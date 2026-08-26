@props([
    'name',
    'options' => [],
    'selected' => '',
    'label' => 'Choose an option',
])

@php
    $dropdownId = 'dropdown-'.preg_replace('/[^a-z0-9_-]+/i', '-', $name);
    $selectedKey = (string) $selected;
    $selectedLabel = $options[$selectedKey] ?? reset($options);
@endphp

<div class="ui-dropdown" data-dropdown>
    <input type="hidden" name="{{ $name }}" value="{{ $selectedKey }}" data-dropdown-input>
    <button
        class="ui-dropdown__trigger"
        type="button"
        aria-label="{{ $label }}"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="{{ $dropdownId }}"
        data-dropdown-trigger
    >
        <span class="ui-dropdown__value" data-dropdown-value>{{ $selectedLabel }}</span>
        <span class="ui-dropdown__arrow" aria-hidden="true">
            <svg viewBox="0 0 12 8"><path d="m1 1.5 5 5 5-5"/></svg>
        </span>
    </button>
    <div class="ui-dropdown__panel" id="{{ $dropdownId }}" role="listbox" aria-label="{{ $label }}" hidden data-dropdown-panel>
        @foreach($options as $value => $optionLabel)
            <button
                class="ui-dropdown__option"
                type="button"
                role="option"
                aria-selected="{{ (string) $value === $selectedKey ? 'true' : 'false' }}"
                data-dropdown-option
                data-value="{{ $value }}"
            >{{ $optionLabel }}</button>
        @endforeach
    </div>
</div>
