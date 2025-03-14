<meta name="csrf-token" content="{{ csrf_token() }}">
<div data-theme="light" >
    <x-app-layout>
        <div id="app"></div>
    </x-app-layout>
</div>

@viteReactRefresh
@vite('resources/js/app.jsx')
