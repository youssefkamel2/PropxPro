<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Blog Post - {{ $blog->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="%23ffffff" fill-opacity="0.1"/><circle cx="80" cy="40" r="1" fill="%23ffffff" fill-opacity="0.1"/><circle cx="40" cy="80" r="1" fill="%23ffffff" fill-opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .logo {
            position: relative;
            z-index: 2;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .logo-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 400;
        }
        
        .blog-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .content {
            padding: 50px 40px;
        }
        
        .announcement-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .blog-category {
            display: inline-block;
            padding: 6px 12px;
            background: #f8f9ff;
            color: #6366f1;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            text-transform: capitalize;
            margin-bottom: 20px;
            border: 1px solid #e0e7ff;
        }
        
        .blog-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.8px;
        }
        
        .blog-excerpt {
            font-size: 16px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        
        .blog-cover {
            width: 100%;
            height: 200px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 18px 36px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        .stats-section {
            display: flex;
            justify-content: space-around;
            background: #f8fafc;
            padding: 30px;
            border-radius: 16px;
            margin: 30px 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            display: block;
        }
        
        .stat-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        
        .tags-section {
            margin: 30px 0;
        }
        
        .tag {
            display: inline-block;
            background: #e0e7ff;
            color: #5b21b6;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin: 0 8px 8px 0;
        }
        
        .footer {
            background: #1a1a1a;
            color: #ffffff;
            padding: 40px;
            text-align: center;
        }
        
        .footer-content {
            margin-bottom: 20px;
        }
        
        .footer-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .footer-description {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 30px;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            background: #374151;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }
        
        .copyright {
            font-size: 12px;
            color: #6b7280;
            padding-top: 20px;
            border-top: 1px solid #374151;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 600px) {
            .email-container {
                margin: 0 10px;
                border-radius: 16px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .blog-title {
                font-size: 24px;
            }
            
            .stats-section {
                flex-direction: column;
                gap: 20px;
            }
            
            .footer {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="blog-icon">
                    <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                </div>
                <div class="logo-text">PropX Pro</div>
                <div class="logo-subtitle">Property Insights & Guides</div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Announcement Badge -->
            <div class="announcement-badge">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                New Blog Post
            </div>
            
            <!-- Blog Category -->
            <div class="blog-category">{{ $blog->category }}</div>
            
            <!-- Blog Title -->
            <h1 class="blog-title">{{ $blog->title }}</h1>
            
            <!-- Blog Cover Image -->
            @if($blog->cover_photo)
                <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="blog-cover">
            @endif
            
            <!-- Blog Excerpt -->
            <div class="blog-excerpt">
                {{ Str::limit(strip_tags($blog->content), 200) }}
            </div>
            
            <!-- Tags Section -->
            @if($blog->tags && count($blog->tags) > 0)
                <div class="tags-section">
                    @foreach($blog->tags as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
            
            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stat-item">
                    <span class="stat-number">{{ Carbon\Carbon::parse($blog->created_at)->format('M j') }}</span>
                    <span class="stat-label">Published</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ str_word_count(strip_tags($blog->content)) }}</span>
                    <span class="stat-label">Words</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }}</span>
                    <span class="stat-label">Min Read</span>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <a href="https://propx-pro.vercel.app/blog/post/{{ $blog->id }}" class="cta-button">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                    </svg>
                    Read Full Article
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-title">Stay Connected</div>
                <div class="footer-description">
                    Get the latest property insights, market trends, and expert guides delivered to your inbox.
                </div>
                
                <!-- Social Links -->
                <div class="social-links">
                    <a href="#" class="social-link">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} PropX Pro. All rights reserved. | 
                <a href="https://propx-pro.vercel.app" style="color: #94a3b8; text-decoration: none;">Visit Website</a>
            </div>
        </div>
    </div>
</body>
</html>