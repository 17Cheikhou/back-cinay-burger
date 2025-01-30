<?php

use App\Http\Controllers\ArticleCommandeController;
use App\Http\Controllers\commandeController;
use App\Http\Controllers\Auth\AuthContoller;
use App\Http\Controllers\StatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('burgers',\App\Http\Controllers\burgerController::class);
Route::get('burgers/archives',[\App\Http\Controllers\burgerController::class,'getArchived']);
Route::get('burger', [\App\Http\Controllers\burgerController::class, 'getBurgers']);

Route::get('burgers/search', [\App\Http\Controllers\burgerController::class, 'search']);
Route::get('commandes/search', [CommandeController::class, 'search']);


Route::put('burgers/{id}/changeStatutArchive', [\App\Http\Controllers\burgerController::class, 'changeStatut']);

Route::apiResource('articles_commandes', ArticleCommandeController::class);
Route::apiResource('commandes', CommandeController::class);
Route::get('/commandes/{id}/pdf', [CommandeController::class, 'generatePdf']);


Route::controller(AuthContoller::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:api');
});

Route::middleware('auth:api')->get('/user-info', function (Request $request) {
    return $request->user();
});


Route::post('/password/email', [AuthContoller::class,'sendResetLinkEmail']);


Route::post('/password/reset', [AuthContoller::class,'reset']);


Route::post('/password/change', [AuthContoller::class,'changePassword'])->middleware('auth:sanctum');



Route::get('statistiques/commandes-en-cours', [StatisticController::class, 'commandesEnCours']);
Route::get('statistiques/commandes-validees', [StatisticController::class, 'commandesValidees']);
Route::get('statistiques/recettes-journalieres', [StatisticController::class, 'recettesJournalieres']);
Route::get('statistiques/commandes-annulees', [StatisticController::class, 'commandesAnnulees']);
