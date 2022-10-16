<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect(route('login'));
});
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('projects')->group(function () {
    Route::get('/', 'ProjectController@index');
    Route::get('/{id}', 'ProjectController@show');
    Route::post('/add', 'ProjectController@add');
    Route::post('/update', 'ProjectController@update');
    Route::post('/delete', 'ProjectController@delete');

    Route::get('/{project_id}/entries', 'EntryController@index');
    Route::get('/{project_id}/totalTime', 'EntryController@projectTotalTime');
    Route::post('/entry/upsert', 'EntryController@upsert');
});

Route::fallback(function(){
    return redirect('login');
});
