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
                            <img src="{{ asset('/images/template/page-img/kbanner.png') }}" alt="profile-bg"
                                class="rounded img-fluid">

                        </div>
                        <div class="user-detail text-center mb-3">
                            <div class="profile-img position-relative">
                                @if($business->profilePicture && $business->profilePicture->file_path)
                                    <img src="{{ Storage::url($business->profilePicture->file_path) }}" alt="profile-img" class="avatar-130 img-fluid rounded-circle"/>
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-130 img-fluid rounded-circle"  />
                                @endif
                            </div>
                            <!--Profile Picture Modal-->

                            <div class="profile-detail">
                                <h3 class="">{{ $business->name }}</h3>
                            </div>
                        </div>
                        <div class="profile-info p-3 d-flex align-items-center justify-content-between position-relative">
                            <div class="social-info">
                                <ul
                                    class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                                    <li class="text-center ps-3">
                                        <h6>Followers</h6>
                                        <p class="mb-0">{{$totalFollowers}}</p>
                                    </li>
                                    <li class="text-center ps-3">
                                        <h6>Ads</h6>
                                        <p class="mb-0">{{ $userAdsCount}}</p>
                                    </li>
                                    <li class="text-center ps-3">
                                        <div class="follow-button">
                                            @if ($isFollowing)
                                            <!-- If already following, show 'Following' and a dropdown to unfollow -->
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="followButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Following
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="followButton">
                                                <li>
                                                    <form action="{{ route('business.follow', $business->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">Unfollow</button>
                                                    </form>
                                                </li>
                                            </ul>
                                            @else
                                            <!-- If not following, show 'Follow' button -->
                                            <form action="{{ route('business.follow', $business->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Follow</button>
                                            </form>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div>

                            {{-- @if ($receivedFriendRequest)
                            <form action="{{ route('friend.accept', ['id' => $receivedFriendRequest->user_id]) }}" method="post" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>
                            <form action="{{ route('friend.reject', ['id' => $receivedFriendRequest->user_id]) }}" method="post" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                            @elseif ($sentFriendRequest)
                            @if ($sentFriendRequest->status === 'pending')
                                <button class="btn btn-warning">Request Sent</button>
                            @elseif ($sentFriendRequest->status === 'accepted')
                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton01" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                <i class="ri-check-line me-1 text-white"></i> Friend
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton01">
                                <form action="{{ route('friend.unfriend', ['id' => $user->id]) }}" method="post" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Unfriend</button>
                                </form>
                            </div>
                        @endif --}}


                        {{-- @else --}}
                        {{-- <form action="{{ route('friends.add', ['id' => $user->id]) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success">Add Friend</button>
                        </form>
                    @endif --}}

                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                            Report Business
                        </button>
                        <!-- Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Business</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.report', ['id' => $user->id]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="reason">Report Reason:</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger mt-2">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="user-tabing">
                        <ul
                            class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                            <li class="nav-item col-12 col-sm-3 p-0">
                                <a class="nav-link active" href="#pills-timeline-tab" data-bs-toggle="pill"
                                    data-bs-target="#timeline" role="button">Timeline</a>
                            </li>
                            <li class="nav-item col-12 col-sm-3 p-0">
                                <a class="nav-link" href="#pills-about-tab" data-bs-toggle="pill" data-bs-target="#about"
                                    role="button">About</a>
                            </li>
                            <li class="nav-item col-12 col-sm-3 p-0">
                                <a class="nav-link" href="#pills-friends-tab" data-bs-toggle="pill"
                                    data-bs-target="#friends" role="button">Friends</a>
                            </li>
                            <li class="nav-item col-12 col-sm-3 p-0">
                                <a class="nav-link" href="#pills-photos-tab" data-bs-toggle="pill" data-bs-target="#photos"
                                    role="button">Ads</a>
                            </li>
                        </ul>
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
                                            <h4 class="card-title">Ads</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                            @foreach ($ads as $post)
                                                @if ($post->adimages->isNotEmpty())
                                                    @foreach ($post->adimages as $image)
                                                        <li class=""><a href="#"><img
                                                                    src="{{ asset($image->file_path) }} "
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
                                            <h4 class="card-title">Followers</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                            @if ($followers->isEmpty())
                                                <p>No followers.</p>
                                            @else
                                                <ul style="list-style-type: none;">
                                                    {{-- Loop through the followers --}}
                                                    @foreach ($followers as $follower)
                                                        <li>
                                                            <a href="#">
                                                                <input type="radio" name="follower" id="follower-{{ $follower->id }}" style="display: none;">
                                                                <img class="timeline-friends-profile-img"
                                                                src="{{ $follower->user && $follower->user->profilePicture ? Storage::url($follower->user->profilePicture->file_path) : asset('/images/template/user/noprofile.jpg') }}"
                                                                alt="gallery-image" class="img-fluid rounded-circle" />

                                                            </a>
                                                            <h6 class="mt-2 text-center">{{ $follower->name }}</h6>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8 row m-0 p-0">


                                @if ($ads->isEmpty())
                                    <div class="card">
                                        <div class="card-body">
                                            <p>No Ads.</p>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($ads as $key => $item)
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="post-item">
                                                    <div class="user-post-data py-3">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="me-3 user-img">
                                                                @if($business->profilePicture && $business->profilePicture->file_path)
                                    <img src="{{ Storage::url($business->profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                @endif
                                                            </div>


                                                            <div class="w-100">
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="">
                                                                        <h5 class="mb-0 d-inline-block"><a href="#"
                                                                                class="">{{ $business->name }}</a>
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
                                                    @if ($item->adimages && $item->adimages->isNotEmpty()) <!-- Check if adimages is not null and not empty -->
                                                        <div class="post-images">
                                                            @foreach ($item->adimages as $image)
                                                                <img src="{{ asset($image->file_path) }}" alt="Post Image" class="img-fluid rounded">
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="comment-area mt-3">
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <div class="like-block position-relative d-flex align-items-center">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="like-data">
                                                                        <button class="btn btn-link p-0 like-button" data-post-id="{{ $post->id }}">
                                                                            @if($item->adLikes->where('user_id', auth()->id())->count()|| ($item->adLikes->where('business_id', Auth::guard('business')->id())->count()))
                                                                                Unlike
                                                                            @else
                                                                                Like
                                                                            @endif
                                                                        </button>
                                                                    </div>
                                                                    <div class="total-like-block ms-2 me-3">
                                                                        <div class="dropdown">
                                                                            <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                                                <span class="like-count">{{ $item->adLikes->count() }}</span>
                                                                                <span class="like-text">{{ $item->adLikes->count() === 1 ? 'Like' : 'Likes' }}</span>
                                                                            </span>
                                                                            <div class="dropdown-menu">
                                                                                @foreach($item->adLikes as $like)
                                                                                    <a class="dropdown-item" href="#">  {{ $like->user ? $like->user->name : ($like->business ? $like->business->name : 'Unknown') }}</a>
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
                                                                                <a class="dropdown-item" href="#">{{ $comment->user?->name ?? $comment->business?->name ?? 'Unknown' }}: {{ $comment->comment }}</a>
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
                                @endempty

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="about" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="nav nav-pills basic-info-items list-inline d-block p-0 m-0">
                                    <li>
                                        <a class="nav-link active" href="#v-pills-basicinfo-tab"
                                            data-bs-toggle="pill" data-bs-target="#v-pills-basicinfo-tab"
                                            role="button">Contact and Basic Info</a>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-md-9 ps-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="v-pills-basicinfo-tab" role="tabpanel"
                                        aria-labelledby="v-pills-basicinfo-tab">
                                        <h4>Contact Information</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-3">
                                                <h6>Email</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">rinsu.pradhan@deerwalk.edu.np</p>
                                            </div>
                                            <div class="col-3">
                                                <h6>Mobile</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">9848044640</p>
                                            </div>
                                            <div class="col-3">
                                                <h6>Address</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">Koteshwor</p>
                                            </div>
                                        </div>

                                        <h4 class="mt-3">Basic Information</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-3">
                                                <h6>Birth Date</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">2 November</p>
                                            </div>
                                            <div class="col-3">
                                                <h6>Birth Year</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">2003</p>
                                            </div>
                                            <div class="col-3">
                                                <h6>Gender</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">Female</p>
                                            </div>

                                            <div class="col-3">
                                                <h6>language</h6>
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">English, Nepali</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-details-tab" role="tabpanel"
                                        aria-labelledby="v-pills-details-tab">
                                        <h4 class="mb-3">About You</h4>
                                        <p>Hi, I’m Rinsu Pradhan, I’m 20 and I study at Deerwalk Institute of
                                            Technology.</p>
                                        {{-- <h4 class="mt-3 mb-3">Other Name</h4>
                                    <p></p> --}}
                                        <h4 class="mt-3 mb-3">Favorite Quotes</h4>
                                        <p>Whats come is better than Whats gone
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="friends" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Friends</h2>
                            </div>

                        </div>

                        <div class="friend-list-tab mt-2">
                            <ul
                                class="nav nav-pills d-flex align-items-center justify-content-left friend-list-items p-0 mb-2">
                                <li>
                                    <a class="nav-link active" data-bs-toggle="pill" href="#pill-all-friends"
                                        data-bs-target="#all-feinds">All Friends</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="all-friends" role="tabpanel">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            @if (empty($friends))
                                                <p>You have no friends.</p>
                                            @else
                                                @foreach ($friends as $key => $item)
                                                    <div class="col-md-6 col-lg-6 mb-3">
                                                        <div class="iq-friendlist-block">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#">
                                                                        <img class="timeline-friends-profile-img"
                                                                        src="{{ $item['profile_picture'] ? Storage::url($item['profile_picture']) : asset('/images/template/user/1.jpg') }}"
                                                                        alt="gallery-image" class="img-fluid rounded-circle" />
                                                                    </a>
                                                                    <div class="friend-info ms-3">
                                                                        <h5>{{ $item['user']['name'] }}</h5>
                                                                        <p class="mb-0">Friends since
                                                                            {{ \Carbon\Carbon::parse($item['friend']['created_at'])->format('D, d M, Y') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="card-header-toolbar d-flex align-items-center">
                                                                    {{-- <button data-friendId="{{ $item['user']['id'] }}" type="button" class="btn btn-primary mt-1 me-2" data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl">
                                                                        <i class="ri-mail-line"></i>
                                                                    </button> --}}
                                                                    <div class="dropdown" style=" margin-top:3px; margin-right:10px " >
                                                                        <span class="dropdown-toggle btn btn-secondary" id="dropdownMenuButton01" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                                            <i class="ri-check-line me-1 text-white"></i>
                                                                            Friend
                                                                        </span>
                                                                        {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton01">
                                                                            <form action="{{ route('friend.unfriend', ['id' => $item['user']['id']]) }}" method="post" style="display:inline;">
                                                                                @csrf
                                                                                <button type="submit" class="dropdown-item">Unfriend</button>
                                                                            </form>
                                                                        </div> --}}
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endempty

                                            <div class="modal fade bd-example-modal-xl" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Send Message</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close">

                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <textarea name="message" id="messageBox" cols="30" rows="10"></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button"
                                                                class="btn btn-primary sendMessage">Send</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="recently-add" role="tabpanel">
                                <div class="card-body p-0">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="closefriends" role="tabpanel">
                                <div class="card-body p-0">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="home-town" role="tabpanel">
                                <div class="card-body p-0">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="following" role="tabpanel">
                                <div class="card-body p-0">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
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
                            {{-- <li>
                            <a class="nav-link active" data-bs-toggle="pill" href="#pill-photosofyou"
                                data-bs-target="#photosofyou">Photos of You</a>
                        </li> --}}
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
                                                {{-- <a href="#">
                                                <img src="{{ asset('/images/template/page-img/51.jpg') }}"
                                                    class="img-fluid rounded" alt="Responsive image">
                                             </a> --}}
                                                <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                                    {{-- @foreach ($ads as $post)
                                                        @if ($post->images->isNotEmpty())
                                                            @foreach ($post->images as $image)
                                                                <li class=""><a href="#"><img
                                                                            src="{{ Storage::url($image->file_path) }} "
                                                                            alt="gallary-image"
                                                                            class="img-fluid" /></a></li>
                                                                {{-- <div class="image-hover-data">
                                                                <div class="product-elements-icon">
                                                                    <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                                        <li><a href="#" class="pe-3 text-white"> 60 <i
                                                                                    class="ri-thumb-up-line"></i> </a></li>
                                                                        <li><a href="#" class="pe-3 text-white"> 30 <i
                                                                                    class="ri-chat-3-line"></i> </a></li>
                                                                        <li><a href="#" class="pe-3 text-white"> 10 <i
                                                                                    class="ri-share-forward-line"></i> </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div> --}}
                                                            {{-- @endforeach
                                                        @endif
                                                    @endforeach --}}
                                                </ul>

                                                {{-- <a href="#" class="image-edit-btn"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="" data-bs-original-title="Edit or Remove"><i
                                                    class="ri-edit-2-fill"></i></a> --}}
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
