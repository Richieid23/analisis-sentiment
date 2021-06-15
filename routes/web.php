<?php

use App\Http\Controllers\BobotTrainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PembobotanController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\BobotTweetController;
use App\Http\Controllers\CrawlingController;
use App\Http\Controllers\SvmController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PreprocessingTrainController;

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
Route::post('/dataset/import-dataset', [DatasetController::class, 'import_dataset'])->name('import.dataset');

Route::get('/train', [TrainingController::class, 'index'])->name('train');
Route::get('/train/test', [TrainingController::class, 'train'])->name('training');
Route::post('/train/import-training', [TrainingController::class, 'import_training'])->name('import.training');

Route::get('/svm', [SvmController::class, 'index'])->name('svm');
Route::post('/svm/process', [SvmController::class, 'svm_fun'])->name('svm.process');

Route::get('/crawling', [CrawlingController::class, 'index'])->name('crawling');
Route::post('/crawling/process', [CrawlingController::class, 'crawling'])->name('crawling.process');

Route::get('/preprocessing', [PreprocessingController::class, 'index'])->name('preprocessing');
Route::post('/preprocessing/process', [PreprocessingController::class, 'preprocessing'])->name('preprocessing.process');

Route::get('/preprocessing-train', [PreprocessingTrainController::class, 'index'])->name('preprocessing.train');
Route::post('/preprocessing-train/process', [PreprocessingTrainController::class, 'preprocessing'])->name('preprocessing.train.process');

Route::get('/pembobotan', [PembobotanController::class, 'index'])->name('pembobotan');
Route::post('/pembobotan/process', [PembobotanController::class, 'pembobotan'])->name('pembobotan.process');
Route::post('/pembobotan/tfidf', [PembobotanController::class, 'tfidf'])->name('pembobotan.tfidf');

Route::get('/pembobotan-training', [BobotTrainController::class, 'index'])->name('pembobotan.training');
Route::post('/pembobotan-training/process', [BobotTrainController::class, 'pembobotan'])->name('pembobotan.training.process');
Route::post('/pembobotan-training/tfidf', [BobotTrainController::class, 'tfidf'])->name('pembobotan.training.tfidf');

Route::get('/bobottweet', [BobotTweetController::class, 'index'])->name('bobottweet');
Route::post('/bobottweet/process', [BobotTweetController::class, 'pembobotan'])->name('bobottweet.process');
