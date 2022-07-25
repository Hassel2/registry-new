<?php

use App\Http\Controllers\LicenseCard;
use App\Http\Controllers\SubsoilUsersTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/rootCompany', [SubsoilUsersTree::class, 'getManagementCompanies']);
Route::get('/rootCompany{id}/childs', [SubsoilUsersTree::class, 'getChilds']);
Route::get('/search={searchStr}', [SubsoilUsersTree::class, 'search']);
Route::get('/license{id}/getCard', [LicenseCard::class, 'get']);
