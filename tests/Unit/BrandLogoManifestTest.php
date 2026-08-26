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
            $this->assertStringStartsWith('https://commons.wikimedia.org/wiki/File:', $logo['source_page'], $slug);
            $this->assertFileExists($path, $slug);
            $this->assertStringContainsString('<svg', file_get_contents($path), $slug);
        }

        $files = glob(__DIR__.'/../../public/brand-logos/*.svg');
        $this->assertCount(count($manifest['logos']), $files, 'Every local SVG must be represented in the provenance manifest.');
    }
}
