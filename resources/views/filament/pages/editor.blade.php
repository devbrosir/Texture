@php
    use App\Http\Resources\PartResource;
    use App\Models\Part;
    $part = new PartResource(Part::find(request('part_id')))->toArray(request());

    $manifest = json_decode(file_get_contents(public_path('editor/.vite/manifest.json')),true);
    $entry = $manifest['src/main.js'];
@endphp

<div>
    <x-filament-panels::page>
        <div id="app" class="w-screen h-screen" dir="ltr"
             style="position: fixed; z-index: 99; inset:0;background-color: white"
        ></div>
    </x-filament-panels::page>

    @if(isset($entry['css']))
        @foreach($entry['css'] as $css)
            <link rel="stylesheet" href="{{ asset('editor/' . $css) }}">
        @endforeach
    @endif
    <script type="module" src="{{ asset('editor/' . $entry['file']) }}"></script>

    <script>
        function getTextures(page = 1) {
            return fetch('/textures?page=' + page, {
                headers: __headers(),
            })
        }

        function getPart() {
            return {{\Illuminate\Support\Js::from($part)}}
        }

        function setMaskConfig(config, defaultTextureId = null) {
            return fetch('/parts/{{request('part_id')}}/set-mask', {
                method: 'PUT',
                headers: __headers(),
                body: JSON.stringify({mask_config: config, default_texture_id: defaultTextureId}),
            })
        }

        function __headers() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': __csrfToken(),
            }
        }

        function __csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }

        window.Panel = {getTextures, getPart, setMaskConfig};
    </script>
</div>
