<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PembobotanController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\BobotTweetController;

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

Route::get('/', function () {
    return view('index');
});

Route::get('/dataset', [DatasetController::class, 'index'])->name('dataset');
Route::post('/dataset/import-excel', [DatasetController::class, 'import_excel'])->name('import');

Route::get('/preprocessing', [PreprocessingController::class, 'index'])->name('preprocessing');
Route::post('/preprocessing/process', [PreprocessingController::class, 'preprocessing'])->name('preprocessing.process');

Route::get('/pembobotan', [PembobotanController::class, 'index'])->name('pembobotan');
Route::post('/pembobotan/process', [PembobotanController::class, 'pembobotan'])->name('pembobotan.process');
Route::post('/pembobotan/tfidf', [PembobotanController::class, 'tfidf'])->name('pembobotan.tfidf');

Route::get('/bobottweet', [BobotTweetController::class, 'index'])->name('bobottweet');
Route::post('/bobottweet/process', [BobotTweetController::class, 'pembobotan'])->name('bobottweet.process');
