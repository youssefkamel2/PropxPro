<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Blog: {{ $blog->title }}</title>
    <style type="text/css">
        /* Client-specific styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Reset styles */
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        
        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        
        /* Main styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            background-color: #f7f7f7;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        .content-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .blog-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }
        
        .blog-content {
            padding: 25px;
        }
        
        .blog-title {
            font-size: 24px;
            font-weight: 700;
            color: #222222;
            margin: 0 0 15px 0;
            line-height: 1.3;
        }
        
        .blog-meta {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666666;
        }
        
        .category-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-right: 10px;
        }
        
        .trending { background-color: #ffecec; color: #ff4d4d; }
        .guides { background-color: #e6f3ff; color: #2a7de1; }
        .insights { background-color: #e6ffe6; color: #00b300; }
        
        .excerpt {
            font-size: 16px;
            line-height: 1.6;
            color: #444444;
            margin-bottom: 25px;
        }
        
        .read-more-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3a86ff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .read-more-btn:hover {
            background-color: #2667cc;
        }
        
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #999999;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-icon {
            display: inline-block;
            margin: 0 8px;
        }
        
        .unsubscribe-link {
            color: #999999;
            text-decoration: underline;
            font-size: 12px;
        }
        
        @media screen and (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .blog-content {
                padding: 20px;
            }
            
            .blog-title {
                font-size: 20px;
            }
            
            .read-more-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" valign="top">
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="600">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="https://propx-pro.vercel.app/images/logo.png" alt="ProPX Logo" class="logo" />
                        </td>
                    </tr>
                    
                    <!-- Blog Card -->
                    <tr>
                        <td class="content-card">
                            <!-- Blog Image -->
                            <img src="{{ asset('storage/' . $blog->cover_photo) }}" alt="{{ $blog->title }}" class="blog-image" />
                            
                            <!-- Blog Content -->
                            <table class="blog-content" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <span class="category-tag {{ $blog->category }}">{{ $blog->category }}</span>
                                        <span>{{ $blog->created_at->format('F j, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h1 class="blog-title">{{ $blog->title }}</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="excerpt">{{ Str::limit(strip_tags($blog->content), 150) }}...</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="https://propx-pro.vercel.app/blog/post/{{ $blog->id }}" class="read-more-btn">Read Full Article</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <div class="social-links">
                                <a href="#" class="social-icon"><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook" width="24" /></a>
                                <a href="#" class="social-icon"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter" width="24" /></a>
                                <a href="#" class="social-icon"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" width="24" /></a>
                                <a href="#" class="social-icon"><img src="https://cdn-icons-png.flaticon.com/512/3536/3536569.png" alt="LinkedIn" width="24" /></a>
                            </div>
                            <p>&copy; {{ date('Y') }} ProPX. All rights reserved.</p>
                            <p>
                                <a href="#" class="unsubscribe-link">Unsubscribe</a> | 
                                <a href="#" class="unsubscribe-link">Privacy Policy</a>
                            </p>
                            <p>123 Business Ave, Suite 100, San Francisco, CA 94107</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>