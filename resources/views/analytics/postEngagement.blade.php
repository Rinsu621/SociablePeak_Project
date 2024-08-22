<!-- resources/views/post_engagement.blade.php -->

@extends('layout')
@section('style')
<style>
    .avatar-60 {
    width: 60px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
}

.user-img {
    width: 60px;
    height: 55px;
    overflow: hidden;
    border-radius: 50%;
}
</style>
@endsection

@section('content')
<div class="container">
    <h2>Number of Posts Over Time</h2>
    <canvas id="postsChart" width="400" height="200"></canvas>
</div>


<div class="container" style="margin-top:40px">
    <h2 style="margin-bottom:40px;">Most Liked and Commented Post</h2>
    @foreach ($posts as $item)
        <div class="card">
            <div class="card-body">
                <div class="post-item">
                    <div class="user-post-data py-3">
                        <div class="d-flex justify-content-between">
                            <div class="me-3 user-img">
                                @if($profilePicture && $profilePicture->file_path)
                                <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" />
                                @else
                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" />
                                @endif
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <div class="">
                                        <h5 class="mb-0 d-inline-block"><a href="#" class="">{{ auth()->user()->name }}</a></h5>
                                        <p class="ms-1 mb-0 d-inline-block">Update Status</p>
                                        <p class="mb-0">{{ convertToTimeAgo($item->created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-post">
                        <p>{{ $item->description }}</p>
                    </div>

                    {{-- Check if the post has images and display them --}}
                    @if($item->images->isNotEmpty())
                        <div class="post-images">
                            @foreach($item->images as $image)
                                <img src="{{ Storage::url($image->file_path) }}" alt="Post Image" class="img-fluid rounded">
                            @endforeach
                        </div>
                    @endif

                    <div class="comment-area mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="like-block position-relative d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="like-data">
                                        <button class="btn btn-link p-0 like-button" data-post-id="{{ $item->id }}">
                                            @if($item->likes->where('user_id', auth()->id())->count())
                                                Unlike
                                            @else
                                                Like
                                            @endif
                                        </button>
                                    </div>
                                    <div class="total-like-block ms-2 me-3">
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                <span class="like-count">{{ $item->likes->count() }}</span> {{ $item->likes->count() == 1 ? 'Like' : 'Likes' }}
                                            </span>
                                            <div class="dropdown-menu">
                                                @foreach($item->likes as $like)
                                                    <a class="dropdown-item" href="#">{{ $like->user->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="total-comment-block">
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                            {{ $item->comments->count() }} {{ $item->comments->count() == 1 ? 'Comment' : 'Comments' }}
                                        </span>
                                        <div class="dropdown-menu">
                                            @foreach($item->comments as $comment)
                                                <a class="dropdown-item" href="#">{{ $comment->user->name }}: {{ $comment->comment }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <ul class="post-comments list-inline p-0 m-0">
                            @foreach($item->comments as $comment)
                                <li class="mb-2">
                                    <div class="d-flex">
                                        <div class="user-img">
                                            @if($comment->user->profilePicture && $comment->user->profilePicture->file_path)
                                                <img src="{{ Storage::url($comment->user->profilePicture->file_path) }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                            @else
                                                <img src="{{ asset('/images/template/user/default.jpg') }}" alt="userimg" class="avatar-35 rounded-circle img-fluid">
                                            @endif
                                        </div>
                                        <div class="comment-data-block ms-3">
                                            <h6>{{ $comment->user->name }}</h6>
                                            <p class="mb-0">{{ $comment->comment }}</p>
                                            <div class="d-flex flex-wrap align-items-center comment-activity">
                                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <form class="comment-text d-flex align-items-center mt-3" action="{{ route('post.comment', $item->id) }}" method="POST">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = @json($labels); // Example: ['January', 'February', ...]
        const data = {
            labels: labels,
            datasets: [{
                label: 'Number of Posts',
                data: @json($postsData), // Example: [10, 20, 15, ...]
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        };

        const config = {
            type: 'line',
            data: data,
        };

        const postsChart = new Chart(
            document.getElementById('postsChart'),
            config
        );
    });
</script>
@endsection
