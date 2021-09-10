<?php

use Illuminate\Support\Facades\Route;


// Legal
Route::get('legal/approval/verify/{id}/{contract}', 'Auth\LoginController@authenticatedLegalById')->name('legal.approval.verify');
Route::namespace('Legal')->prefix('legal')->name('legal.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('contract-request', 'ContractRequestController', ['only' => ['index', 'create', 'store', 'edit', 'show', 'update', 'destroy']]);
    Route::post('uploadfilecontract', 'ContractRequestController@uploadfilecontract')->name('uploadfilecontract');
    Route::get('contract/{id}/pdf', 'ContractRequestController@generatePDF')->name('pdf');
    Route::post('contract-request/{id}/approval', 'ContractRequestController@approvalContract')->name('contract.approval');
    Route::namespace('ContractRequest')->prefix('contract-request')->name('contract-request.')->group(function () {
        Route::resource('workservicecontract', 'WorkServiceContractController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::resource('purchaseequipment', 'PurchaseEquipmentController', ['only' => ['index', 'create', 'edit', 'update', 'store']]);
        Route::resource('purchaseequipmentinstall', 'PurchaseEquipmentInstallController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::resource('mould', 'MouldController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::resource('scrap', 'ScrapController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::resource('vendorservicecontract', 'VendorServiceContractController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::resource('leasecontract', 'LeaseContractController', ['only' => ['index', 'create', 'edit', 'show', 'update']]);
        Route::resource('projectbasedagreement', 'ProjectBasedAgreementController', ['only' => ['index', 'create', 'edit', 'show', 'update']]);
        Route::resource('marketingagreement', 'MarketingAgreementController', ['only' => ['index', 'create', 'edit', 'show', 'update']]);

        Route::resource('comerciallists', 'ComercialListsController', ['only' => ['store', 'edit', 'destroy']]);
    });

    Route::namespace('AdminManagement')->prefix('adminmanagement')->name('adminmanagement.')->group(function () {
        Route::resource('approval', 'ApprovalController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
        Route::put('approval/{id}/level/up', 'ApprovalController@levelUp')->name('approval.levelup');
        Route::put('approval/{id}/level/down', 'ApprovalController@levelDown')->name('approval.leveldown');
        Route::post('approval/copyto/{dept}', 'ApprovalController@copyto')->name('approval.copyto');
    });
});
