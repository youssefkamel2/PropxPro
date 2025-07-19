<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4285f4;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
        .meet-link {
            background-color: #4285f4;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }
        .meet-link:hover {
            background-color: #3367d6;
        }
        .details {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Demo Confirmed!</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $demo->first_name }} {{ $demo->last_name }},</p>
        
        <p>Thank you for requesting a demo! Your session has been scheduled and confirmed.</p>
        
        <div class="details">
            <h3>Meeting Details:</h3>
            <p><strong>Date & Time:</strong> {{ $demo->preferred_datetime->format('F j, Y \a\t g:i A T') }}</p>
            <p><strong>Duration:</strong> 1 hour</p>
            @if($demo->google_meet_link)
                <p><strong>Meeting Link:</strong></p>
                <a href="{{ $demo->google_meet_link }}" class="meet-link" target="_blank">
                    Join Google Meet
                </a>
                <p><small>You can also dial in by phone if needed. Check the calendar invite for phone details.</small></p>
            @endif
        </div>


        <h3>What to Expect:</h3>
        <ul>
            <li>Live demonstration of our platform</li>
            <li>Customized walkthrough based on your needs</li>
            <li>Q&A session to address your questions</li>
            <li>Next steps discussion</li>
        </ul>

        <h3>Preparation:</h3>
        <ul>
            <li>Ensure you have a stable internet connection</li>
            <li>Test your microphone and camera beforehand</li>
            <li>Prepare any specific questions you'd like to discuss</li>
            <li>Have a pen and paper ready for notes</li>
        </ul>

        <p><strong>Need to reschedule?</strong> Please contact us at least 24 hours in advance.</p>
        
    </div>
    
    <div class="footer">
        <p>Looking forward to meeting with you!</p>
        <p>If you have any questions, please don't hesitate to contact us.</p>
    </div>
</body>
</html>