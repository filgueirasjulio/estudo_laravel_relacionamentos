<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Preference
};

Route::get('/one-to-one', function () {

    $user = User::first();

    $data = [
        'background_color' => '#fff',
    ];

    if($user->preference) {
        $user->preference->update($data);
    } else {
        $user->preference()->create($data);
    }

    $user->refresh();
    dump($user->preference);

    $user->preference->delete();
    $user->refresh();
    dump($user->preference);
});

Route::get('/', function () {
    return view('welcome');
});
