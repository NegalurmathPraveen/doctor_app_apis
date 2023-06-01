<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\doctor_apis;

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

Route::post("/login",[doctor_apis::class,"login"]);
Route::post("/submitPatientDetails",[doctor_apis::class,"submitPatientDetails"]);
Route::post("/getAllPatientsList",[doctor_apis::class,"getAllPatientsList"]);
Route::post("/editDocProfile",[doctor_apis::class,"editDocProfile"]);
Route::post("/editPatientProfile",[doctor_apis::class,"editPatientProfile"]);
Route::post("/addNotification",[doctor_apis::class,"addNotification"]);
Route::post("/getNotifications",[doctor_apis::class,"getNotifications"]);
Route::post("/addNextVisitation",[doctor_apis::class,"addNextVisitation"]);
Route::post("/getVisitations",[doctor_apis::class,"getVisitations"]);
Route::post("/addVaccination",[doctor_apis::class,"addVaccination"]);
Route::post("/getVaccination",[doctor_apis::class,"getVaccination"]);
Route::post("/index",[doctor_apis::class,"index"]);
Route::post("/vaccinePatients",[doctor_apis::class,"vaccinePatients"]);
Route::post("/storeImage",[doctor_apis::class,"storeImage"]);
Route::post("/submitReceptionistDetails",[doctor_apis::class,"submitReceptionistDetails"]);
Route::post("/getAllReceptionistList",[doctor_apis::class,"getAllReceptionistList"]);
Route::post("/editReceptionistProfile",[doctor_apis::class,"editReceptionistProfile"]);
Route::post("/getImage",[doctor_apis::class,"getImage"]);
