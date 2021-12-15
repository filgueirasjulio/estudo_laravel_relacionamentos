<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Course,
    Module
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

Route::get('/one-to-many', function () {

   $course = Course::create(['name' => 'Curso de Relacionamentos']);
   $course = Course::with('modules')->first();

   $course->modules()->create([
       'name' => 'módulo x1'
   ]);

   $course->modules()->create([
        'name' => 'módulo x2'
   ]);

   $module = Module::with('lessons')->first();

   $module->lessons()->create([
       'name' => 'one-to-one',
       'video' => 'one-to-one.mp4'
   ]);

   $module->lessons()->create([
    'name' => 'one-to-many',
    'video' => 'one-to-many.mp4'
]);

   //dd($course->modules()->get());
   //dd($module->lessons()->get());
});



Route::get('/', function () {
    return view('welcome');
});
