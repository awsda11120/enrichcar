<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Storage;

Route::get('/', [Admincontroller::class,'info']);
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', [AdminController::class, 'info']);
Route::get('/receive', function () {
    return view('receive');
});


Route::get('/customer', [AdminController::class,'customer'])->name('customer');
Route::get('/car', [AdminController::class,'car'])->name('car');
Route::get('/add',[AdminController::class,'add']);
Route::get('/info',[Admincontroller::class,'info']);
// Route::get('/info/{List}', [Admincontroller::class, 'info']);
Route::get('/sum',[Admincontroller::class,'sum']);

Route::post('/insertInfo',[AdminController::class,'insertInfo']);
Route::get('deleteCus/{id}',[AdminController::class,'deleteCus'])->name('deleteCus');
Route::get('deleteCar/{id}',[AdminController::class,'deleteCar'])->name('deleteCar');
Route::get('editCus/{id}',[AdminController::class,'editCus'])->name('editCus');
Route::get('editCar/{id}',[AdminController::class,'editCar'])->name('editCar');
// Route::post('updateCus/{id}',[AdminController::class,'updateCus'])->name('updateCus');
// Route::post('updateCar/{id}',[AdminController::class,'updateCar'])->name('updateCar');


Route::get('/settings/general/{category}', [SettingsController::class, 'index']);
Route::get('/settings/general', [SettingsController::class, 'index']);
Route::get('/settings/cost', [SettingsController::class, 'cost']);
Route::get('/settings/cost/remove/{id}', [SettingsController::class, 'remove_cost'])->name('deleteCost');
Route::get('/settings/general/remove/{id}', [SettingsController::class, 'remove_general'])->name('deleteGeneral');
Route::post('/addcost',[SettingsController::class,'addcost']);
Route::post('/addgeneral',[SettingsController::class,'addgeneral']);

Route::get('/infomation/{id}',[AdminController::class,'showIn'])->name('infomation');//โชว์หน้า infomation
Route::get('CheckRenew/{id}',[AdminController::class,'renewCheck'])->name('CheckRenew');
Route::get('editInfo/{id}',[AdminController::class,'editInfo'])->name('editInfo');
Route::post('updateInfo/{id}',[AdminController::class,'updateInfo'])->name('updateInfo');
// Route::get('/check-costs/{id}/{typeRenew?}', [CalculateController::class, 'CalCosts'])->name('CheckCosts');
Route::get('CheckCosts/{id}/{typeRenew?}',[CalculateController::class,'CalCosts'])->name('CheckCosts');
Route::post('CheckCosts/{id}/{typeRenew?}', [CalculateController::class, 'CalCosts']);
Route::get('InsView',[AdminController::class,'InsView']);
Route::get('/history/{id}',[AdminController::class,'storeHistory'])->name('history');
Route::get('/ShowHis',[AdminController::class,'showHis']);
Route::get('/sum', [ChartController::class, 'CarChart'])->name('sum');
Route::get('/getChartData', [ChartController::class, 'getChartData']);
Route::get('/receive',[AdminController::class,'showReceive']);
Route::get('/sum', [ChartController::class, 'CarChart']);
Route::get('/receive', [AdminController::class, 'showReceive'])->name('receiveStore');

Route::post('/store-history', [AdminController::class, 'storeHistory'])->name('storeHistory');
Route::post('/update-date-renew', [AdminController::class, 'updateDateRenew'])->name('updateDateRenew');


Route::post('/history/update/{id}', [AdminController::class, 'storeHistoryReceive'])->name('history.update');
// Route::post('/history/update/{id}', [HistoryController::class, 'updateHistory'])->name('history.update');
// Route::get('/upload/{filename}', function ($filename) {
//     return response()->file(storage_path('upload\doc' . $filename));
// });

Route::get('/summary', [ChartController::class, 'index'])->name('summary');
Route::get('/getChartData', [ChartController::class, 'getChartData']);

Route::post('/update-status', [AdminController::class, 'updateStatus'])->name('update.status');
