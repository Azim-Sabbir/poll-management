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

                    <div class="space-y-4">
                        @forelse($poll->options as $option)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $option->title }}
                                </span>
                                    <span class="text-gray-600 dark:text-gray-400">
                                    {{ $option->total_votes }} votes
                                </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                    <div style="width: {{ ($option->total_votes / $totalVotes) * 100 }}%; background: #2b70c8; height: 10px;border-radius: 20px">&nbsp;</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-400">No options found.</p>
                        @endforelse
                    </div>

                    <div class="mt-6 text-gray-600 dark:text-gray-400">
                        Total Votes: {{ $totalVotes }}
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
