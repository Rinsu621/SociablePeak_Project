@extends('layout')
@section('styles')
<style>

.avatar-60 {
    width: 60px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
}
.avatar-40
{
    width: 40px !important;
    height: 40px !important;
    border-radius: 50%;
    object-fit: cover;
}

.user-img {
    width: 60px;
    height: 55px;
    overflow: hidden;
    border-radius: 50%;
}



.profile-btn:hover {
    color: white;  /* Text color when hovering */
    background-color: #268fff; /* Button background color when hovering */
}



</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8 row m-0 p-0" >
            <div class="col-sm-12">
                <div id="post-modal-data" class="card card-block card-stretch card-height">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Create Post</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="user-img" style="width: 60px; height: 55px; overflow: hidden; border-radius:50%;">
                                @if($profilePicture && $profilePicture->file_path)
                                <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                @else
                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                @endif

                            </div>
                            <form class="post-text ms-3 w-100 " data-bs-toggle="modal" data-bs-target="#post-modal"
                                action="javascript:void();">
                                <input type="text" class="form-control rounded" placeholder="Write something here..."
                                    style="border:none;">
                            </form>
                        </div>
                        <hr>
                        <form class="post-text ms-3 w-100 " data-bs-toggle="modal" data-bs-target="#post-modal"
                        action="javascript:void();">
                        <ul class=" post-opt-block d-flex list-inline m-0 p-0 flex-wrap">
                            <li class="me-3 mb-md-0 mb-2">
                                <a href="#" class="btn btn-soft-primary">
                                    <img src="{{ asset('/images/template/small/07.png') }}" alt="icon" class="img-fluid me-2">
                                    Photo
                                </a>
                            </li>


                        </ul>
                    </form>
                    </div>
                    <div class="modal fade" id="post-modal" tabindex="-1" aria-labelledby="post-modalLabel" aria-hidden="true">
                        <div class="modal-dialog   modal-fullscreen-sm-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="post-modalLabel">Create Post</h5>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                            class="ri-close-fill"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('postStore')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex align-items-center">
                                            <div class="user-img" style="width: 60px; height: 55px; overflow: hidden; border-radius:50%;">
                                                @if($profilePicture && $profilePicture->file_path)
                                                <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                                @else
                                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                                @endif
                                            </div>
                                            {{-- <form class="post-text ms-3 w-100" action="javascript:void();"> --}}
                                                <input type="text" class="form-control rounded" name="description"
                                                    placeholder="Write something here..." style="border:none;">
                                            {{-- </form> --}}
                                        </div>
                                        <div id="image-preview" style="margin-top: 10px;"></div>
                                        <hr>
                                        <ul class="d-flex flex-wrap align-items-center list-inline m-0 p-0">
                                            <li class="col-md-6 mb-3">
                                                <div class="bg-soft-primary rounded p-2 pointer me-3">
                                                   Schedule Post : <input type="date" name="set_time">
                                            </li>
                                            <li class="col-md-6 mb-3">
                                                <div class="bg-soft-primary rounded p-2 pointer me-3"><a
                                                        href="#"></a><img src="{{ asset('/images/template/small/07.png') }}"
                                                        alt="icon" class="img-fluid"><label for="image"> Photo
                                                        <input type="file" id="image" name="image[]" multiple style="display: none;"  >
                                                    </label>
                                                </div>
                                            </li>

                                        </ul>

                                        <button type="submit" class="btn btn-primary d-block w-100 mt-3">Post</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @foreach($posts as $post)
            <div class="col-sm-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="user-post-data">
                            <div class="d-flex justify-content-between">
                                <div class="me-3 user-img" style="width: 60px; height: 55px; overflow: hidden; border-radius:50%;">
                                    @if($post->user->profilePicture && $post->user->profilePicture->file_path)
                                        <img src="{{ Storage::url($post->user->profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                    @else
                                        <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                    @endif
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 d-inline-block">{{ $post->user->name }}</h5>
                                            <span class="mb-0 d-inline-block">Add New Post</span>
                                            <p class="mb-0 text-primary">{{ convertToTimeAgo($post->created_at) }}</p>

                                        </div>
                                        <div class="card-post-toolbar">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p>{{ $post->description }}</p>
                        </div>
                        @if($post->images->count())

                        <div class="user-post">
                            <div class=" d-grid grid-rows-2 grid-flow-col gap-3">
                                <div class="row-span-2 row-span-md-1">
                                    @foreach($post->images as $image)
                                    <img src="{{ asset(Storage::url($image->file_path)) }}" class="img-fluid mb-2">
                                @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="comment-area mt-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="like-block position-relative d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="like-data">
                                            <button class="btn btn-link p-0 like-button" data-post-id="{{ $post->id }}">
                                                @if($post->likes->where('user_id', auth()->id())->count())
                                                    Unlike
                                                @else
                                                    Like
                                                @endif
                                            </button>
                                        </div>
                                        <div class="total-like-block ms-2 me-3">
                                            <div class="dropdown">
                                                <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                    <span class="like-count">{{ $post->likes->count() }}</span> {{ $post->likes->count() == 1 ? 'Like' : 'Likes' }}
                                                </span>
                                                <div class="dropdown-menu">
                                                    @foreach($post->likes as $like)
                                                        <a class="dropdown-item" href="#">{{ $like->user->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="total-comment-block">
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                {{ $post->comments->count() }} {{ $post->comments->count() == 1 ? 'Comment' : 'Comments' }}
                                            </span>
                                            <div class="dropdown-menu">
                                                @foreach($post->comments as $comment)
                                                    <a class="dropdown-item" href="#">{{ $comment->user->name }}: {{ $comment->comment }}</a>
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
                                                @if($comment->user->profilePicture && $comment->user->profilePicture->file_path)
                                                    <img src="{{ Storage::url($comment->user->profilePicture->file_path) }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                                @else
                                                    <img src="{{ asset('/images/template/user/noprofile.jpg') }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                                @endif
                                            </div>
                                            <div class="comment-data-block ms-3">
                                                <h6>{{ $comment->user->name }}</h6>
                                                <p class="mb-0">{{ $comment->comment }}</p>
                                                <div class="d-flex flex-wrap align-items-center comment-activity">

                                                    <span> {{ $comment->created_at->diffForHumans() }} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <form class="comment-text d-flex align-items-center mt-3" action="{{ route('post.comment', $post->id) }}" method="POST">
                                @csrf
                                <input type="text" name="comment" class="form-control rounded" placeholder="Enter Your Comment" required>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if ($loop->iteration % 5 == 0)
            {{-- @foreach ($ads as $key => $item) --}}
                         @php
            // Get only one ad for every 5 posts (ensure you use only the first ad if there are multiple ads)
                                 $item = $ads->random();
                                    @endphp
                                    @if($item)
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="post-item">
                                                <div class="user-post-data py-3">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="me-3 user-img">
                                                            @if($item->business && $item->business->profilePicture && $item->business->profilePicture->file_path)
                                                                 <img src="{{ Storage::url($item->business->profilePicture->file_path) }}" alt="business-img" class="avatar-60 img-fluid rounded-circle" />
                                                             @else
                                                                 <img src="{{ asset('/images/template/business/default.jpg') }}" alt="business-img" class="avatar-60 img-fluid rounded-circle" />
                                                             @endif
                                                        </div>
                                                        <div class="w-100">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="">
                                                                    <h5 class="mb-0 d-inline-block"><a href="#"
                                                                            class="">{{ $item->business->name }}</a>
                                                                    </h5>
                                                                    <p class="ms-1 mb-0 d-inline-block">
                                                                        Update Status
                                                                    </p>
                                                                    <p class="mb-0">
                                                                        {{ convertToTimeAgo($item->created_at) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="user-post">
                                                    <p>
                                                        {{ $item->description }}
                                                    </p>
                                                </div>
                                                {{-- Check if the post has images and display them --}}
                                                @if($item->adimages && $item->adimages->isNotEmpty())
                                                <div class="post-images">
                                                    @foreach($item->adimages as $image)
                                                    @php
                                                    \Log::info('Image Path:', [Storage::url($image->file_path)]);
                                                    @endphp
                                                        <img src="{{ asset($image->file_path) }}" alt="Post Image" class="img-fluid rounded">
                                                    @endforeach

                                                </div>

                                                @endif
                                                    <div class="comment-area mt-3">
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <div class="like-block position-relative d-flex align-items-center">
                                                                <div class="d-flex align-items-center">
                                                                  <div class="like-block d-flex align-items-center gap-2">
                                                                        <button type="button" class="btn btn-link p-0 like-button" data-ad-id="{{ $item->id }}">
                                                                            @if(($item->adLikes->where('user_id', auth()->id())->count())|| ($item->adLikes->where('business_id', Auth::guard('business')->id())->count()))
                                                                                Unlike
                                                                            @else
                                                                                Like
                                                                            @endif
                                                                        </button>

                                                                    <div class="total-like-block ms-2 me-3">
                                                                        <div class="dropdown">
                                                                            <span class="dropdown-toggle" data-bs-toggle="dropdown" role="button">
                                                                                <span class="like-count">{{ $item->adLikes->count() }}</span>
                                                                                <span class="like-text">{{ $item->adLikes->count() === 1 ? 'Like' : 'Likes' }}</span>
                                                                            </span>

                                                                            <div class="dropdown-menu ad-like-list">
                                                                                @foreach($item->adLikes as $like)
                                                                                    <a class="dropdown-item" href="#">
                                                                                        {{ $like->user ? $like->user->name : ($like->business ? $like->business->name : 'Unknown') }}
                                                                                    </a>
                                                                                @endforeach
                                                                            </div>
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
                                                                                <a class="dropdown-item" href="#"> {{ $comment->user?->name ?? $comment->business?->name ?? 'Unknown' }}: {{ $comment->comment }}</a>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="total-comment-block">
                                                                    <a href="{{ route('businessprofile', ['id' => $item->business->id]) }}" class="btn btn-outline-primary profile-btn" style="margin-left:270px">
                                                                        <i class="ri-user-line"></i> Show Profile
                                                                    </a>
                                                                </div>


                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <ul class="post-comments list-inline p-0 m-0">
                                                            @foreach($item->comments as $comment)
                                                                <li class="mb-2">
                                                                    <div class="d-flex">
                                                                        <div class="user-img">
                                                                            @php
                                                                            $profilePath = null;

                                                                            if ($comment->user && $comment->user->profilePicture && $comment->user->profilePicture->file_path) {
                                                                                $profilePath = Storage::url($comment->user->profilePicture->file_path);
                                                                            } elseif ($comment->business && $comment->business->profilePicture && $comment->business->profilePicture->file_path) {
                                                                                $profilePath = Storage::url($comment->business->profilePicture->file_path);
                                                                            }
                                                                        @endphp

                                                                        <img src="{{ $profilePath ?? asset('/images/template/user/default.jpg') }}"
                                                                             alt="userimg"
                                                                             class="avatar-40 rounded-circle img-fluid">
                                                                        </div>
                                                                        <div class="comment-data-block ms-3">
                                                                            <h6>{{ $comment->user?->name ?? $comment->business?->name ?? 'Unknown' }}</h6>
                                                                            <p class="mb-0">{{ $comment->comment }}</p>
                                                                            <div class="d-flex flex-wrap align-items-center comment-activity">

                                                                                <span> {{ $comment->created_at->diffForHumans() }} </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <form class="comment-text d-flex align-items-center mt-3" action="{{ route('businesscomment', $item->id) }}" method="POST">
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
                                {{-- @endforeach --}}
                                @endif
                                    @endif

            @endforeach
             <div class="col-sm-12 text-center">
            <img src="{{ asset('/images/template/page-img/page-load-loader.gif') }}" alt="loader" style="height: 100px;">
        </div>
                    </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Friend Request</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-story list-inline m-0 p-0">
                        @foreach($friendRequests as $request)
                        <li class="d-flex mb-3 align-items-center active">
                            @if($request->user->profilePicture && $request->user->profilePicture->file_path)
                                    <img src="{{ Storage::url($request->user->profilePicture->file_path) }}" alt="story-img" class="rounded-circle img-fluid">
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="story-img" class="rounded-circle img-fluid">
                                @endif
                            <div class="stories-data ms-3">
                                <h5>{{ $request->user->name }}</h5>
                                <p class="mb-0">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                    <a href="{{ route('friend.friendrequest') }}" class="btn btn-primary d-block mt-3">See All</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Friend Suggestions</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-story list-inline m-0 p-0">
                        @foreach($suggestedFriends as $suggestedFriend)
                            <li class="d-flex mb-4 align-items-center">
                                @if($suggestedFriend->profilePicture && $suggestedFriend->profilePicture->file_path)
                                    <img src="{{ Storage::url($suggestedFriend->profilePicture->file_path) }}" alt="story-img" class="rounded-circle img-fluid">
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="story-img" class="rounded-circle img-fluid">
                                @endif
                                <div class="stories-data ms-3">
                                    <h5>{{ $suggestedFriend->name }}</h5>
                                    <form action="{{ route('friends.add', ['id' => $suggestedFriend->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Add Friend</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            </div>
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

    document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
        if (event.target.classList.contains('like-button')) {
            event.preventDefault();

            const button = event.target;
            const adId = button.dataset.adId;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('Clicked Like button for ad:', adId);

            fetch(`/business/ads/${adId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ _token: token })
            })
            .then(response => {
                if (!response.ok) throw new Error('Request failed');
                return response.json();
            })
            .then(data => {
                console.log('Response from server:', data);

                const likeBlock = button.closest('.like-block');
                const likeCountSpan = likeBlock.querySelector('.like-count');
                const likeTextSpan = likeBlock.querySelector('.like-text');
                const dropdownMenu = likeBlock.querySelector('.ad-like-list');

                // Update count and label
                const newCount = data.likes_count;
                likeCountSpan.textContent = newCount;
                likeTextSpan.textContent = newCount === 1 ? 'Like' : 'Likes';

                // Update button text
                button.textContent = data.liked ? 'Unlike' : 'Like';

                // Update dropdown list — for now, just show "You" if liked
                dropdownMenu.innerHTML = ''; // Clear previous items
                if (data.liked) {
                    const newItem = document.createElement('a');
                    newItem.className = 'dropdown-item';
                    newItem.textContent = 'You';
                    dropdownMenu.appendChild(newItem);
                }
            })
            .catch(error => console.error('Like error:', error));
        }
    });
});


</script>
@endsection
