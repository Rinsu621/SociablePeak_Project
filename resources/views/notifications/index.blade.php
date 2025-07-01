@extends('layout')

@section('content')
<div class="container">
    <h4>All Notifications</h4>
    <ul class="list-group">
        @forelse($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $notification->message }}</span>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="list-group-item">No notifications found.</li>
        @endforelse
    </ul>

</div>
@endsection
