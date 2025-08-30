<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Blog Post - {{ $blog->title }}</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333 !important;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 40px;
            text-align: center;
        }
        
        .logo {
            color: #ffffff !important;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 14px;
            font-weight: 400;
        }
        
        /* Hero Image */
        .hero-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        
        /* Content */
        .content {
            padding: 40px;
        }
        
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: #ffffff !important;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .category-trending {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        }
        
        .category-guides {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
        }
        
        .category-insights {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }
        
        .blog-title {
            font-size: 26px;
            font-weight: 700;
            color: #2d3748 !important;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .blog-meta {
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .meta-items {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 8px 14px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            font-size: 13px;
            font-weight: 500;
            color: #4a5568 !important;
            min-width: fit-content;
        }
        
        .meta-icon {
            width: 16px;
            height: 16px;
            color: #667eea !important;
            flex-shrink: 0;
        }
        
        .meta-date {
            color: #2d3748 !important;
            font-weight: 600;
        }
        
        .meta-read-time {
            color: #38a169 !important;
        }
        
        .meta-author {
            color: #805ad5 !important;
        }
        
        .blog-excerpt {
            color: #4a5568 !important;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        /* Tags */
        .tags-container {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .tags-label {
            font-size: 14px;
            font-weight: 600;
            color: #718096 !important;
            margin-bottom: 10px;
        }
        
        .tag {
            display: inline-block;
            background-color: #edf2f7;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin-right: 8px;
            margin-bottom: 5px;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .footer-content {
            position: relative;
            z-index: 1;
        }
        
        .footer-logo {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .footer-text {
            color: #cbd5e0;
            font-size: 16px;
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        .social-links {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
            text-decoration: none;
            border-radius: 50%;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #4a5568, transparent);
            margin: 25px 0;
        }
        
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .footer-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        
        .footer-link {
            color: #a0aec0 !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #667eea;
        }

        
        .footer-copyright {
            color: #718096 !important;
            font-size: 12px;
            margin-top: 20px;
            line-height: 1.5;
        }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .blog-title {
                font-size: 22px;
            }
            
            .blog-meta {
                flex-direction: column;
                align-items: stretch;
                padding: 15px;
            }
            
            .meta-items {
                flex-direction: column;
                gap: 12px;
            }
            
            .meta-item {
                justify-content: center;
            }
            
            .footer {
                padding: 30px 20px;
            }
            
            .social-links {
                gap: 15px;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-links {
                justify-content: center;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1a202c;
            }
            
            .content {
                color: #e2e8f0;
            }
            
            .blog-title {
                color: #f7fafc;
            }
            
            .blog-excerpt {
                color: #cbd5e0;
            }
            
            .blog-meta {
                background: linear-gradient(135deg, #2d3748, #4a5568);
                border-color: #4a5568;
            }
            
            .meta-item {
                background: #1a202c;
                border-color: #4a5568;
                color: #cbd5e0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">PropX Pro</div>
            <div class="header-subtitle">Your trusted source for property insights</div>
        </div>
        
        <!-- Hero Image -->
        @if($blog->cover_photo)
        <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="hero-image">
        @endif
        
        <!-- Content -->
        <div class="content">
            <!-- Category Badge -->
            <div class="category-badge category-{{ $blog->category }}">
                {{ ucfirst($blog->category) }}
            </div>
            
            <!-- Blog Title -->
            <h1 class="blog-title">{{ $blog->title }}</h1>
            
            <!-- Meta Information -->
            <div class="blog-meta">
                <div class="meta-items">
                    <div class="meta-item">
                        <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="meta-date">{{ $blog->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="meta-item">
                        <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="meta-read-time">{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                    </div>
                    
                    @if($blog->author)
                    <div class="meta-item">
                        <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="meta-author">{{ $blog->author->name ?? 'PropX Team' }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Blog Excerpt -->
            <div class="blog-excerpt">
                {{ Str::limit(strip_tags($blog->content), 200, '...') }}
            </div>
            
            <!-- CTA Button -->
            <a href="{{ config('app.frontend_url', 'https://propx-pro.vercel.app') }}/blog/post/{{ $blog->slug }}" class="cta-button">
                Read Full Article
            </a>
            
            <!-- Tags -->
            @if($blog->tags && count($blog->tags) > 0)
            <div class="tags-container">
                <div class="tags-label">Tags:</div>
                @foreach($blog->tags as $tag)
                <span class="tag">#{{ $tag }}</span>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-logo">PropX Pro</div>
                <div class="footer-text">
                    Thanks for being part of our community! 🚀
                </div>
                
                <div class="footer-divider"></div>
                
                <div class="footer-copyright">
                    © {{ date('Y') }} PropX Pro. All rights reserved.<br>
                    Empowering smart property investment decisions worldwide.
                </div>
            </div>
        </div>
    </div>
</body>
</html>