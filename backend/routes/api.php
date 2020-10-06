<?php

Route::post('/auth/register','RegisterController@store')->name('register.store');
Route::get('/auth/logout/{token}','UserController@logout')->name('user.logout');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


