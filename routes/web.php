<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Bus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('send-notifications-to-all', function () {
    \App\Jobs\SendNotificationsJob::dispatch();
    return 'ok';
});

Route::get('run-batch', function () {

    Bus::batch([
        new \App\Jobs\MakeOrder(),
        new \App\Jobs\ValidateCard(),
        new \App\Jobs\RunPayment()
    ])->name('Run Batch Example' . rand(1, 10))->dispatch();

});
