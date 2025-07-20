<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $blog->title }}</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333333;
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
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 40px;
            text-align: center;
        }
        
        .logo {
            color: #ffffff;
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
            letter-spacing: 1px;
        }
        
        .tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 8px;
        }
        
        .content-wrapper {
            padding: 0;
        }
        
        .hero-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }
        
        .content {
            padding: 40px;
        }
        
        .category-badge {
            display: inline-block;
            background: #667eea;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        
        .blog-title {
            font-size: 28px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .blog-excerpt {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .meta-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
            color: #718096;
        }
        
        .author {
            font-weight: 600;
            color: #2d3748;
        }
        
        .date {
            margin-left: 15px;
            padding-left: 15px;
            border-left: 2px solid #e2e8f0;
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
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
            transition: transform 0.2s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .tags-section {
            margin-top: 30px;
        }
        
        .tags-title {
            font-size: 14px;
            color: #718096;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .tag {
            display: inline-block;
            background: #edf2f7;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 8px;
            margin-bottom: 6px;
        }
        
        .footer {
            background: #f7fafc;
            padding: 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-content {
            margin-bottom: 20px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 10px;
            padding: 10px;
            background: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            line-height: 20px;
            font-size: 16px;
        }
        
        .unsubscribe {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 20px;
        }
        
        .unsubscribe a {
            color: #667eea;
            text-decoration: none;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
            }
            
            .header {
                padding: 20px !important;
            }
            
            .content {
                padding: 30px 20px !important;
            }
            
            .blog-title {
                font-size: 24px !important;
            }
            
            .hero-image {
                height: 200px !important;
            }
            
            .meta-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .date {
                margin-left: 0 !important;
                padding-left: 0 !important;
                border-left: none !important;
                margin-top: 5px;
            }
            
            .footer {
                padding: 30px 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <a href="{{ url('/') }}" class="logo">Your Blog Name</a>
            <div class="tagline">Insights • Guides • Trending</div>
        </div>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Hero Image -->
            @if($blog->cover_photo)
            <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="hero-image">
            @endif
            
            <!-- Main Content -->
            <div class="content">
                <!-- Category Badge -->
                <span class="category-badge">{{ ucfirst($blog->category) }}</span>
                
                <!-- Blog Title -->
                <h1 class="blog-title">{{ $blog->title }}</h1>
                
                <!-- Meta Information -->
                <div class="meta-info">
                    <span class="author">By {{ $blog->author->name ?? 'Admin' }}</span>
                    <span class="date">{{ $blog->created_at->format('M d, Y') }}</span>
                </div>
                
                <!-- Blog Excerpt -->
                <div class="blog-excerpt">
                    {!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 250) !!}...
                </div>
                
                <!-- Divider -->
                <div class="divider"></div>
                
                <!-- CTA Section -->
                <div class="cta-section">
                    <a href="{{ url('/blog/' . $blog->id) }}" class="cta-button" target="_blank">
                        Read Full Article →
                    </a>
                </div>
                
                <!-- Tags Section -->
                @if($blog->tags && count($blog->tags) > 0)
                <div class="tags-section">
                    <div class="tags-title">Tags:</div>
                    @foreach($blog->tags as $tag)
                    <span class="tag"># {{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <h3 style="color: #2d3748; margin-bottom: 10px;">Stay Connected</h3>
                <p style="color: #718096; font-size: 14px;">Thank you for being part of our community!</p>
            </div>
            
            <!-- Social Links -->
            <div class="social-links">
                <a href="#" class="social-link">📧</a>
                <a href="#" class="social-link">🐦</a>
                <a href="#" class="social-link">📘</a>
                <a href="#" class="social-link">💼</a>
            </div>
            
            <!-- Unsubscribe -->
            <div class="unsubscribe">
                <p>
                    You received this email because you subscribed to our newsletter.<br>
                    <a href="{{ url('/unsubscribe') }}">Unsubscribe</a> | 
                    <a href="{{ url('/') }}">Visit Website</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>