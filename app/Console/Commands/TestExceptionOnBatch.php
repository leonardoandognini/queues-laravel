<?php

namespace App\Console\Commands;

use App\Jobs\TestExceptionJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class TestExceptionOnBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Exception on batch';


    public function handle()
    {

        $user1 = User::query()->offset(0)->first();
        $user2 = User::query()->offset(1)->first();
        $user3 = User::query()->offset(2)->first();

        $user4 = User::query()->offset(3)->first();
        $user5 = User::query()->offset(4)->first();
        $user6 = User::query()->offset(5)->first();

        $users = User::query()->take(10)->offset(10)->get()->map(fn ($u) => new TestExceptionJob($u, false));


        Bus::batch([
            ...$users,
            [ //Chain
                new TestExceptionJob($user1, true),
                new TestExceptionJob($user2, false),
                new TestExceptionJob($user3, false)
            ],
            [ //Chain
                new TestExceptionJob($user4, false),
                new TestExceptionJob($user5, true),
                new TestExceptionJob($user6, false)
            ]
        ])->dispatch();

//       Batch Example
//        Bus::batch([
//            new TestExceptionJob($user1, true),
//            new TestExceptionJob($user2, false),
//            new TestExceptionJob($user3, false)
//        ])->catch(function (Batch $batch, Throwable $e) {
//            ds('primeiro job falhou', $e)->danger();
//            $batch->cancel();
//        })->dispatch();

        /* Chain Example */
//        Bus::chain([
//            new TestExceptionJob($user1, false),
//            new TestExceptionJob($user2, true),
//            new TestExceptionJob($user3, false)
//        ])->catch(function (Batch $batch, Throwable $e) {
//            ds('primeiro job falhou', $e)->danger();
//            $batch->cancel();
//        })->dispatch();


    }
}
