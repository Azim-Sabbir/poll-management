<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Polls') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center p-6">
                <form class="w-full max-w-lg" method="post" action="{{ route('polls.store') }}">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3 space-x-3">
                            <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2" for="option-1">
                                Question for the poll
                            </label>
                            @if(old('question'))
                                <input value="{{ old('question') }}" name="question" id="question" type="text" placeholder="Poll question" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 pr-3">
                            @else
                                <input name="question" id="question" type="text" placeholder="Poll question" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 pr-3">
                            @endif
                            @error('question')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div id="option-container" class="pt-10">
                        <h2 class="text-center text-blue-50 text-2xl font-bold mb-4">Add Options for the Poll</h2>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2" for="option-1">
                                    Option 1
                                </label>
                                <div class="flex items-center gap-2">
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="option-1" type="text" placeholder="Option" name="options[]">
                                    <button type="button" class="remove-option text-red-500 hover:text-red-700 border border-blue-500 hover:bg-blue-500 font-bold py-2 px-4 rounded">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('options')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="button" id="add-option" class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-bold py-2 px-4 rounded mb-6">
                        Add Option
                    </button>
                    <button type="submit" class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-bold py-2 px-4 rounded">
                        Create Poll
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">All Polls</h2>
                    <div class="relative overflow-x-auto rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Poll</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($polls as $poll)
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $poll->question }}
                                    </td>
                                    <td class="px-6 py-4 space-x-2">
                                        <a
                                            href="{{ route('polls.show', $poll->id) }}"
                                            class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300"
                                        >
                                            Result
                                        </a>
                                        <button
                                            onclick="copyToClipboard('{{ url("/poll/$poll->slug") }}')"
                                            class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300"
                                        >
                                            Share Link
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const optionContainer = document.getElementById('option-container');
        const addOptionButton = document.getElementById('add-option');
        let optionCount = 1;

        addOptionButton.addEventListener('click', function() {
            optionCount++;
            const newOptionField = document.createElement('div');
            newOptionField.classList.add('flex', 'flex-wrap', '-mx-3', 'mb-6');

            newOptionField.innerHTML = `
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="option-${optionCount}">
                        Option ${optionCount}
                    </label>
                    <div class="flex items-center gap-2">
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="option-${optionCount}" type="text" placeholder="Option" name="options[]">
                        <button type="button" class="remove-option text-red-500 hover:text-red-700 border border-blue-500 hover:bg-blue-500 font-bold py-2 px-4 rounded">
                            Remove
                        </button>
                    </div>
                </div>
            `;

            optionContainer.appendChild(newOptionField);
        });

        optionContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-option')) {
                const optionField = event.target.closest('.flex.flex-wrap.-mx-3.mb-6');
                if (optionField) {
                    optionField.remove();
                }
            }
        });

        const firstOptionLabel = document.querySelector('#option-container label');
        if (firstOptionLabel) {
            firstOptionLabel.textContent = 'Option 1';
        }
    });
</script>

<script>
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert("Link copied to clipboard");
                })
                .catch((err) => {
                    console.error("Clipboard copy failed", err);
                });
        } else {
            const textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            alert("Link copied to clipboard");
        }
    }
</script>
