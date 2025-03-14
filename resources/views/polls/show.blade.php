<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Poll Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                    {{ $poll->question }}
                </h3>

                @php($totalVotes = $poll->options->sum('total_votes'))
                <div class="mb-6 text-gray-600 dark:text-gray-400" id="total-votes">
                    Total Votes: {{ $totalVotes }}
                </div>

                <div class="space-y-4">
                    @forelse($poll->options as $option)
                        <div id="option-{{ $option->id }}" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $option->title }}
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 vote-count">
                                    {{ $option->total_votes }} votes · {{ $totalVotes > 0 ? round(($option->total_votes / $totalVotes) * 100, 2) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div
                                    class="bg-blue-500 h-2.5 rounded-full progress-bar"
                                    style="width: {{ $totalVotes > 0 ? ($option->total_votes / $totalVotes) * 100 : 0 }}%;"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">No options found.</p>
                    @endforelse
                        <div class="mt-6 text-gray-600 dark:text-gray-400">
                            See the result in real time
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.0.1/dist/web/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.17.1/echo.iife.min.js" integrity="sha512-+niSJwvEHJjkzsB/dPujR2RRenWKIx7jZ/R6Q1XVY3ZmQ1s6BN5coO9smFctXZ29kjGO98vJ0Rx+K+n3pFkWMw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env('REVERB_APP_KEY') }}',
            wsHost: window.location.hostname,
            wsPort: 8080,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        const pollId = {{ $poll->id }};

        window.Echo.channel(`poll.${pollId}`)
            .listen('VoteUpdated', (data) => {
                updatePollResults(data);
            });

        function updatePollResults(data) {
            document.getElementById('total-votes').textContent = `Total Votes: ${data.total_votes}`;

            data.options.forEach((option) => {
                const optionElement = document.getElementById(`option-${option.id}`);
                if (optionElement) {
                    const voteCountElement = optionElement.querySelector('.vote-count');
                    const progressBarElement = optionElement.querySelector('.progress-bar');

                    if (voteCountElement) {
                        voteCountElement.textContent = `${option.votes} votes · ${data.total_votes > 0 ? round((option.votes / data.total_votes) * 100, 2) : 0}%`;
                    }

                    if (progressBarElement) {
                        progressBarElement.style.width = `${data.total_votes > 0 ? (option.votes / data.total_votes) * 100 : 0}%`;
                    }
                }
            });
        }

        function round(value, decimals) {
            return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
        }
    </script>
</x-app-layout>
