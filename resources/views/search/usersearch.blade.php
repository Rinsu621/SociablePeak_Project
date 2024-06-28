<!-- resources/views/search/usersearch.blade.php -->

@extends('layout')

@section('content')
<div class="col-sm-12">
    <div id="post-modal-data" class="card card-block card-stretch card-height">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Results for "{{ $query }}"</h4>
            </div>
        </div>
        @if($users->isEmpty())
            <p>No users found.</p>
            <hr>
        @else
            @foreach($users as $user)
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="user-img" style="width: 60px; height: 55px; overflow: hidden; border-radius:50%;">
                                @if($user->profilePicture && $user->profilePicture->file_path)
                                    <img src="{{ Storage::url($user->profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                @else
                                    <img src="{{ asset('/images/template/user/11.png') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                @endif
                            </div>
                            <div class="ml-3" style="margin-left: 15px;">
                                <li style="font-size: 20px; list-style-type: none;">{{ $user->name }}</li>
                            </div>
                        </div>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}" class="btn btn-primary">View Profile</a>
                    </div>
                    <hr>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
