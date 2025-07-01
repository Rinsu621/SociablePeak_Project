@extends('layout')

@section('style')
@endsection

@section('content')
<div class="container">
    <h3><span style="color: gray">Trending Posts <i class="fa-solid fa-fire" style="color: #f29d26;"></i></span></h3>
    <hr>

    @foreach ($topPosts as $post)
    <div class="card mb-4">
        <div class="card-body">
            <div class="post-item">
                <div class="user-post-data py-3">
                    <div class="d-flex justify-content-between">
                        <div class="me-3 user-img">
                            @if($post->user && $post->user->profilePicture && $post->user->profilePicture->file_path)
                                <img src="{{ Storage::url($post->user->profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" />
                            @else
                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" />
                            @endif
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0 d-inline-block">
                                        <a href="#" class="text-dark">{{ $post->user->name ?? 'Unknown User' }}</a>
                                    </h5>
                                    <p class="ms-1 mb-0 d-inline-block">Update</p>
                                    <p class="mb-0">{{ convertToTimeAgo($post->created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="user-post mb-2">
                    <p>{{ $post->description }}</p>
                </div>

                @if($post->images->isNotEmpty())
                <div class="post-images mb-2">
                    @foreach($post->images as $image)
                        <img src="{{ Storage::url($image->file_path) }}" alt="Post Image" class="img-fluid rounded mb-2">
                    @endforeach
                </div>
                @endif

                <div class="comment-area mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="like-block position-relative d-flex align-items-center">
                            <div class="like-data me-3">
                                <button class="btn btn-link p-0 like-button" data-post-id="{{ $post->id }}">
                                    {{ $post->likes->where('user_id', auth()->id())->count() ? 'Unlike' : 'Like' }}
                                </button>
                            </div>

                            <div class="total-like-block me-3">
                                <div class="dropdown">
                                    <span class="dropdown-toggle" data-bs-toggle="dropdown" role="button">
                                        <span class="like-count">{{ $post->likes_count }}</span> {{ $post->likes->count() == 1 ? 'Like' : 'Likes' }}
                                    </span>
                                    <div class="dropdown-menu">
                                        @foreach($post->likes as $like)
                                            <a class="dropdown-item" href="#">{{ $like->user->name ?? 'Unknown' }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="total-comment-block">
                                <div class="dropdown">
                                    <span class="dropdown-toggle" data-bs-toggle="dropdown" role="button">
                                        {{ $post->comments->count() }} {{ $post->comments->count() == 1 ? 'Comment' : 'Comments' }}
                                    </span>
                                    <div class="dropdown-menu">
                                        @foreach($post->comments as $comment)
                                            <a class="dropdown-item" href="#">{{ $comment->user->name ?? 'Unknown' }}: {{ $comment->comment }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <ul class="post-comments list-inline p-0 m-0">
                        @foreach($post->comments as $comment)
                        <li class="mb-2">
                            <div class="d-flex">
                                <div class="user-img">
                                    @if($comment->user && $comment->user->profilePicture && $comment->user->profilePicture->file_path)
                                        <img src="{{ Storage::url($comment->user->profilePicture->file_path) }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                    @else
                                        <img src="{{ asset('/images/template/user/default.jpg') }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                    @endif
                                </div>
                                <div class="comment-data-block ms-3">
                                    <h6>{{ $comment->user->name ?? 'Unknown User' }}</h6>
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                    <span class="small text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <form class="comment-text d-flex align-items-center mt-3" action="{{ route('post.comment', $post->id) }}" method="POST">
                        @csrf
                        <input type="text" name="comment" class="form-control rounded" placeholder="Enter Your Comment" required>
                        <div class="comment-attagement d-flex">
                            <a href="javascript:void(0);"><i class="ri-link me-3"></i></a>
                            <a href="javascript:void(0);"><i class="ri-user-smile-line me-3"></i></a>
                            <a href="javascript:void(0);"><i class="ri-camera-line me-3"></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const likeButtons = document.querySelectorAll('.like-button');

        likeButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const postId = this.dataset.postId;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/post/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ _token: token })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const likeCountSpan = this.closest('.like-block').querySelector('.like-count');

                        if (data.liked) {
                            likeCountSpan.textContent = parseInt(likeCountSpan.textContent) + 1;
                            this.textContent = 'Unlike';
                        } else {
                            likeCountSpan.textContent = parseInt(likeCountSpan.textContent) - 1;
                            this.textContent = 'Like';
                        }
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection
