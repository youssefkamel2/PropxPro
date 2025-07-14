<?php
namespace App\Jobs;

use App\Models\Blog;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterEmailLog;
use App\Mail\NewBlogNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBlogToSubscribersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function handle()
    {
        $subscribers = NewsletterSubscription::where('is_active', true)->get();
        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewBlogNotification($this->blog));
                NewsletterEmailLog::create([
                    'blog_id' => $this->blog->id,
                    'subscriber_id' => $subscriber->id,
                    'status' => 'sent',
                    'sent_at' => Carbon::now(),
                ]);
            } catch (\Exception $e) {
                NewsletterEmailLog::create([
                    'blog_id' => $this->blog->id,
                    'subscriber_id' => $subscriber->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => Carbon::now(),
                ]);
            }
        }
    }
} 