@props(['value'])

<label {{ $attributes->merge(["class" => "block text-sm font-semibold mb-2"]) }}>
    {{ $value }}
</label>