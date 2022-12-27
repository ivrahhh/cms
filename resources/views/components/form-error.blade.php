@props(['for'])

@error($for)
    <span class="text-xs text-red-600">{{ $message }}</span>
@enderror