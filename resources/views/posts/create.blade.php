<x-app-layout>
    {{-- Trix Editor Assets --}}
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Create New Post</h1>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
                    placeholder="Enter post title"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" 
                    required>
            </div>

            {{-- Content (Trix Editor) --}}
            <div>
                <label for="content" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Content
                </label>
                <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                <trix-editor input="content" class="trix-content border rounded dark:bg-gray-800 dark:border-gray-700"></trix-editor>
            </div>

            {{-- Image --}}
            <div>
                <label for="image" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Featured Image
                </label>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    accept="image/*"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Max size: 2MB</p>
            </div>

            {{-- Category --}}
            <div>
                <label for="category" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Category
                </label>
                <input 
                    type="text" 
                    name="category" 
                    id="category" 
                    value="{{ old('category') }}"
                    placeholder="e.g., Technology, Lifestyle"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Tags --}}
            <div>
                <label for="tags" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Tags
                </label>
                <input 
                    type="text" 
                    name="tags" 
                    id="tags" 
                    value="{{ old('tags') }}"
                    placeholder="e.g., laravel, php, web development"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Separate tags with commas</p>
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition font-semibold">
                    Publish Post
                </button>
                <a 
                    href="{{ route('posts.index') }}" 
                    class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-6 py-2 rounded transition font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Optional: Style Trix Editor for Dark Mode --}}
    <style>
        trix-editor {
            min-height: 200px;
        }
        .dark trix-editor {
            color: #fff;
        }
        .dark trix-toolbar * {
            color: #fff;
        }
    </style>
</x-app-layout>