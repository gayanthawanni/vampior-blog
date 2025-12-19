<x-app-layout>
    <div class="max-w-6xl mx-auto py-10">
        <a href="{{ route('posts.create') }}"
           class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
            Create New Post
        </a>

        @foreach($posts as $post)
            <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded shadow">
                <h2 class="text-xl font-bold">{{ $post->title }}</h2>
                <p class="text-sm text-gray-500">
                    By {{ $post->user->name }}
                </p>

                <a href="{{ route('blog.show', $post->slug) }}"
                   class="text-blue-500">
                    Read more
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
