<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Poll Participation</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @else
        <style>
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen">
<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto p-6">
    @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            @else
                <a
                    href="{{ route('login') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                >
                    Log in
                </a>

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Register
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</header>

<main class="flex-1 flex items-center justify-center p-6">
    <div class="w-full lg:max-w-4xl max-w-[335px]">
        <div class="bg-white dark:bg-[#1b1b18] rounded-lg shadow-lg" style="padding: 50px">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                Welcome to the Poll Dashboard
            </h1>

            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-8">
                Participate and analyze polls in real-time. Get started by pasting a poll link below to vote and see live updates.
            </p>

            <div class="mb-8">
                <input
                    id="poll-link"
                    type="text"
                    placeholder="Paste Poll Link Here"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:border-[#1b1b18] dark:focus:border-[#EDEDEC]"
                />
            </div>

            <div class="flex gap-4">
                <a
                    onclick="goToPoll(event)"
                    href="#"
                    class="inline-block px-6 py-2 border border-[#1b1b18] dark:border-[#EDEDEC] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#1b1b18] dark:hover:bg-[#EDEDEC] hover:text-white dark:hover:text-[#1b1b18] transition-colors"
                >
                    Go
                </a>
                <a
                    href="/poll"
                    class="inline-block px-6 py-2 border border-[#1b1b18] dark:border-[#EDEDEC] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#1b1b18] dark:hover:bg-[#EDEDEC] hover:text-white dark:hover:text-[#1b1b18] transition-colors"
                >
                    See all available poll
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="w-full lg:max-w-4xl max-w-[335px] text-sm mx-auto p-6 text-center text-[#706f6c] dark:text-[#A1A09A]">
    &copy; {{ date('Y') }} Poll Dashboard. All rights reserved.
</footer>
<script type="text/javascript">
    function goToPoll(e) {
        e.preventDefault();

        const pollLink = document.getElementById('poll-link').value;
        if (!pollLink) {
            alert('Please paste a poll link to proceed.');
            return;
        }
        window.location.href = pollLink;
    }
</script>
</body>
</html>
