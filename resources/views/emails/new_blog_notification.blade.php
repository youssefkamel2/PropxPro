<div class="email-container">
        <!-- Hero Section with Glass Morphism -->
        <div class="hero-section">
            @if($blog->cover_photo)
            <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="hero-image">
            @endif

            <div class="hero-overlay">
                <!-- Floating Logo -->
                <div class="floating-logo floating-elements">
                    <div class="logo">PropX Pro</div>
                </div>

                <!-- Content -->
                <div class="category-pill">{{ ucfirst($blog->category) }} • New Blog Post</div>
                <h1 class="hero-title">{{ $blog->title }}</h1>
                <p class="hero-description">
                    {{ Str::limit(strip_tags($blog->content), 160, '...') }}
                </p>
                <a href="https://propx-pro.vercel.app/blog/post/{{ $blog->id }}" class="hero-button" target="_blank">
                    Read Full Article ✨
                </a>
            </div>
        </div>

        <!-- Glass Content Section -->
        <div class="glass-content">
            <!-- Author Section -->
            <div class="author-section">
                <div class="author-avatar">
                    {{ substr($blog->author->name ?? 'P', 0, 1) }}
                </div>
                <div class="author-info">
                    <div class="author-name">{{ $blog->author->name ?? 'PropX Team' }}</div>
                    <div class="publish-date">{{ $blog->created_at->format('M d, Y') }}</div>
                </div>
                <div class="reading-stats">
                    <div class="stat-item">
                        <span>⏱️</span>
                        <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min</span>
                    </div>
                    <div class="stat-item">
                        <span>📖</span>
                        <span>{{ str_word_count(strip_tags($blog->content)) }} words</span>
                    </div>
                </div>
            </div>

            <!-- Content Preview -->
            <div class="content-preview">
                {{ Str::limit(strip_tags($blog->content), 320, '...') }}
            </div>

            <!-- Main CTA -->
            <div class="action-section">
                <a href="https://propx-pro.vercel.app/blog/post/{{ $blog->id }}" class="main-cta" target="_blank">
                    🚀 Read Complete Article
                </a>
            </div>

            <!-- Tags Cloud -->
            @if($blog->tags && count($blog->tags) > 0)
            <div class="tags-cloud">
                <div class="tags-title">Related Topics</div>
                <div>
                    @foreach($blog->tags as $tag)
                    <span class="tag-item"># {{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Glass Footer -->
        <div class="glass-footer">
            <h3 class="footer-title">Stay Connected</h3>
            <p class="footer-subtitle">Join our community of real estate innovators</p>

            <!-- Social Buttons -->
            <div class="social-buttons">
                <a href="#" class="social-btn">📧</a>
                <a href="#" class="social-btn">🌐</a>
                <a href="#" class="social-btn">💼</a>
                <a href="#" class="social-btn">📱</a>
                <a href="#" class="social-btn">🔗</a>
            </div>

            <!-- Footer Links -->
            <div class="footer-links">
                <a href="{{ url('/') }}">Visit Website</a>
                <a href="{{ url('/blog') }}">All Articles</a>
                <a href="{{ url('/about') }}">About Us</a>
                <a href="{{ url('/contact') }}">Contact</a>
            </div>

            <div class="company-info">
                © 2025 PropX Pro. All rights reserved.<br>
                Your trusted partner in property solutions.
            </div>
        </div>
    </div><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $blog->title }} - PropX Pro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .hero-section {
            position: relative;
            height: 500px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg,
                    rgba(102, 126, 234, 0.85) 0%,
                    rgba(118, 75, 162, 0.75) 30%,
                    rgba(240, 147, 251, 0.65) 60%,
                    rgba(0, 0, 0, 0.4) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 40px;
        }

        .floating-logo {
            position: absolute;
            top: 30px;
            left: 40px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            padding: 12px 24px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .category-pill {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-title {
            font-size: 42px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            max-width: 550px;
        }

        .hero-description {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.6;
            margin-bottom: 35px;
            max-width: 480px;
            font-weight: 400;
        }

        .hero-button {
            background: rgba(255, 255, 255, 1);
            color: #667eea !important;
            padding: 18px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .hero-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.6s;
        }

        .hero-button:hover::before {
            left: 100%;
        }

        .hero-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            color: #764ba2 !important;
        }
        
        .glass-content {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
        }

        .author-section {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding: 24px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 22px;
            margin-right: 20px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .author-info {
            flex: 1;
        }

        .author-name {
            color: #1a202c;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .publish-date {
            color: #718096;
            font-size: 15px;
            font-weight: 500;
        }

        .reading-stats {
            display: flex;
            gap: 25px;
            color: #4a5568;
            font-size: 14px;
            font-weight: 600;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .content-preview {
            color: #2d3748;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 40px;
            font-weight: 400;
            background: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .action-section {
            text-align: center;
            margin: 50px 0;
        }

        .main-cta {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff !important;
            padding: 20px 48px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            position: relative;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            overflow: hidden;
        }

        .main-cta::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .main-cta:hover::after {
            width: 300px;
            height: 300px;
        }

        .main-cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.6);
        }
        
        .tags-cloud {
            margin: 40px 0;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tags-title {
            color: #2d3748;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 20px;
        }

        .tag-item {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            backdrop-filter: blur(10px);
            color: #4a5568;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            margin: 6px 8px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }

        .tag-item:hover {
            background: rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            color: #2d3748;
        }
        
        .glass-footer {
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.1) 0%, 
                rgba(118, 75, 162, 0.08) 50%, 
                rgba(240, 147, 251, 0.05) 100%);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-title {
            color: #1a202c;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-subtitle {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 35px;
            font-weight: 500;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 35px 0;
        }

        .social-btn {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            color: white !important;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .social-btn:hover {
            background: linear-gradient(135deg, #764ba2, #f093fb);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .footer-links {
            margin-top: 35px;
            padding-top: 30px;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
        }

        .footer-links a {
            color: #4a5568;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            margin: 0 25px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .footer-links a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .company-info {
            color: #718096;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(102, 126, 234, 0.1);
            font-weight: 500;
        }
        
        /* Responsive Design */
        @media (max-width: 650px) {
            body {
                padding: 20px 10px;
            }

            .email-container {
                border-radius: 20px;
            }

            .hero-section {
                height: 450px;
            }

            .hero-overlay {
                padding: 40px 25px;
            }

            .hero-title {
                font-size: 32px;
            }

            .glass-content,
            .glass-footer {
                padding: 40px 25px;
            }

            .floating-logo {
                top: 20px;
                left: 25px;
                padding: 10px 16px;
            }

            .author-section {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .reading-stats {
                flex-direction: column;
                gap: 10px;
            }

            .social-buttons {
                flex-wrap: wrap;
                gap: 15px;
            }

            .footer-links a {
                display: block;
                margin: 10px 0;
            }

            .main-cta {
                padding: 18px 40px;
                font-size: 15px;
            }
        }

        /* Enhanced animations */
        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: 200px 0;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .glass-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            background-size: 200px 100%;
            animation: shimmer 3s infinite;
        }

        .floating-elements {
            animation: float 6s ease-in-out infinite;
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