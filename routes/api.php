<?php
use App\Http\Controllers\API\GetEvents;
use App\Http\Controllers\API\DonationRevenueController;
use App\Http\Controllers\API\FollowersController;
use App\Http\Controllers\API\TopSalesController;
use App\Http\Controllers\API\SubscriptionRevenueController;
use App\Http\Controllers\API\SalesRevenueController;
use App\Http\Controllers\API\AuthController;
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

Route::post('/login',[AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    //Route::get('endpoint', [GetEvents::class, 'RetieveMyEvents']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
//Route::get('endpoint', [GetEvents::class, 'RetieveMyEvents']);
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('endpoint', [GetEvents::class, 'RetieveMyEvents'])->name('endpoint');
    Route::get('followers',[FollowersController::class, 'RetrieveFollowersCount'])->name('followers');
    Route::get('topsales',[TopSalesController::class, 'RetrieveTopSales'])->name('topsales');
    Route::get('donationrevenue',[DonationRevenueController::class,'RetrieveDonationRevenue'])->name('donationrevenue');
    Route::get('salesrevenue',[SalesRevenueController::class,'RetrieveSalesRevenue'])->name('salesrevenue');
    Route::get('subscriptionrevenue',[SubscriptionRevenueController::class, 'GetSubscriptionRevenue'])->name('subscriptionrevenue');
});

