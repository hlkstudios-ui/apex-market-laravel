@props(['brand'])

<span class="brand-logo" data-brand-logo>
    <img
        class="brand-logo__image"
        src="{{ $brand->logo_url }}"
        alt="{{ $brand->name }} wordmark"
        loading="lazy"
        decoding="async"
        onerror="this.onerror=null;this.src='{{ route('brands.mark', $brand) }}?v=2'"
    >
</span>
