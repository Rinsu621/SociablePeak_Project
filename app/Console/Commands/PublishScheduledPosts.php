<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\ScheduledPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will search for any scheduled posts in scheduled_post table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Your logic here
        $today = Carbon::today();
        $posts = ScheduledPost::whereDate('set_time', $today)->where('status',0)->get();

        foreach ($posts as $post) {
            // Your logic to publish the post, e.g., changing status or moving to published table
            $post->status = 1; // assuming you have a status column
            $post->published_at = Carbon::now();
            $post->save();

            $postData = [
                'user_id' => $post->user_id,
                'description' => $post->description,
                'privacy' => $post->privacy,
                'likes' => $post->likes,
                'comments' => $post->comments,
            ];
            Post::create($postData);

            Log::info("Post with ID {$post->id} published at {$post->published_at}");
        }
        Log::info("Cron job running at " . now());
    }
}
