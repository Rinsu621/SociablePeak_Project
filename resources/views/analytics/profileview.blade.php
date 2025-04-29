
<h1>
    Hi</h1>
    <h2>Profile of {{ $user->name }}</h2>

<!-- Displaying profile view count -->
<p>Total views: {{ $profileViews->count() }}</p>

<!-- Displaying who viewed the profile -->
<h3>Viewers:</h3>
<ul>
    @foreach($profileViews as $view)
        <li>{{ $view->viewer->name }} viewed your profile on {{ $view->viewed_at->format('Y-m-d H:i') }}</li>
    @endforeach
</ul>
