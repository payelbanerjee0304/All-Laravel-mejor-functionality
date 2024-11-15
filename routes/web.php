<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FormBuilderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/insert-form',[MainController::class,'insertForm'])->name('insert-form');
Route::post('formSubmit',[MainController::class,'formSubmit'])->name('formSubmit');
Route::get('/job',[MainController::class,'jobViewPage'])->name('jobViewPage');
Route::get('/download-assigned-user', [MainController::class, 'downloadAssignedUser'])->name('download.assigned.user');
Route::get('download/{filename}', [MainController::class, 'downloadAssignedUsers'])->name('download.file');

Route::get("/get-districts/{stateId}",[MainController::class,"getDistricts"])->name('getDistricts');
Route::get("/get-cities/{districtId}",[MainController::class,"getCities"])->name('getCities');

Route::get('/search', [MainController::class, 'search'])->name('search');
Route::get('/pagination/paginate-data', [MainController::class, 'pagination']);
Route::get('/download', [MainController::class, 'download'])->name('download');

Route::get('/edit{ep}',[MainController::class,'edit'])->name('edit');
Route::post('update',[MainController::class,'update'])->name('update');
Route::get('/delete/{id}',[MainController::class,'delete'])->name('delete');
Route::get('/codes{cd}',[MainController::class,'codes'])->name('codes');

Route::post('/send-otp', [MainController::class, 'sendOtp'])->name('sendOtp');
Route::post('/verify-otp', [MainController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/send-phone-otp', [MainController::class, 'sendPhoneOtp'])->name('sendPhoneOtp');
Route::post('/verify-phone-otp', [MainController::class, 'verifyPhoneOtp'])->name('verifyPhoneOtp');

Route::middleware(['adminAuth'])->group(function () {
Route::get('/listing',[MainController::class,'displayall'])->name('admin.listing');
Route::get('uploadUser',[MainController::class,'uploadUser'])->name('uploadUser');
Route::post('upload',[MainController::class,'upload'])->name('upload');
Route::get('/display-excelfile-users',[MainController::class,'displayExcelfileUsers'])->name('admin.displayExcelfileUsers');
Route::get('/admin/paginate', [MainController::class, 'paginateUsers'])->name('admin.paginateUsers');
Route::get('/admin/search', [MainController::class, 'searchUsers'])->name('admin.searchUsers');
Route::get('/admin/dateSearch', [MainController::class, 'dateSearch'])->name('admin.dateSearch');

Route::get('/users/filter', [MainController::class, 'filterUsers'])->name('admin.filterUsers');
});

Route::get('/admin/login',[LoginController::class, 'login'])->name('admin.login')->middleware('guest');
Route::post('/admin/loginAction',[LoginController::class,'loginAction'])->name('admin.save');
Route::post('/admin/verifyAndLogin',[LoginController::class,'verifyAndLogin'])->name('admin.verifyAndLogin');

Route::get('/admin/logout',[LoginController::class, 'logout'])->name('admin.logout');

Route::get('/donate', [PaymentController::class, 'donate'])->name('donate');
Route::post('/payment', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
Route::post('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::get('/import-data', [MainController::class, 'importDataView'])->name('data.import.view');
Route::post('/import-states', [MainController::class, 'importStates'])->name('data.import.states');
Route::post('/import-districts', [MainController::class, 'importDistricts'])->name('data.import.districts');

Route::get('/company', [CompanyController::class, 'company'])->name('company');
Route::get('/captcha', [CompanyController::class, 'captcha'])->name('captcha.image');
Route::post('/company-submit', [CompanyController::class, 'companySubmit'])->name('company.submit');

Route::get('/create',[FormBuilderController::class,'create'])->name('admin.create');
Route::post('/questions', [FormBuilderController::class, 'store']);
Route::get('/fetch{fet}',[FormBuilderController::class,'fetch'])->name('fetch');
Route::post('/answersubmit/{subtaskIndex}', [FormBuilderController::class, 'answersubmit'])->name('answersubmit');
