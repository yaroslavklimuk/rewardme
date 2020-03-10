<?php

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

Route::middleware('auth')->group(function(){
    Route::get('/reward', function () {
        return view('welcome');
    });

    Route::post('/reward/claim', 'RewardController@claimReward');
    Route::post('/reward/accept', 'RewardController@acceptReward');
    Route::post('/reward/reject', 'RewardController@rejectReward');
});

Auth::routes();
