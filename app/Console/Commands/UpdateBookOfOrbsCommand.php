<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBookOfOrbsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:orbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Book of Orbs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\Jobs\UpdateBookOfOrbs::dispatch();
    }
}