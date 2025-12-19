<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4">

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- USERS --}}
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Users</h2>

        @forelse($users as $user)
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 mb-2 rounded shadow">
                <div class="text-gray-900 dark:text-gray-100">
                    <p class="font-medium">{{ $user->name }}</p>
                    <p class="text-sm">{{ $user->email }}</p>
                    <p class="text-sm italic">
                        Role: {{ $user->getRoleNames()->first() ?? 'none' }}
                    </p>
                </div>

                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex gap-2">
                        @csrf
                        <select name="role" class="border rounded p-1 dark:bg-gray-700 dark:border-gray-600 text-black dark:text-white">
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                            <option value="editor" {{ $user->hasRole('editor') ? 'selected' : '' }}>Editor</option>
                            <option value="reader" {{ $user->hasRole('reader') ? 'selected' : '' }}>Reader</option>
                        </select>

                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                            Update
                        </button>
                    </form>
                @else
                    <span class="text-sm text-gray-500 italic">(Your account)</span>
                @endif
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No users found.</p>
        @endforelse

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        {{-- POSTS --}}
        <h2 class="text-xl font-semibold mt-10 mb-4 text-gray-900 dark:text-gray-100">Posts</h2>

        @forelse($posts as $post)
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 mb-2 rounded shadow">
                <span class="text-gray-900 dark:text-gray-100">{{ $post->title }}</span>

                <form method="POST" action="{{ route('admin.posts.delete', $post) }}">
                    @csrf
                    @method('DELETE')

                    <button
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                        onclick="return confirm('Are you sure you want to delete this post?')">
                        Delete
                    </button>
                </form>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No posts found.</p>
        @endforelse

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

    </div>
</x-app-layout>
