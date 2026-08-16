<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonorController;
use App\Http\Controllers\Api\FoodDonationController;
use App\Http\Controllers\Api\NgoController;
use App\Http\Controllers\Api\VolunteerController;
use App\Http\Controllers\Api\FoodRequestController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\RecipientController;
use App\Http\Controllers\Api\DeliveryUpdateController;
use App\Http\Controllers\Api\FeedbackController;

Route::apiResource('donors', DonorController::class);
Route::apiResource('food-donations', FoodDonationController::class);
Route::apiResource('ngos', NgoController::class);
Route::apiResource('volunteers', VolunteerController::class);
Route::apiResource('food-requests', FoodRequestController::class);
Route::apiResource('deliveries', DeliveryController::class);
Route::apiResource('recipients', RecipientController::class);
Route::apiResource('delivery-updates', DeliveryUpdateController::class);
Route::apiResource('feedback', FeedbackController::class);