<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="app"></div>

@viteReactRefresh
@vite('resources/js/app.jsx')
