<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Poll') }}
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
                <form class="w-full max-w-lg" method="post" action="{{ route('polls.update', $poll->id) }}">
                    @csrf
                    @method('PUT')
                    <h2 class="text-center text-blue-50 text-2xl font-bold mb-4">Add New Option</h2>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2" for="new-option">
                                New Option
                            </label>
                            <input
                                value="{{ $poll->question }}"
                                name="question"
                                id="question"
                                type="text"
                                placeholder="Poll question"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            >
                            @error('question')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-bold py-1 px-3 rounded text-sm transition-all duration-300">
                        Update Poll Title
                    </button>
                </form>
            </div>

            <!-- Existing Options -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center p-6 mb-8 mt-8">
                <div class="w-full max-w-lg">
                    <h2 class="text-center text-blue-50 text-2xl font-bold mb-4">Current Options</h2>
                    <p class="p-3 text-blue-50">( Removing an option will remove the votes with this option too )</p>
                    @foreach($poll->options as $option)
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <div class="flex items-center gap-2">
                                    <form
                                        method="post"
                                        action="{{ route('polls.option.update', $option->id) }}"
                                        id="update-form-{{ $option->id }}"
                                        class="flex-grow"
                                    >
                                    @csrf
                                    @method('PUT')
                                    <div class="pb-3">
                                        <input
                                            value="{{ $option->title }}"
                                            name="title"
                                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                            type="text"
                                            placeholder="Option"
                                        >
                                        @error('title')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    </form>

                                    <button
                                        type="submit"
                                        form="update-form-{{ $option->id }}"
                                        class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-bold py-1 px-3 rounded text-sm transition-all duration-300"
                                    >
                                        Update
                                    </button>

                                    <!-- Delete Button -->
                                    <form method="post" action="{{ route('polls.option.destroy', $option->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-red-500 hover:text-red-700 border border-red-500 hover:bg-red-500 font-bold py-1 px-3 rounded text-sm transition-all duration-300"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Add New Option Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center p-6">
                <form class="w-full max-w-lg" method="post" action="{{ route('polls.option.store', $poll->id) }}">
                    @csrf
                    <h2 class="text-center text-blue-50 text-2xl font-bold mb-4">Add New Option for the pool</h2>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2" for="new-option">
                                New Option
                            </label>
                            <input
                                name="title"
                                id="new-option"
                                type="text"
                                placeholder="New option"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            >
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-bold py-1 px-3 rounded text-sm transition-all duration-300">
                        Add Option
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
