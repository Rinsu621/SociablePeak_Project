@extends('layout')

@section('style')
    <style>
        .friends-tab-profile-img {
            min-height: 140px;
            max-height: 140px;
            min-width: 140px;
            max-width: 140px;
            object-fit: contain;
        }

        .timeline-friends-profile-img {
            min-height: 90px;
            max-height: 90px;
            min-width: 90px;
            max-width: 90px;
            object-fit: contain;
        }


        .profile-img {
            position: relative;
            display: inline-block;
        }

        .profile-img img {
            width: 130px;
            /* Ensure the image is square */
            height: 130px;
            /* Ensure the image is square */
            border-radius: 50%;
            /* Make the image circular */
            object-fit: cover;
            /* Ensure the image covers the entire area */
        }

        .camera-icon-wrapper {
            position: absolute;
            bottom: 0px;
            /* Adjust as needed */
            right: 0px;
            /* Adjust as needed */
            list-style: none;
            margin: 0;
            padding: 0;
            width: 40px;
            /* Adjust the size as needed */
            height: 40px;
            /* Adjust the size as needed */
            background-color: white;
            /* Background color */
            border-radius: 50%;
            /* Make it circular */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            /* Optional: add a shadow for better visibility */
        }

        .camera-icon {
            font-size: 20px;
            /* Adjust the size as needed */

            cursor: pointer;
        }

        .avatar-130 {
            width: 130px;
            height: 130px;
        }

        .avatar-90 {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-60 {
            width: 60px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-60 {
            width: 40px;
            height: 40px;
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
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body profile-page p-0">
                    <div class="profile-header">
                        <div class="position-relative">
                            <img src="{{ asset('/images/template/page-img/profile-bg1.jpg') }}" alt="profile-bg"
                                class="rounded img-fluid">
                        </div>
                        <div class="user-detail text-center mb-3">
                            <div class="profile-img position-relative">
                                @if($profilePicture && $user->profilePicture->file_path)
                                    <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-130 img-fluid rounded-circle"/>
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-130 img-fluid rounded-circle"  />
                                @endif
                            </div>
                            <!--Profile Picture Modal-->

                            <div class="profile-detail">
                                <h3 class="">{{ $user->name }}</h3>
                            </div>
                        </div>
                        <div class="profile-info p-3 d-flex align-items-center justify-content-between position-relative">
                            <div class="social-info">
                                <ul
                                    class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                                    <li class="text-center ps-3">
                                        <h6>Posts</h6>
                                        <p class="mb-0">{{ $postCount }}</p>
                                    </li>
                                    <li class="text-center ps-3">
                                        <h6>Friends</h6>
                                        <p class="mb-0">{{ $friendsCount }}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <form action="{{ route('admin.reports.user.delete', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                </form>
                            </div>
                         <div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="col-sm-12">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="timeline" role="tabpanel">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="header-title">
                                            <h4 class="card-title">Photos</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                            @foreach ($posts as $post)
                                                @if ($post->images->isNotEmpty())
                                                    @foreach ($post->images as $image)
                                                        <li class=""><a href="#"><img
                                                                    src="{{ Storage::url($image->file_path) }} "
                                                                    alt="gallary-image" class="img-fluid" /></a></li>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="header-title">
                                            <h4 class="card-title">Friends</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                            @if (empty($friends))
                                                <p>No friends.</p>
                                            @else
                                                @foreach ($friends as $item)
                                                    <li class="">
                                                        <a href="#">
                                                            <img class="timeline-friends-profile-img"
                                                            src="{{ $item['profile_picture'] ? Storage::url($item['profile_picture']) : asset('/images/template/user/1.jpg') }}"
                                                            alt="gallery-image" class="img-fluid rounded-circle" />
                                                        </a>

                                                        <h6 class="mt-2 text-center">{{ $item['user']['name'] }}</h6>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8 row m-0 p-0">


                                @if ($posts->isEmpty())
                                    <div class="card">
                                        <div class="card-body">
                                            <p>No Posts.</p>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($posts as $key => $item)
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="post-item">
                                                    <div class="user-post-data py-3">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="me-3 user-img">
                                                                @if($profilePicture && $profilePicture->file_path)
                                    <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                @endif
                                                            </div>


                                                            <div class="w-100">
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="">
                                                                        <h5 class="mb-0 d-inline-block"><a href="#"
                                                                                class="">{{ $user->name }}</a>
                                                                        </h5>
                                                                        <p class="ms-1 mb-0 d-inline-block">

                                                                            Update Status

                                                                        </p>
                                                                        <p class="mb-0">
                                                                            {{ convertToTimeAgo($item->created_at) }}</p>
                                                                    </div>
                                                                    <div class="card-post-toolbar">
                                                                        <div class="dropdown">
                                                                            <span class="dropdown-toggle"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-haspopup="true" aria-expanded="false"
                                                                                role="button">
                                                                                <i class="ri-more-fill"></i>
                                                                            </span>
                                                                            <div class="dropdown-menu m-0 p-0">
                                                                                <a class="dropdown-item p-3"
                                                                                    href="#">
                                                                                    <div class="d-flex align-items-top">
                                                                                        <i class="ri-save-line h4"></i>
                                                                                        <div class="data ms-2">
                                                                                            <h6>Save Post</h6>
                                                                                            <p class="mb-0">Add this to
                                                                                                your saved items</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a class="dropdown-item p-3"
                                                                                    href="#">
                                                                                    <div class="d-flex align-items-top">
                                                                                        <i class="ri-pencil-line h4"></i>
                                                                                        <div class="data ms-2">
                                                                                            <h6>Edit Post</h6>
                                                                                            <p class="mb-0">Update your
                                                                                                post and saved items</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a class="dropdown-item p-3"
                                                                                    href="#">
                                                                                    <div class="d-flex align-items-top">
                                                                                        <i
                                                                                            class="ri-close-circle-line h4"></i>
                                                                                        <div class="data ms-2">
                                                                                            <h6>Hide From Timeline</h6>
                                                                                            <p class="mb-0">See fewer
                                                                                                posts like this.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a class="dropdown-item p-3"
                                                                                    href="#">
                                                                                    <div class="d-flex align-items-top">
                                                                                        <i
                                                                                            class="ri-delete-bin-7-line h4"></i>
                                                                                        <div class="data ms-2">
                                                                                            <h6>Delete</h6>
                                                                                            <p class="mb-0">Remove thids
                                                                                                Post on Timeline</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a class="dropdown-item p-3"
                                                                                    href="#">
                                                                                    <div class="d-flex align-items-top">
                                                                                        <i
                                                                                            class="ri-notification-line h4"></i>
                                                                                        <div class="data ms-2">
                                                                                            <h6>Notifications</h6>
                                                                                            <p class="mb-0">Turn on
                                                                                                notifications for this post
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </div>
                                                                        </div>
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
                                                    @if ($item->images->isNotEmpty())
                                                        <div class="post-images">
                                                            @foreach ($item->images as $image)
                                                                <img src="{{ Storage::url($image->file_path) }}"
                                                                    alt="Post Image" class="img-fluid rounded">
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    {{-- <div class="comment-area mt-3">
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
                                                        <hr> --}}
                                                        {{-- <ul class="post-comments list-inline p-0 m-0">
                                                            @foreach($post->comments as $comment)
                                                                <li class="mb-2">
                                                                    <div class="d-flex">
                                                                        <div class="user-img">
                                                                            @if($comment->user->profilePicture && $comment->user->profilePicture->file_path)
                                                                                <img src="{{ Storage::url($comment->user->profilePicture->file_path) }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
                                                                            @else
                                                                                <img src="{{ asset('/images/template/user/default.jpg') }}" alt="userimg" class="avatar-40 rounded-circle img-fluid">
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
                                                        </ul> --}}
                                                        {{-- <form class="comment-text d-flex align-items-center mt-3" action="{{ route('post.comment', $post->id) }}" method="POST">
                                                            @csrf
                                                            <input type="text" name="comment" class="form-control rounded" placeholder="Enter Your Comment" required>
                                                            <div class="comment-attagement d-flex">
                                                                <a href="javascript:void(0);"><i class="ri-link me-3"></i></a>
                                                                <a href="javascript:void(0);"><i class="ri-user-smile-line me-3"></i></a>
                                                                <a href="javascript:void(0);"><i class="ri-camera-line me-3"></i></a>
                                                            </div>
                                                        </form> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endempty

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="photos" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h2>Photos</h2>
                    <div class="friend-list-tab mt-2">
                        <ul
                            class="nav nav-pills d-flex align-items-center justify-content-left friend-list-items p-0 mb-2">

                            <li>
                                <a class="nav-link" data-bs-toggle="pill" href="#pill-your-photos"
                                    data-bs-target="#your-photos">Your Photos</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="photosofyou" role="tabpanel">
                                <div class="card-body p-0">
                                    <div class="d-grid gap-2 d-grid-template-1fr-13">
                                        <div class="">
                                            <div class="user-images position-relative overflow-hidden">

                                                <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                                    @foreach ($posts as $post)
                                                        @if ($post->images->isNotEmpty())
                                                            @foreach ($post->images as $image)
                                                                <li class=""><a href="#"><img
                                                                            src="{{ Storage::url($image->file_path) }} "
                                                                            alt="gallary-image"
                                                                            class="img-fluid" /></a></li>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </ul>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-sm-12 text-center">
<img src="{{ asset('/images/template/page-img/page-load-loader.gif') }}" alt="loader"
    style="height: 100px;">
</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // When the button with data-bs-toggle="modal" is clicked
        $('button[data-bs-toggle="modal"]').on('click', function() {
            // Get the friendId from the button
            var friendId = $(this).data('friendid');
            // Set the friendId in the sendMessage button
            $('.sendMessage').data('friendid', friendId);
        });

        // When the sendMessage button is clicked
        $('.sendMessage').on('click', function() {
            // Get the friendId from the sendMessage button
            var friendId = $(this).data('friendid');
            // Get the message from the textarea
            var message = $('#messageBox').val();

            // Check if the message is empty
            if (message.trim() === '') {
                toastr.error('It is bad to send an empty message to your friend :)');
                return;
            }

            // Perform the AJAX request
            $.ajax({
                url: '{{ route('chat.sendMessage') }}',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    friend_id: friendId,
                    message: message
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#messageBox').val(''); // Clear the textarea
                    $('.bd-example-modal-xl').modal('hide'); // Close the modal
                },
                error: function(response) {
                    toastr.error('Error: ' + response.message);
                }
            });
        });
    });

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
