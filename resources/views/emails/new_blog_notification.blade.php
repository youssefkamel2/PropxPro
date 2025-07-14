<html>
<body>
    <h2>{{ $blog->title }}</h2>
    <img src="{{ $blog->cover_photo }}" alt="Cover Photo" style="max-width:100%;height:auto;">
    <div>
        {!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 300) !!}
    </div>
    <p>
        <a href="{{ url('/') }}" target="_blank">Read more on our website</a>
    </p>
</body>
</html> 