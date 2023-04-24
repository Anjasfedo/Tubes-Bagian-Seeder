<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;

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
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::resource('/produk', ProdukController::class);

    Route::get('/jenis/data', [JenisController::class, 'data'])->name('jenis.data');
    Route::resource('/jenis', JenisController::class);

    Route::get('/bahan/data', [BahanController::class, 'data'])->name('bahan.data');
    Route::post('/bahan/delete-selected', [BahanController::class, 'deleteSelected'])->name('bahan.delete_selected');
    Route::resource('/bahan', BahanController::class);

    Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
    Route::post('/member/delete-selected', [MemberController::class, 'deleteSelected'])->name('member.delete_selected');
    Route::resource('/member', MemberController::class);

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::post('/supplier/delete-selected', [SupplierController::class, 'deleteSelected'])->name('supplier.delete_selected');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', PengeluaranController::class);

    Route::get('/pemasukan/data', [PemasukanController::class, 'data'])->name('pemasukan.data');
    Route::resource('/pemasukan', PemasukanController::class);

    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class)
            ->except('create');

    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');        
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');
});