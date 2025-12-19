<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Post Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-100">
                {{ $post->title }}
            </h1>

            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <span>By <strong>{{ $post->user->name }}</strong></span>
                <span>•</span>
                <span>{{ $post->created_at->diffForHumans() }}</span>
                @if($post->category)
                    <span>•</span>
                    <span class="bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded">
                        {{ $post->category }}
                    </span>
                @endif
            </div>

            {{-- Edit/Delete Buttons (Owner Only) --}}
            @auth
                @if(auth()->id() === $post->user_id)
                    <div class="flex gap-2 mb-4">
                        <a href="{{ route('posts.edit', $post) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                            Edit Post
                        </a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this post?')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                                Delete Post
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Featured Image --}}
        @if($post->image)
            <img src="{{ asset('storage/'.$post->image) }}"
                 alt="{{ $post->title }}"
                 class="w-full mb-6 rounded-lg shadow-lg">
        @endif

        {{-- Post Content --}}
        <div class="prose prose-lg dark:prose-invert max-w-none mb-8">
            {!! $post->content !!}
        </div>

        {{-- Tags --}}
        @if($post->tags && is_array($post->tags))
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($post->tags as $tag)
                    <span class="bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-full text-sm">
                        #{{ trim($tag) }}
                    </span>
                @endforeach
            </div>
        @endif

        <hr class="my-8 border-gray-300 dark:border-gray-700">

        {{-- Like & Save Buttons --}}
        <div class="flex gap-4 mb-8">
            @auth
                {{-- Like/Unlike Button --}}
                @if($post->isLikedBy(auth()->user()))
                    <form method="POST" action="{{ route('posts.unlike', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                            ❤️ Unlike ({{ $post->likes()->count() }})
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('posts.like', $post) }}">
                        @csrf
                        <button class="flex items-center gap-2 bg-gray-200 hover:bg-red-500 dark:bg-gray-700 hover:text-white px-4 py-2 rounded transition">
                            🤍 Like ({{ $post->likes()->count() }})
                        </button>
                    </form>
                @endif

                {{-- Save/Unsave Button --}}
                @if($post->isBookmarkedBy(auth()->user()))
                    <form method="POST" action="{{ route('posts.unsave', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                            ⭐ Saved
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('posts.save', $post) }}">
                        @csrf
                        <button class="flex items-center gap-2 bg-gray-200 hover:bg-yellow-500 dark:bg-gray-700 hover:text-white px-4 py-2 rounded transition">
                            ☆ Save for Later
                        </button>
                    </form>
                @endif
            @else
                {{-- Guest View --}}
                <div class="text-gray-500 dark:text-gray-400">
                    ❤️ {{ $post->likes()->count() }} likes
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to like or save this post
                </p>
            @endauth
        </div>

        <hr class="my-8 border-gray-300 dark:border-gray-700">

        {{-- Comments Section --}}
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                Comments ({{ $post->comments()->count() }})
            </h3>

            {{-- Comment Form (Authenticated Users Only) --}}
            @auth
                <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                    <textarea name="comment"
                              rows="3"
                              class="w-full p-3 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                              placeholder="Write a comment..."
                              required></textarea>

                    <button type="submit" 
                            class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Post Comment
                    </button>
                </form>
            @else
                <p class="mb-6 text-gray-600 dark:text-gray-400">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to leave a comment
                </p>
            @endauth

            {{-- Comments List --}}
            @forelse($post->comments()->latest()->get() as $comment)
                <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <strong class="text-gray-900 dark:text-gray-100">
                                    {{ $comment->user->name }}
                                </strong>
                                <small class="text-gray-500 dark:text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="text-gray-800 dark:text-gray-200">
                                {{ $comment->comment }}
                            </p>
                        </div>

                        {{-- Delete Comment (Comment Owner or Post Owner) --}}
                        @auth
                            @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Delete this comment?')"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 italic">
                    No comments yet. Be the first to comment!
                </p>
            @endforelse
        </div>

    </div>
</x-app-layout>