<x-public-layout>
    @if(isset($themeSettings['hero_title']))
        @section('title', $themeSettings['hero_title'] . ' - ' . config('app.name', 'Sekolah Hub'))
    @endif

    <div class="flex flex-col gap-0 overflow-hidden">
        @foreach($activeSections as $section)
            @includeIf('sections.' . $section)
        @endforeach
    </div>
</x-public-layout>
