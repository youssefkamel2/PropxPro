<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $blog->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
        }
        
        .email-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        }
        
        .hero-section {
            position: relative;
            height: 500px;
            overflow: hidden;
        }
        
        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, 
                rgba(102,126,234,0.9) 0%, 
                rgba(118,75,162,0.8) 30%,
                rgba(240,147,251,0.7) 60%,
                rgba(0,0,0,0.6) 100%);
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
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .hero-title {
            font-size: 36px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
            max-width: 500px;
        }
        
        .hero-description {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            margin-bottom: 32px;
            max-width: 400px;
            font-weight: 400;
        }
        
        .hero-button {
            background: rgba(255, 255, 255, 1);
            color: #667eea;
            padding: 16px 32px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        
        .hero-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102,126,234,0.1), transparent);
            transition: left 0.6s;
        }
        
        .hero-button:hover::before {
            left: 100%;
        }
        
        .hero-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            color: #764ba2;
        }
        
        .glass-content {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            position: relative;
        }
        
        .author-section {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .author-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 20px;
            margin-right: 16px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .author-info {
            flex: 1;
        }
        
        .author-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .publish-date {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 400;
        }
        
        .reading-stats {
            display: flex;
            gap: 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .content-preview {
            color: rgba(255, 255, 255, 0.9);
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 40px;
            font-weight: 400;
        }
        
        .action-section {
            text-align: center;
            margin: 50px 0;
        }
        
        .main-cta {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #f1f5f9;
            padding: 20px 48px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            position: relative;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
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
        }
        
        .tags-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        
        .tag-item {
            display: inline-block;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            color: rgba(255, 255, 255, 0.9);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 500;
            margin: 4px 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .tag-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .glass-footer {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .footer-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        
        .footer-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 30px 0;
        }
        
        .social-btn {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }
        
        .footer-links {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            margin: 0 20px;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #ffffff;
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
                height: 400px;
            }
            
            .hero-overlay {
                padding: 40px 25px;
            }
            
            .hero-title {
                font-size: 28px;
            }
            
            .glass-content, .glass-footer {
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
            
            .social-buttons {
                flex-wrap: wrap;
            }
            
            .footer-links a {
                display: block;
                margin: 10px 0;
            }
        }
        
        /* Animation for glass effects */
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        .glass-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 200px 100%;
            animation: shimmer 3s infinite;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Hero Section with Glass Morphism -->
        <div class="hero-section">
            @if($blog->cover_photo)
            <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="hero-image">
            @endif
            
            <div class="hero-overlay">
                <!-- Floating Logo -->
                <div class="floating-logo">
                    <a href="{{ url('/') }}" class="logo">PropxPro</a>
                </div>
                
                <!-- Content -->
                <div class="category-pill">{{ ucfirst($blog->category) }}</div>
                <h1 class="hero-title">{{ $blog->title }}</h1>
                <p class="hero-description">
                    {!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 140) !!}...
                </p>
                <a href="{{ url('/blog/' . $blog->id) }}" class="hero-button" target="_blank">
                    Explore Article ✨
                </a>
            </div>
        </div>
        
        <!-- Glass Content Section -->
        <div class="glass-content">
            <!-- Author Section -->
            <div class="author-section">
                <div class="author-avatar">
                    {{ substr($blog->author->name ?? 'A', 0, 1) }}
                </div>
                <div class="author-info">
                    <div class="author-name">{{ $blog->author->name ?? 'Admin Team' }}</div>
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
                {!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 320) !!}...
            </div>
            
            <!-- Main CTA -->
            <div class="action-section">
                <a href="{{ url('/blog/' . $blog->id) }}" class="main-cta" target="_blank">
                    🚀 Read Complete Article
                </a>
            </div>
            
            <!-- Tags Cloud -->
            @if($blog->tags && count($blog->tags) > 0)
            <div class="tags-cloud">
                <div class="tags-title">Related Topics</div>
                @foreach($blog->tags as $tag)
                <span class="tag-item"># {{ $tag }}</span>
                @endforeach
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
        </div>
    </div>
</body>
</html>