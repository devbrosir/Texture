@php
    $manifest = json_decode(file_get_contents(public_path('editor/.vite/manifest.json')),true);
@endphp

<div>
    <x-filament-panels::page>
        <div id="app" class="w-screen h-screen" dir="ltr"
             style="position: fixed; z-index: 99; inset:0;background-color: white"
        ></div>
    </x-filament-panels::page>

    <link rel="stylesheet" href="{{ asset('editor/' . $manifest['style.css']['file']) }}">
    <script type="module" src="{{ asset('editor/' . $manifest['src/main.ts']['file']) }}"></script>
</div>
