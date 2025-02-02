@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Group Chat: {{ $group->name }}</h1>
    <ul>
        @foreach($group->messages as $message)
            <li><strong>{{ $message->user->name }}:</strong> {{ $message->message }}</li>
        @endforeach
    </ul>
</div>
@endsection
