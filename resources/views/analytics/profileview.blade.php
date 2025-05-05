@extends('layout') <!-- Assuming you are using a base layout -->

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Title and Total Views -->
        <div class="col-12 mb-4">
            <hr>
            <h2 class="text-start">Profile Viewers of <strong>{{ $viewedUser->name }}</strong></h2>
            <hr>
        </div>

        <!-- Display the profile view count with highlight -->
        <div class="col-12">
            <p class="lead text-start">
                <strong style="font-size: 20px">Total Views:</strong>
                <span class="badge text-dark px-2 py-2" style="font-size: 15px;">{{ $viewsCount }}</span>
            </p>
        </div>

        <!-- List of users who have viewed the profile -->
        <div class="col-12">
            @if($viewers->isEmpty())
                <p class="text-start text-muted">No Profile Viewers yet</p>
            @else
                <h4 class="mb-4 text-start">People who have viewed this profile:</h4>
                <ul class="list-unstyled">
                    @foreach($viewers as $viewer)
                        <li class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded shadow-sm bg-light">
                            <!-- Avatar -->
                            <div class="d-flex align-items-center">
                                <img src="{{ $viewer->profilePicture ? Storage::url($viewer->profilePicture->file_path) : asset('/images/template/user/noprofile.jpg') }}"
                                     alt="{{ $viewer->name }}" class="avatar-50 img-fluid rounded-circle me-3">
                                <!-- Name and "You" text if it's the current user -->
                                <div>
                                    <h5 class="mb-0">{{ $viewer->name }}</h5>
                                    @if ($viewer->id == $user->id)
                                        <small class="text-muted">(You)</small>
                                    @endif
                                </div>
                            </div>
                            <!-- View Profile button aligned to the right -->
                            <a href="{{ route('user.show', ['id' => $viewer->id]) }}" class="btn btn-primary">View Profile</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Optionally, display more information about the viewed user -->

    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-50 {
        width: 50px;
        height: 50px;
    }

    .avatar-40 {
        width: 40px;
        height: 40px;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .shadow-sm {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .list-unstyled {
        padding-left: 0;
    }

    .badge {
        font-size: 1.25rem;
        font-weight: 500;
    }

    /* Highlight effect for total views */
    .badge.bg-info {
        background-color: #17a2b8;
        color: white;
    }

    /* Hover effect on profile viewer list items */
    li:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
</style>
@endpush
