<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BrandLogoManifestTest extends TestCase
{
    public function test_every_verified_logo_has_a_valid_local_svg_and_provenance(): void
    {
        $manifest = json_decode(file_get_contents(__DIR__.'/../../public/brand-logos/manifest.json'), true, flags: JSON_THROW_ON_ERROR);

        foreach ($manifest['logos'] as $slug => $logo) {
            $path = __DIR__.'/../../public/brand-logos/'.$logo['local_file'];
            $this->assertSame('verified', $logo['status'], $slug);
            $this->assertStringStartsWith('https://', $logo['source_page'], $slug);

            if (! str_starts_with($logo['source_page'], 'https://commons.wikimedia.org/wiki/File:')) {
                $this->assertArrayHasKey('vector_source', $logo, $slug);
                $this->assertStringStartsWith('https://', $logo['vector_source'], $slug);

                $isCuratedVector = str_starts_with(
                    $logo['vector_source'],
                    'https://raw.githubusercontent.com/simple-icons/simple-icons/',
                );
                $isWikipediaVector = preg_match(
                    '~^https://[a-z-]+\.wikipedia\.org/wiki/(?:File|Datei):~',
                    $logo['source_page'],
                ) === 1 && str_starts_with(
                    $logo['vector_source'],
                    'https://upload.wikimedia.org/wikipedia/',
                );
                $sourceHost = parse_url($logo['source_page'], PHP_URL_HOST);
                $vectorHost = parse_url($logo['vector_source'], PHP_URL_HOST);
                $isOfficialAsset = $sourceHost === $vectorHost
                    || str_ends_with($vectorHost, '.'.$sourceHost);

                $this->assertTrue($isCuratedVector || $isWikipediaVector || $isOfficialAsset, $slug.' must use an official or curated vector source.');
            }

            $this->assertFileExists($path, $slug);
            $svg = file_get_contents($path);
            $this->assertStringContainsString('<svg', $svg, $slug);
            $this->assertStringNotContainsStringIgnoringCase('<script', $svg, $slug);
            $this->assertStringNotContainsStringIgnoringCase('javascript:', $svg, $slug);
            $this->assertStringNotContainsStringIgnoringCase('<image', $svg, $slug.' must be genuine vector artwork, not a raster image wrapper.');
            $this->assertStringNotContainsStringIgnoringCase('data:image/', $svg, $slug.' must not embed raster image data.');
        }

        $files = glob(__DIR__.'/../../public/brand-logos/*.svg');
        $this->assertCount(count($manifest['logos']), $files, 'Every local SVG must be represented in the provenance manifest.');

        foreach ($manifest['pending'] ?? [] as $slug => $logo) {
            $this->assertArrayNotHasKey($slug, $manifest['logos'], $slug);
            $this->assertSame('source_verified_asset_pending', $logo['status'], $slug);
            $this->assertStringStartsWith('https://', $logo['source_page'], $slug);
            $this->assertNotSame('', $logo['reason'], $slug);
        }
    }
}
