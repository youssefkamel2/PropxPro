<?php
namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBlogNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function build()
    {
        return $this->subject('New Blog: ' . $this->blog->title)
            ->view('emails.new_blog_notification')
            ->with([
                'blog' => $this->blog,
            ]);
    }
} 