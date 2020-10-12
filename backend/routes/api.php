<?php


Route::post('/auth/login','UserController@login')->name('login');
Route::post('/auth/register','UserController@register')->name('register');
Route::get('/auth/logout/{token}','UserController@logout')->name('logout');

Route::group (['middleware'=>['checktoken']],function() { 

    Route::get('/board/{token}','BoardController@index')->name('board');
    Route::post('/board/{token}','BoardController@store')->name('board.store');
    Route::put('/board/{board_id}/{token}','BoardController@update')->name('board.update');
    Route::delete('/board/{board_id}/{token}','BoardController@delete')->name('board.delete');

    Route::get('/board/{board_id}/{token}','BoardController@show')->name('board.show');

    Route::post('/board/{id}/member/{token}','BoardController@addMember')->name('member.store');
    Route::delete('/board/{id}/member/{user_id}/{token}','BoardController@deleteMember')->name('member.delete');

    Route::get('/board/{id}/list/{token}','ListController@index')->name('list.index');
    Route::post('/board/{id}/list/{token}','ListController@store')->name('list.store');
    Route::put('/board/{board_id}/list/{list_id}/{token}','ListController@update')->name('list.update');
    Route::delete('/board/{board_id}/list/{list_id}/{token}','ListController@delete')->name('list.delete');

    Route::get('/board/{id}/card/{token}','CardController@index')->name('list.index');
    Route::post('/board/{board_id}/list/{list_id}/card/{token}','CardController@store')->name('card.store');
    Route::put('/board/{board_id}/card/{card_id}/{token}','CardController@update')->name('card.update');
    Route::delete('/board/{board_id}/card/{card_id}/{token}','CardController@delete')->name('card.delete');
   
    Route::put('/board/{board_id}/list/{list_id}/right/{token}','ListController@moveRight')->name('list.right');
    Route::put('/board/{board_id}/list/{list_id}/left/{token}','ListController@moveLeft')->name('list.left');
    Route::put('/board/{board_id}/card/{card_id}/up/{token}','CardController@moveUp')->name('card.up');
    Route::put('/board/{board_id}/card/{card_id}/down/{token}','CardController@moveDown')->name('card.down');
    Route::put('/card/{card_id}/move/{list_id}/{token}','CardController@moveCard')->name('card.move');

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });