<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Polls') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center p-6">
                <form class="w-full max-w-lg" method="post" action="{{route('polls.store')}}">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3 space-x-3">
                            <input name="question" id="question" type="text" placeholder="poll question" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 pr-3">
                        </div>
                    </div>
                    <div id="option-container">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="option-1">
                                    Option 1
                                </label>
                                <div class="flex items-center gap-2">
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="option-1" type="text" placeholder="Option" name="options[]">
                                    <button type="button" class="remove-option text-red-500 hover:text-red-700 border border-blue-500 hover:bg-blue-500 font-bold py-2 px-4 rounded">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <caption class="caption-top bold">
                        Polls
                    </caption>
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Poll
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($polls as $poll)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $poll->question }}
                            </th>
                            <td class="px-6 py-4">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    view details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="option-${optionCount}" type="text" placeholder="Option" name="options[]">
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
