<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use App\Models\NewsletterEmailLog;

class NewsletterEmailStats extends Command
{
    protected $signature = 'newsletter:email-stats {blog_id}';
    protected $description = 'Show email delivery stats for a blog newsletter blast';

    public function handle()
    {
        $blogId = $this->argument('blog_id');
        $blog = Blog::find($blogId);
        if (!$blog) {
            $this->error('Blog not found.');
            return 1;
        }
        $sent = NewsletterEmailLog::where('blog_id', $blogId)->where('status', 'sent')->count();
        $failed = NewsletterEmailLog::where('blog_id', $blogId)->where('status', 'failed')->count();
        $this->info("Blog: {$blog->title}");
        $this->info("Sent: $sent");
        $this->info("Failed: $failed");
        if ($failed > 0) {
            $this->info('Failed emails:');
            $logs = NewsletterEmailLog::where('blog_id', $blogId)->where('status', 'failed')->get();
            foreach ($logs as $log) {
                $this->line("- Subscriber ID: {$log->subscriber_id}, Error: {$log->error_message}");
            }
        }
        return 0;
    }
} 