<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //
    }
}
