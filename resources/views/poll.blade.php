<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-app-layout>
    <div id="app"></div>
</x-app-layout>

@viteReactRefresh
@vite('resources/js/app.jsx')
