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
            color: #333;
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
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
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
            color: #ffffff;
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
            color: #2d3748;
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
            gap: 6px;
        }
        
        .meta-icon {
            width: 16px;
            height: 16px;
            opacity: 0.7;
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
            color: #ffffff;
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
            color: #718096;
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
            background-color: #f7fafc;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-text {
            color: #718096;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .social-links {
            margin-bottom: 20px;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .unsubscribe {
            color: #a0aec0;
            font-size: 12px;
            text-decoration: none;
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
                align-items: flex-start;
                gap: 10px;
            }
            
            .footer {
                padding: 25px 20px;
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
            
            .footer {
                background-color: #2d3748;
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
                <div class="meta-item">
                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="meta-item">
                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                </div>
                
                @if($blog->author)
                <div class="meta-item">
                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $blog->author->name ?? 'PropX Team' }}</span>
                </div>
                @endif
            </div>
            
            <!-- Blog Excerpt -->
            <div class="blog-excerpt">
                {{ Str::limit(strip_tags($blog->content), 200, '...') }}
            </div>
            
            <!-- CTA Button -->
            <a href="{{ config('app.frontend_url', 'https://propx-pro.vercel.app') }}/blog/post/{{ $blog->id }}" class="cta-button">
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
            <div class="footer-text">
                Thanks for being part of the PropX Pro community!
            </div>
            
            <div class="social-links">
                <a href="#" class="social-link">Website</a>
                <a href="#" class="social-link">LinkedIn</a>
                <a href="#" class="social-link">Twitter</a>
            </div>
            
            <div style="margin-top: 15px;">
                <a href="#" class="unsubscribe">Unsubscribe from these emails</a>
            </div>
            
            <div style="margin-top: 10px; color: #a0aec0; font-size: 11px;">
                © {{ date('Y') }} PropX Pro. All rights reserved.<br>
                This email was sent because you subscribed to our blog updates.
            </div>
        </div>
    </div>
</body>
</html>