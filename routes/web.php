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

use App\Mail\NewUserWelcomeMail;

Auth::routes();

Route::get('/email', function()
{
    return new NewUserWelcomeMail();
    //return new instance of new user welcome mail
});

Route::post('follow/{user}', 'FollowsController@store');

Route::get('/', 'PostsController@index');

Route::get('/p/create', 'PostsController@create');
//PostsController@create->go to the PostController & hits the create method
Route::post('/p', 'PostsController@store');
Route::get('/p/{post}', 'PostsController@show');
//here {post} is variable

Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
//here profile is resource name
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');
