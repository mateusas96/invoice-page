<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth', 'middleware' => 'validateBackHistory'], function(){

    Route::apiResources([
        'user'=>'API\UserController',
        'bankAccount'=>'API\BankAccountController',
        'invoices'=>'API\InvoiceController',
    ]);

    Route::get('findUser','API\UserController@search')->name('find.user');

    Route::get('profile','API\ProfileController@profile')->name('get.profile.data');

    Route::put('updateProfile/{userID}','API\ProfileController@updateProfile')->name('profile.update');

    Route::put('updatePassword/{userID}','API\ProfileController@updatePassword')->name('password.update');

    Route::get('billToClientList','API\InvoiceController@billToClientList')->name('invoice.billToClientList');

    Route::get('billSendersInfo','API\InvoiceController@billSendersInfo')->name('invoice.billSendersInfo');

    Route::get('invoiceNumber','API\InvoiceController@invoiceNumber')->name('invoice.invoiceNumber');

    Route::get('checkForUnpaidInvoices','API\InvoiceController@checkForUnpaidInvoices')->name('invoice.checkForUnpaidInvoices');

    Route::get('invoiceView/{invoiceID}','API\InvoiceController@invoiceView')->name('invoice.invoiceView');

    Route::get('invoiceItemsView/{invoiceID}','API\InvoiceController@invoiceItemsView')->name('invoice.invoiceItemsView');
});

