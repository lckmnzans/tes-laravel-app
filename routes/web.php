<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EximController;
use App\Http\Controllers\PPICController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\InventoriController;
use App\Http\Controllers\PenerimaanBBController;
use App\Http\Controllers\PurchaseOrderBBController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\DeliveryRequestController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProductionScheduleController;


Route::get('/', function () {
    return view('auth/login');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ppic/production/index', [ProductionScheduleController::class, 'indexProduction'])->name('ppic.production.index');
    Route::post('/ppic/production/{id}/update-status', [ProductionScheduleController::class, 'updateStatus'])->name('ppic.production.updateStatus');
    Route::post('/ppic/production/{id}/rollback-status', [ProductionScheduleController::class, 'rollbackStatus'])->name('ppic.production.rollbackStatus');
    Route::get('/gudang/product/index',[ProductController::class, 'index'])->name('gudang.product.index');
    Route::get('/production/report', [ProductionScheduleController::class, 'report'])->name('ppic.production.report');
    Route::get('/production/report/print', [ProductionScheduleController::class, 'printReport'])->name('ppic.production.report.print');
    Route::get('/gudang/bahanbaku/index', [BahanBakuController::class, 'index'])->name('gudang.bahanbaku.index');
    Route::get('/purchasing/pobb/index',[PurchaseOrderBBController::class, 'index'])->name('purchasing.pobb.index');
    Route::get('/gudang/report', [InventoriController::class, 'report'])->name('gudang.report');
    Route::get('/gudang/report/print', [InventoriController::class, 'printReport'])->name('gudang.report.print');
    // web.php
Route::get('/production-info', [ProductionScheduleController::class, 'getProductionCount']);
Route::get('/surat-pengiriman/{id}', [ProductionScheduleController::class, 'docshipping'])->name('ppic.production.docshipping');
Route::get('/api/dr-po-data', [EXIMController::class, 'getDrAndPoData']);
Route::get('/bahan-baku/data', [GudangController::class, 'getBahanBakuData']);


});

// Route manager
Route::middleware(['auth', 'role:manager'])->group(function () {
    // Route untuk dashboard manager
Route::get('manager/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

    // Route untuk menampilkan daftar Delivery Request
    Route::get('manager/request-order', [DeliveryRequestController::class, 'indexForManager'])
        ->name('manager.persetujuandr');

    // Route untuk persetujuan Delivery Request (hanya untuk manager)
    Route::post('/delivery-request/{id}/approve', [DeliveryRequestController::class, 'approve'])
        ->name('manager.persetujuandr.approve');

    Route::get('/users/create', [RegisteredUserController::class, 'create'])->name('auth.register');
    Route::post('/users/create', [RegisteredUserController::class, 'store'])->name('auth.register.store');
});



Route::middleware(['auth', 'role:exim'])->group(function(){
    Route::get('/exim/dashboard',[EximController::class, 'dashboard'])->name('exim.dashboard');
    
    Route::get('/exim/pelanggan/index',[PelangganController::class, 'index'])->name('exim.pelanggan.index');
    Route::get('/exim/pelanggan/create',[PelangganController::class, 'create'])->name('exim.pelanggan.create');
    Route::post('/exim/pelanggan/index', [PelangganController::class, 'store'])->name('exim.pelanggan.index.store');

    Route::get('/exim/pelanggan/edit/{id}',[PelangganController::class, 'edit'])->name('exim.pelanggan.edit');
    Route::put('/exim/pelanggan/update/{id}', [PelangganController::class, 'update'])->name('exim.pelanggan.update');

    //Route::get('/exim/pelanggan/show',[PelangganController::class, 'show'])->name('exim.pelanggan.show');
    Route::get('/exim/pelanggan/{id}',[PelangganController::class, 'show'])->name('exim.pelanggan.show');
    Route::delete('/exim/pelanggan/index/{id}', [PelangganController::class, 'destroy'])->name('exim.pelanggan.index.destroy');

    Route::get('/exim/requestorder/index', [DeliveryRequestController::class, 'index'])->name('exim.deliveryrequest.index');
    Route::get('/exim/requestorder/create', [DeliveryRequestController::class, 'create'])->name('exim.deliveryrequest.create');
    Route::post('/exim/requestorder/index', [DeliveryRequestController::class, 'store'])->name('exim.deliveryrequest.index.store');
    Route::resource('delivery-requests', DeliveryRequestController::class);
    //Route::get('/exim/purchaseorder/create', [DeliveryRequestController::class, 'createPurchaseOrder'])->name('exim.purchaseorder.create');
    Route::post('/delivery-request/{id}/create-po', [DeliveryRequestController::class, 'createPurchaseOrder'])->name('delivery_requests.createPo');
});
Route::get('/delivery-request/{id}', [DeliveryRequestController::class, 'show'])->name('exim.deliveryrequest.show');
// Route untuk melihat status pembayaran
Route::post('/invoices/{id}/pay-invoice', [DeliveryRequestController::class, 'payInvoice'])->name('invoices.payInvoice');
Route::post('/payments/update', [DeliveryRequestController::class, 'updatePayment'])->name('payments.update');
Route::delete('/payments/{id}/delete', [DeliveryRequestController::class, 'deletePayment'])->name('payments.delete');
Route::get('/payments/{id}/download-receipt', [DeliveryRequestController::class, 'downloadReceipt'])->name('payments.downloadReceipt');

// Route untuk mencetak invoice
Route::get('/invoices/{id}/print', [DeliveryRequestController::class, 'print'])->name('deliveryrequest.invoice');


Route::get('/exim/purchaseorder/index', [DeliveryRequestController::class, 'indexPO'])->name('exim.purchaseorder.index');


Route::middleware(['auth', 'role:ppic'])->group(function(){
    Route::get('/ppic/dashboard',[PPICController::class, 'dashboard'])->name('ppic.dashboard');
    Route::get('/ppic/permintaanpesanan/index', [ProductionScheduleController::class, 'index'])->name('ppic.permintaanpesanan.index');
    Route::get('/ppic/permintaanpesanan/create/{id}', [ProductionScheduleController::class, 'create'])->name('ppic.permintaanpesanan.create');
    Route::post('/ppic/permintaanpesanan/index', [ProductionScheduleController::class, 'store'])->name('ppic.permintaanpesanan.index.store');
    Route::get('/ppic/permintaanpesanan/{id}/show', [ProductionScheduleController::class, 'show'])->name('ppic.permintaanpesanan.show');
    Route::get('/ppic/permintaanpesanan/{id}/cetak', [ProductionScheduleController::class, 'cetakJadwal'])->name('ppic.permintaanpesanan.cetak');
    


    //Route::get('/ppic/production/index', [ProductionScheduleController::class, 'indexProduction'])->name('ppic.production.index');
    //Route::post('/ppic/production/{id}/update-status', [ProductionScheduleController::class, 'updateStatus'])->name('ppic.production.updateStatus');

    
});



Route::middleware(['auth', 'role:gudang'])->group(function(){
    Route::get('/gudang/dashboard',[GudangController::class, 'dashboard'])->name('gudang.dashboard');

    
    Route::get('/gudang/product/create',[ProductController::class, 'create'])->name('gudang.product.create');
    Route::post('/gudang/product/index', [ProductController::class, 'store'])->name('gudang.product.index.store');
    Route::get('/gudang/product/edit/{id}', [ProductController::class, 'edit'])->name('gudang.product.edit');
    Route::put('/gudang/product/update/{id}', [ProductController::class, 'update'])->name('gudang.product.update');
    Route::delete('/gudang/product/index/{id}',[ProductController::class, 'destroy'])->name('gudang.product.index.destroy');  
    
    //Route::get('/gudang/bahanbaku/index',[BahanBakuController::class, 'index'])->name('gudang.bahanbaku.index');
    Route::get('/gudang/bahanbaku/create',[BahanBakuController::class, 'create'])->name('gudang.bahanbaku.create');
    Route::post('/gudang/bahanbaku/index', [BahanBakuController::class, 'store'])->name('gudang.bahanbaku.index.store');
    Route::get('/gudang/bahanbaku/edit/{id}', [BahanBakuController::class, 'edit'])->name('gudang.bahanbaku.edit');
    Route::put('/gudang/bahanbaku/update/{id}', [BahanBakuController::class, 'update'])->name('gudang.bahanbaku.update');
    Route::delete('/gudang/bahanbaku/index/{id}',[BahanBakuController::class, 'destroy'])->name('gudang.bahanbaku.index.destroy'); 
    Route::get('/gudang/bahanbaku/stok_minimum',[BahanBakuController::class, 'stokMinimum'])->name('gudang.bahanbaku.stokminimum');

    Route::get('/gudang/pr/index',[PurchaseRequestController::class, 'index'])->name('gudang.pr.index');
    Route::get('/gudang/pr/createpr', [PurchaseRequestController::class, 'create'])->name('gudang.pr.createpr');
    Route::post('/gudang/bahanbaku/stok_minimum', [PurchaseRequestController::class, 'store'])->name('gudang.bahanbaku.stok_minimum.store');

    Route::get('/gudang/inventori/index',[InventoriController::class, 'index'])->name('gudang.inventori.index');
    Route::get('/gudang/inventori/create',[InventoriController::class, 'create'])->name('gudang.inventori.create');
    Route::post('/gudang/inventori/index', [InventoriController::class, 'store'])->name('gudang.inventori.index.store');
    Route::get('/gudang/inventori/edit/{id}', [InventoriController::class, 'edit'])->name('gudang.inventori.edit');
    Route::put('/gudang/inventori/update/{id}', [InventoriController::class, 'update'])->name('gudang.inventori.update');
    Route::delete('/gudang/inventori/index/{id}',[InventoriController::class, 'destroy'])->name('gudang.inventori.index.destroy');  
    
    Route::get('/gudang/penerimaan/index',[PenerimaanBBController::class, 'index'])->name('gudang.penerimaan.index');
    //Route::get('/gudang/penerimaan/{id}/verify-receipt', [PenerimaanBBController::class, 'verifyReceipt'])->name('gudang.penerimaan.verify');
    Route::post('/gudang/penerimaan/{id}/verifikasi', [PenerimaanBBController::class, 'storeVerification'])->name('gudang.penerimaan.storeVerification');


    //Route::get('/gudang/penerimaan/create',[PenerimaanBBController::class, 'create'])->name('gudang.penerimaan.create');
    
    //Route::get('/gudang/penerimaan/edit/{id}', [PenerimaanBBController::class, 'edit'])->name('gudang.penerimaan.edit');
    //Route::put('/gudang/penerimaan/update/{id}', [PenerimaanBBController::class, 'update'])->name('gudang.penerimaan.update');
    Route::get('/gudang/penerimaan/show/{id}', [PenerimaanBBController::class, 'show'])->name('gudang.penerimaan.detail');
    Route::delete('/gudang/penerimaan/index/{id}',[PenerimaanBBController::class, 'destroy'])->name('gudang.penerimaan.index.destroy'); 

    Route::get('/gudang/pengeluaran/index', [ProductionScheduleController::class, 'indexGudang'])->name('gudang.pengeluaran.index');
    Route::post('/gudang/pengeluaran/{id}/create-sjm', [ProductionScheduleController::class, 'createSJM'])->name('gudang.pengeluaran.create-sjm');
    Route::get('/gudang/pengeluaran/cetak-sjm/{id}', [ProductionScheduleController::class, 'cetakSJM'])->name('gudang.pengeluaran.cetak-sjm');

});



Route::middleware(['auth', 'role:purchasing'])->group(function(){
    Route::get('/purchasing/dashboard',[PurchasingController::class, 'dashboard'])->name('purchasing.dashboard');
    Route::get('/purchasing/supplier/index',[SupplierController::class, 'index'])->name('purchasing.supplier.index');
    Route::get('/purchasing/supplier/create',[SupplierController::class, 'create'])->name('purchasing.supplier.create');
    Route::post('/purchasing/supplier/index', [SupplierController::class, 'store'])->name('purchasing.supplier.index.store');
    Route::get('/purchasing/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('purchasing.supplier.edit');
    Route::put('/purchasing/supplier/update/{id}', [SupplierController::class, 'update'])->name('purchasing.supplier.update');
    Route::get('/purchasing/supplier/show',[SupplierController::class, 'show'])->name('purchasing.supplier.show');
    //Route::get('/purchasing/supplier/{id}',[SupplierController::class, 'show'])->name('purchasing.supplier.show');
    Route::delete('/purchasing/supplier/index/{id}', [SupplierController::class, 'destroy'])->name('purchasing.supplier.index.destroy');

    Route::get('purchasing/suppliers/contract_index',[SupplierController::class, 'contractsIndex'])->name('purchasing.supplier.contract_index');
    Route::get('purchasing/suppliers/contract_create', [SupplierController::class, 'createContract'])->name('purchasing.supplier.contract_create');
    Route::post('/purchasing/suppliers/contract_index', [SupplierController::class, 'storeContract'])->name('purchasing.supplier.contract_index.store');
    Route::delete('/purchasing/suppliers/contract_index/{id}', [SupplierController::class, 'destroyContract'])->name('purchasing.supplier.contract_index.destroy');
    Route::get('/purchasing/supplier/contract/{id}', [SupplierController::class, 'contractDetail'])->name('purchasing.supplier.contract_detail');


    Route::get('/gudang/pr/index',[PurchaseRequestController::class, 'index'])->name('gudang.pr.index');
    Route::get('/purchasing/pobb/create',[PurchaseOrderBBController::class, 'create'])->name('purchasing.pobb.create');
    Route::post('/purchasing/pobb/index',[PurchaseOrderBBController::class, 'store'])->name('purchasing.pobb.index.store');
    
    Route::get('/purchasing/pobb/show',[PurchaseOrderBBController::class, 'show'])->name('purchasing.pobb.show');
    //Route::get('/purchasing/pobb/{id}',[PurchaseOrderBBController::class, 'show'])->name('purchasing.pobb.show');
        // Menampilkan daftar PR untuk digabungkan ke dalam POBB
    Route::get('/purchasing/pobb/select-prs', [PurchaseOrderBBController::class, 'showPRsForPOBB'])->name('purchasing.pobb.selectPRs');
    // Route untuk mengubah status dan tanggal pengiriman POBB
    Route::put('/pobb/{id}/update-date', [PurchaseOrderBBController::class, 'updateDate'])->name('pobb.updateDate');
    
    //Route::post('/pobb/{id}/upload-invoice', [PurchaseOrderBBController::class, 'uploadInvoice'])->name('pobb.uploadInvoice');
    Route::get('/pobb/{id}/items', [PurchaseOrderBBController::class, 'getItems'])->name('pobb.items');
    Route::get('/get-prices', [SupplierController::class, 'getPrices'])->name('get.prices');
    Route::post('/pobb/{id}/buktibayar', [PurchaseOrderBBController::class, 'buktibayar'])->name('purchasing.pobb.buktibayar');

});


//route ppic
//Route::get('/ppic', function () {
    //return view('ppic.dashboard');
//})->name('ppic.dashboard');

//Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');


require __DIR__.'/auth.php';
