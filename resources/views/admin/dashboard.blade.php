<form method="POST" action="{{ route('admin.posts.approve', $post) }}">
    @csrf
    <button class="bg-green-600 text-white px-2 py-1 rounded">Approve</button>
</form>

<form method="POST" action="{{ route('admin.posts.reject', $post) }}">
    @csrf
    <button class="bg-yellow-600 text-white px-2 py-1 rounded">Reject</button>
</form>
