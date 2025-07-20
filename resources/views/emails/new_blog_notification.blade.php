<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $blog->title }} - PropX Pro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1.5" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .logo {
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -1px;
            position: relative;
            z-index: 2;
            margin-bottom: 10px;
        }
        
        .tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            position: relative;
            z-index: 2;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .blog-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        
        .blog-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .blog-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            color: #718096;
            font-size: 14px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .blog-cover {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        
        .blog-excerpt {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .tags-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .tags-label {
            font-size: 14px;
            color: #718096;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .tag {
            background: #f7fafc;
            color: #4a5568;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }
        
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .social-links {
            margin-bottom: 20px;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 10px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        
        .social-link:hover {
            transform: scale(1.1);
        }
        
        .unsubscribe {
            color: #718096;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .unsubscribe a {
            color: #667eea;
            text-decoration: none;
        }
        
        .company-info {
            color: #a0aec0;
            font-size: 11px;
        }
        
        @media (max-width: 480px) {
            .email-container {
                margin: 0 10px;
            }
            
            .header,
            .content,
            .footer {
                padding: 20px;
            }
            
            .blog-title {
                font-size: 24px;
            }
            
            .blog-meta {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .cta-button {
                display: block;
                text-align: center;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">PropX Pro</div>
            <div class="tagline">Your Premier Property Platform</div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="blog-badge">
                {{ ucfirst($blog->category) }} • New Blog Post
            </div>
            
            <h1 class="blog-title">{{ $blog->title }}</h1>
            
            <div class="blog-meta">
                <div class="meta-item">
                    <span>📝</span>
                    <span>By {{ $blog->author->name ?? 'PropX Team' }}</span>
                </div>
                <div class="meta-item">
                    <span>📅</span>
                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                </div>
                <div class="meta-item">
                    <span>⏱️</span>
                    <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                </div>
            </div>
            
            @if($blog->cover_photo)
            <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="blog-cover">
            @endif
            
            <div class="blog-excerpt">
                {{ Str::limit(strip_tags($blog->content), 250, '...') }}
            </div>
            
            <div style="text-align: center;">
                <a href="https://propx-pro.vercel.app/blog/post/{{ $blog->id }}" class="cta-button">
                    Read Full Article →
                </a>
            </div>
            
            @if($blog->tags && count($blog->tags) > 0)
            <div class="tags-section">
                <div class="tags-label">Topics:</div>
                <div class="tags">
                    @foreach($blog->tags as $tag)
                    <span class="tag">#{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="#" class="social-link">f</a>
                <a href="#" class="social-link">t</a>
                <a href="#" class="social-link">in</a>
                <a href="#" class="social-link">ig</a>
            </div>
            
            <div class="unsubscribe">
                Don't want to receive these emails? 
                <a href="#">Unsubscribe here</a>
            </div>
            
            <div class="company-info">
                © 2025 PropX Pro. All rights reserved.<br>
                Your trusted partner in property solutions.
            </div>
        </div>
    </div>
</body>
</html>