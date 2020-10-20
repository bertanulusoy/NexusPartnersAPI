<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Partner;
use App\Http\Controllers\PartnerController;

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


/*Route::get('/partners', function() {
    return ['message' => 'hello'];

       $partner = Partner::create([
        'name' => 'Bertan', 
        'photo' => 'my photo'
    ]);
    return $partner;

});*/


// 1. GET /api/partners -- return all partners sorted by id field and paginate the results. The results should contain also the full URL to partner logo.
Route::get('partners', [PartnerController::class, 'index']);
// 2. GET /api/partners?name=ab&per_page=3&page=2 -- return partners where name starts with prefix "ab".
/*Route::get('/partners/{name?}/{per_page?}/{page?}', function($name='John', $per_page=null, $page=null) {
});*/

Route::get('partners/{name?}/{per_page?}/{page?}', [PartnerController::class, 'index']);

// 3. GET /api/partners/{id} -- return a single partner with the given id.
/*Route::get('/partners/{id}', function() {
});*/
Route::get('partners/{id}', [PartnerController::class, 'show']);

// 4. POST /api/partners -- a) create a new partner, b) upload a partner logo, c) crop it to 100x100 pixels and store the filename in the field "photo". d) Implement a case insensitive rule that the "name" field can't contain the substring "Nexus" or its dashed derivatives (e.g. "N-exus", "nE-x----U-s").
Route::post('partners', [PartnerController::class, 'store']);


// 5. PATCH /api/partners/{id} -- allow to update the partner info including the logo, the same validation rules apply as before.
Route::post('partners/{id}', [PartnerController::class, 'update']);


// 6. DELETE /api/partners/{id} -- delete the partner with the given id.
Route::delete('partners/{id}', [PartnerController::class, 'delete']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
