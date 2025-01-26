@extends('layout')

@section('content')
<div class="container">
    <h2>Most Interacted Users</h2>
    <ul class="list-group">
        @foreach ($mostInteractedUsers as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <img src="{{ $user->profilePicture ? Storage::url($user->profilePicture->file_path) : asset('images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-40 img-fluid rounded-circle me-3">
                    {{ $user->name }}
                </div>
                <span class="badge bg-primary rounded-pill">
                    {{ $user->total_interactions }} Interactions
                </span>
            </li>
        @endforeach
    </ul>
</div>
@endsection
