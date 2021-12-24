<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Course,
    Module,
    Permission,
    Image,
    Comment
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

   dump($course->modules()->get());
   dump($module->lessons()->get());
});

Route::get('/many-to-many', function () {
    $user = User::with('permissions')->find(1);

    $permission = Permission::create([
        'name' => 'Admin'
    ]);

    $permission_2 = Permission::create([
        'name' => 'Ceo'
    ]);

    $user = User::Find(1);
    
    //$user->permissions()->save($permission);
    //$user->permissions()->sync([1]); //deleta todas as permissões que não tenham o id 1.
     //$user->permissions()->attach([1, 2]); //anexa permissões ao usuário
     //$user->permisisons()->detach([1]); //desanexa permissões
     
    $user->permissions()->saveMany([
        $permission,
        $permission_2
    ]);

    $user->permissions()->attach([
        1 => ['active' => false],
        2 => ['active' => false]
    ]);

   foreach($user->permissions as $permission) {
       echo "{$permission->name} - {$permission->pivot->active} <br>";
   }
});

Route::get('/one-to-one-polymorphic', function() {
    $user = User::first();

    $user->image()->save(
        new Image(['path' => 'path/nome-image.png'])
    );

    dd($user->image->path);
});


Route::get('/one-to-many-polymorphic', function() {
    $course = Course::create([
        'name' => 'cruso teste',
        'available' => true
    ]);

    $course = Course::first();

    $course->comments()->create([
        'subject' => 'Novo comentário',
        'content' => 'Apenas um comentário'
    ]);

    $course->comments()->create([
        'subject' => 'Novo comentário (2)',
        'content' => 'Apenas um comentário (2)'
    ]);

    //dd($course->comments);

    $comment = Comment::find(1);
    //dd($comment, $comment->commentable);
});

Route::get('/', function () {
    return view('welcome');
});
