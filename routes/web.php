<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadVideoController;

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


Route::get('/',[UploadVideoController::class,'index'])->name('upload-video-chunk');
Route::post('/upload',[UploadVideoController::class,'upload'])->name('upload-video-chunk.upload');

