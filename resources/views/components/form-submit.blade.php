@props(['label'])

<!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
<div>
    @csrf
    <button type="submit" {{ $attributes->merge(["class" => "block w-full rounded-lg text-sm font-bold p-2.5 bg-slate-900 text-white border-2 border-transparent transition ease-in-out duration-300 hover:bg-white hover:text-slate-900 hover:border-slate-900"]) }}>
        {{ $label }}
    </button>
</div>