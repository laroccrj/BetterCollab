<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use const PHP_EOL;

class BackfillColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:backfill-colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        /** @var User[] $users */
        $users = User::all();

        foreach ($users as $user) {
            $user->setRandomColor()
                ->save();

            echo 'Gave ' . $user->getName() . ' color: ' . $user->getColor() . PHP_EOL;
        }
    }
}
