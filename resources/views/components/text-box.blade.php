@php
    $border = $errors->has($attributes->get('name'))
            ? 'bg-red-50 border border-red-300 focus:border-red-600 focus:ring-1 focus:ring-red-600 focus:outline-none'
            : 'bg-gray-50 border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 focus:outline-none';
@endphp

<input {{ $attributes->merge(["class" => "block w-full rounded-lg text-sm p-2.5 {$border}"]) }}>