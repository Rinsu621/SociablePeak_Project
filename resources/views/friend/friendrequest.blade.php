<!-- resources/views/friend/friendrequest.blade.php -->

@extends('layout')

@section('content')
<div class="col-sm-12">
    <div id="friend-request-modal-data" class="card card-block card-stretch card-height">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Friend Requests</h4>
            </div>
        </div>
        @if($friendRequests->isEmpty())
            <p style="margin: 20px">No friend requests.</p>
            <hr>
        @else
            @foreach($friendRequests as $request)
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.show', ['id' => $request->user_id]) }}" style="text-decoration: none; color: inherit;" class="d-flex align-items-center">
                            <div class="user-img" style="width: 60px; height: 55px; overflow: hidden; border-radius:50%;">
                                @if($request->user->profilePicture && $request->user->profilePicture->file_path)
                                    <img src="{{ Storage::url($request->user->profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                                @else
                                    <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                                @endif
                            </div>
                            <div class="ml-3" style="margin-left: 15px;">
                                <li style="font-size: 20px; list-style-type: none;">{{ $request->user->name }}</li>
                            </div>
                        </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('friend.accept', ['id' => $request->user_id]) }}" method="post" style="margin-right: 10px;">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>
                            <form action="{{ route('friend.reject', ['id' => $request->user_id]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
