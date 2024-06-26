<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\VendaController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para clientes
Route::get('/api/clientes', [ClienteController::class, 'index']);
Route::get('/clientes', [ClienteController::class, 'index']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::get('/api/clientes/check-cpf/{cpf}', [ClienteController::class, 'checkCpf']);

// Rotas para produtos
Route::get('/api/produtos', [ProdutoController::class, 'index']);
Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/produtos', [ProdutoController::class, 'store']);
Route::get('/api/produtos/{id}', [ProdutoController::class, 'show']);

// Rotas para vendas e parcelas
Route::post('/api/vendas', [VendaController::class, 'store'])->name('vendas.store');
Route::get('/vendas-lista', [VendaController::class, 'list'])->name('vendas.lista');
Route::get('/vendas/{id}/editar', [VendaController::class, 'edit'])->name('vendas.editar');
Route::get('/vendas/{id}/parcelas', [VendaController::class, 'mostrarParcelas'])->name('vendas.mostrarParcelas');

Route::delete('/vendas/{venda_id}/parcelas/{parcela_id}', [ParcelaController::class, 'deletarParcela'])
    ->name('parcelas.deletar');

Route::put('/vendas/{venda_id}/parcelas/{parcela_id}/atualizar', [ParcelaController::class, 'atualizarParcela'])
    ->name('parcelas.atualizar');

Route::get('/vendas/{venda_id}/parcelas/{parcela_id}/detalhes', [ParcelaController::class, 'detalhesParcela'])
    ->name('parcelas.detalhes');

Route::post('/vendas/{venda_id}/parcelas/atualizar-todas', [ParcelaController::class, 'atualizarTodasParcelas'])
    ->name('parcelas.atualizarTodos');
require __DIR__ . '/auth.php';
