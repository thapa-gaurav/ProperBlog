<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsurePasswordSecure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;


Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/user/index',[UserController::class,'index'])->name('user.index');
    Route::delete('post/delete/{id}',[PostController::class,'destroy'])->name('post.delete');
    Route::post('/passwordchange/{id}',[UserController::class,'flagPasswordChange'])->name('user.flagpasschange');
    Route::post('/admin/role',[RoleController::class,'createRole'])->name('create.role');
    Route::post('/post/image/replace/{id}',[PostController::class,'replaceImage'])->name('replace.image');
    Route::post('/post/image/detach/{id}',[PostController::class,'detachImage'])->name('detach.image');
    Route::post('/admin/assignrole/',[RoleController::class,'assignRole'])->name('assign.role');
    Route::post('/admin/unassignrole',[RoleController::class,'unassignRole'])->name('unassign.role');
    Route::get('/admin/get/role',[RoleController::class,'getRoles'])->name('index.role');
    Route::post('/admin/assign/{roleId}',[RoleController::class,'unassignRole'])->name('unassign.role');
    Route::post('/admin/makepermission/',[PermissionController::class,'createPermission'])->name('create.permisssion');
    Route::post('/admin/givePermission',[PermissionController::class,'assignToRole'])->name('assign.permission');
    Route::post('/admin/revokepermission',[PermissionController::class,'revokePermission'])->name('revoke.permission');
    Route::get('/admin/get/permission',[RoleController::class,'getPermissions'])->name('getPermission');
});
Route::middleware(['auth:sanctum', EnsurePasswordSecure::class])->prefix('posts')->group(function(){
    Route::get('/index',[PostController::class,'index'])->name('post.index');
    Route::post('/store',[PostController::class,'store'])->name('post.store');
    Route::get('/show/{id}',[PostController::class,'show'])->name('post.show');
    Route::patch('/update/{id}',[PostController::class,'update'])->name('post.update');
});

Route::post('/register',[AuthController::class,'register'])->name('user.register');
Route::post('/login',[AuthController::class,'login'])->name('user.login');

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('/logout',[AuthController::class,'logout'])->name('user.logout');
    Route::get('/get',[AuthController::class,'get'])->name('user.get');
});

//Route::group(['middleware'=>['auth:sanctum','role:admin']],function (){
//    Route::post('post/{id}/delete',[PostController::class,'destroy'])->name('post.delete');
//});

//Route::middleware(['auth:sanctum', 'role:admin'])->post('post/{id}/delete',[PostController::class,'destroy'])->name('post.delete');

//Route::post('/logout',[AuthController::class,'logout'])->name('user.logout')->middleware(['auth:sanctum']);
//Route::get('/get/user',[AuthController::class,'get'])->name('user.get')->middleware(['auth:sanctum']);


//to be done later

//Route::middleware()->prefix('auth')
//Route::middleware(['auth:sanctum',EnsurePasswordSecure::class])->prefix('posts')->get('/post',[PostController::class,'index'])->name('post.index');




