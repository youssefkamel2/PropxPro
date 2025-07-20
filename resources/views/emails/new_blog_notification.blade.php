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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #0f172a;
            background: #0f0f23;
            margin: 0;
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 680px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        
        /* Modern minimal header */
        .header {
            background: #ffffff;
            padding: 20px 40px;
            position: relative;
            border-bottom: 1px solid #f1f5f9;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            color: #1e293b;
            font-size: 28px;
            font-weight: 800;
            text-decoration: none;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Floating elements for visual interest */
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20px;
            left: 20px;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60px;
            right: 30px;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 30px;
            left: 50px;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .content-wrapper {
            position: relative;
            background: #ffffff;
        }
        
        .hero-section {
            position: relative;
            margin: 0;
            overflow: hidden;
            height: 400px;
        }
        
        .hero-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(102,126,234,0.4));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .category-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #667eea;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .hero-title {
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            max-width: 500px;
        }
        
        .hero-excerpt {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            line-height: 1.6;
            max-width: 400px;
            margin-bottom: 30px;
        }
        
        .hero-cta {
            display: inline-block;
            background: rgba(255, 255, 255, 0.95);
            color: #667eea;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .hero-cta:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .content {
            padding: 50px 40px;
            position: relative;
        }
        
        .content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .meta-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .author-info {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin-right: 12px;
        }
        
        .author-details .author-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        
        .author-details .publish-date {
            color: #64748b;
            font-size: 13px;
            margin-top: 2px;
        }
        
        .read-time {
            background: #f8fafc;
            color: #64748b;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .blog-excerpt {
            font-size: 17px;
            color: #334155;
            line-height: 1.8;
            margin-bottom: 35px;
            font-weight: 400;
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
            position: relative;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #f8fafc;
            padding: 18px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }
        
        .tags-section {
            background: #f8fafc;
            padding: 25px;
            border-radius: 16px;
            margin-top: 35px;
        }
        
        .tags-title {
            font-size: 14px;
            color: #475569;
            margin-bottom: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .tag {
            display: inline-block;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            color: #475569;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            margin-right: 10px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }
        
        .tag:hover {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8);
            transform: translateY(-1px);
        }
        
        .footer {
            background: #0f172a;
            color: #e2e8f0;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
        }
        
        .footer-content h3 {
            color: #ffffff;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 700;
        }
        
        .footer-content p {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 25px;
        }
        
        .social-links {
            margin: 30px 0;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 8px;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            text-decoration: none;
            font-size: 20px;
            line-height: 48px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2, #667eea);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .social-link:hover::before {
            opacity: 1;
        }
        
        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .footer-links {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #334155;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            margin: 0 15px;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: #667eea;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 40px 0;
        }
        
        /* Content images styling */
        .blog-content-image {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 680px) {
            body {
                padding: 10px 0;
            }
            
            .email-container {
                margin: 0 10px;
                border-radius: 16px;
            }
            
            .header-content, .content, .footer {
                padding: 30px 25px !important;
            }
            
            .hero-section {
                margin: -10px 0 0 0;
                border-radius: 16px 16px 0 0;
            }
            
            .hero-image {
                height: 200px !important;
            }
            
            .hero-overlay {
                padding: 25px !important;
            }
            
            .hero-title {
                font-size: 20px !important;
            }
            
            .logo {
                font-size: 26px !important;
            }
            
            .meta-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .cta-button {
                padding: 16px 32px !important;
                font-size: 15px !important;
            }
            
            .floating-element {
                display: none;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .content-wrapper {
                background: #1e293b;
            }
            
            .content {
                color: #e2e8f0;
            }
            
            .blog-excerpt {
                color: #cbd5e1;
            }
            
            .meta-section {
                border-bottom-color: #334155;
            }
            
            .author-details .author-name {
                color: #f1f5f9;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Modern Minimal Header -->
        <div class="header">
            <div class="header-content">
                <a href="{{ url('/') }}" class="logo">PropxPro</a>
                <div class="header-badge">Newsletter</div>
            </div>
        </div>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Hero Section with Full Overlay -->
            <div class="hero-section">
                @if($blog->cover_photo)
                <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="hero-image">
                <div class="hero-overlay">
                    <span class="category-badge">{{ ucfirst($blog->category) }}</span>
                    <h1 class="hero-title">{{ $blog->title }}</h1>
                    <p class="hero-excerpt">{!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 120) !!}...</p>
                    <a href="{{ url('/blog/' . $blog->id) }}" class="hero-cta" target="_blank">
                        Read Full Story →
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Main Content -->
            <div class="content">
                <!-- Meta Section -->
                <div class="meta-section">
                    <div class="author-info">
                        <div class="author-avatar">
                            {{ substr($blog->author->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="author-details">
                            <div class="author-name">{{ $blog->author->name ?? 'Admin' }}</div>
                            <div class="publish-date">{{ $blog->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="read-time">{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</div>
                </div>
                
                <!-- Blog Excerpt -->
                <div class="blog-excerpt">
                    {!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 280) !!}...
                </div>
                
                <!-- Divider -->
                <div class="divider"></div>
                
                <!-- CTA Section -->
                <div class="cta-section">
                    <a href="{{ url('/blog/' . $blog->id) }}" class="cta-button" target="_blank">
                        🚀 Read Full Article
                    </a>
                </div>
                
                <!-- Tags Section -->
                @if($blog->tags && count($blog->tags) > 0)
                <div class="tags-section">
                    <div class="tags-title">Explore Topics</div>
                    @foreach($blog->tags as $tag)
                    <span class="tag"># {{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Modern Footer -->
        <div class="footer">
            <div class="footer-content">
                <h3>Stay Ahead in Real Estate</h3>
                <p>Thank you for being part of our innovative community!</p>
            </div>
            
            <!-- Social Links with Hover Effects -->
            <div class="social-links">
                <a href="#" class="social-link">📧</a>
                <a href="#" class="social-link">🐦</a>
                <a href="#" class="social-link">📘</a>
                <a href="#" class="social-link">💼</a>
                <a href="#" class="social-link">📱</a>
            </div>
            
            <!-- Footer Links -->
            <div class="footer-links">
                <a href="{{ url('/') }}">Visit Website</a>
                <a href="{{ url('/blog') }}">All Articles</a>
                <a href="{{ url('/contact') }}">Contact Us</a>
            </div>
        </div>
    </div>
</body>
</html>