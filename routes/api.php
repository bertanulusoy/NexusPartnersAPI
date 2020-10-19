<?php

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


// 1. GET /api/partners -- return all partners sorted by id field and paginate the results. The results should contain also the full URL to partner logo.

// 2. GET /api/partners?name=ab&per_page=3&page=2 -- return partners where name starts with prefix "ab".

// 3. GET /api/partners/{id} -- return a single partner with the given id.

// 4. POST /api/partners -- create a new partner, upload a partner logo, crop it to 100x100 pixels and store the filename in the field "photo". Implement a case insensitive rule that the "name" field can't contain the substring "Nexus" or its dashed derivatives (e.g. "N-exus", "nE-x----U-s").

// 5. PATCH /api/partners/{id} -- allow to update the partner info including the logo, the same validation rules apply as before.

// 6. DELETE /api/partners/{id} -- delete the partner with the given id.


Route::get('/partners', function() {
    return ['message' => 'hello'];
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
