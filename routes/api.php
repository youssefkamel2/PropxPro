<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FeatureController;
use App\Http\Controllers\Api\IntegrationController;
use App\Http\Controllers\Api\RequestDemoController;
use App\Http\Controllers\Api\LegalDocumentController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use Illuminate\Support\Facades\Log;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

// Protected routes
Route::group(['middleware' => ['auth:api']], function () {
    // Superadmin routes for managing admins
    Route::group(['prefix' => 'admins'], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/', [AdminController::class, 'store']);
        Route::post('/{admin}', [AdminController::class, 'update']);
        Route::patch('/{admin}/toggle-status', [AdminController::class, 'toggleStatus']);
        Route::delete('/{admin}', [AdminController::class, 'destroy']);

        // Permission management
        Route::get('/permissions', [AdminController::class, 'listAvailablePermissions']);
        Route::put('/{admin}/permissions', [AdminController::class, 'updatePermissions']);
    });

    // admin routes
    Route::group(['prefix' => 'admin'], function () {

        // integrations
        Route::group(['prefix' => 'integrations'], function () {
            Route::get('/', [IntegrationController::class, 'indexAdmin']);
            Route::post('/', [IntegrationController::class, 'store']);
            Route::post('/{integration}', [IntegrationController::class, 'update']);
            Route::delete('/{integration}', [IntegrationController::class, 'destroy']);
            Route::patch('/{integration}/toggle-status', [IntegrationController::class, 'toggleStatus']);
        });

        // features
        Route::group(['prefix' => 'features'], function () {
            Route::get('/', [FeatureController::class, 'index']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::get('/{feature}', [FeatureController::class, 'show']);
            Route::put('/{feature}', [FeatureController::class, 'update']);
            Route::delete('/{feature}', [FeatureController::class, 'destroy']);
            Route::patch('/{feature}/toggle-status', [FeatureController::class, 'toggleStatus']);
        });

        // plans
        Route::group(['prefix' => 'plans'], function () {
            Route::get('/', [PlanController::class, 'indexAdmin']);
            Route::post('/', [PlanController::class, 'store']);
            Route::get('/{plan}', [PlanController::class, 'show']);
            Route::put('/{plan}', [PlanController::class, 'update']);
            Route::delete('/{plan}', [PlanController::class, 'destroy']);
            Route::patch('/{plan}/toggle-status', [PlanController::class, 'toggleStatus']);
        });

        // legal documents
        Route::group(['prefix' => 'legal-documents'], function () {
            Route::post('privacy-policy', [LegalDocumentController::class, 'updatePrivacyPolicy']);
            Route::post('terms-of-service', [LegalDocumentController::class, 'updateTermsOfService']);

        });

        // Blog media handling
        Route::group(['prefix' => 'blog-media'], function () {
            Route::post('/upload-video', [\App\Http\Controllers\Api\BlogMediaController::class, 'uploadVideo']);
            Route::post('/process-youtube', [\App\Http\Controllers\Api\BlogMediaController::class, 'processYoutubeUrl']);
        });

        // Blog management
        Route::group(['prefix' => 'blogs'], function () {
            // Single blog operations
            Route::get('/', [BlogController::class, 'index']);
            Route::post('/', [BlogController::class, 'store']);
            Route::post('/{blog}', [BlogController::class, 'update']);
            Route::post('/images/upload', [BlogController::class, 'uploadContentImage']);
            Route::delete('/{blog}', [BlogController::class, 'destroy']);
            Route::patch('/{blog}/toggle-active', [BlogController::class, 'toggleActive']);
            
            // Bulk operations
            Route::post('/bulk/delete', [BlogController::class, 'bulkDelete']);
            Route::post('/bulk/update-status', [BlogController::class, 'bulkUpdateStatus']);
            Route::post('/bulk/update-category', [BlogController::class, 'bulkUpdateCategory']);
            Route::post('/bulk/mark-as-hero', [BlogController::class, 'bulkMarkAsHero']);
            Route::post('/bulk/update', [BlogController::class, 'bulkUpdate']);
        });

        // Blog FAQ management

        Route::group(['prefix' => 'blogs/{blog}/manage/faq'], function () {
            Route::get('/', [App\Http\Controllers\Api\BlogFaqController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\BlogFaqController::class, 'store']);
            Route::get('/{faq}', [App\Http\Controllers\Api\BlogFaqController::class, 'show']);
            Route::put('/{faq}', [App\Http\Controllers\Api\BlogFaqController::class, 'update']);
            Route::delete('/{faq}', [App\Http\Controllers\Api\BlogFaqController::class, 'destroy']);
        });

        // Newsletter subscriber management
        Route::group(['prefix' => 'newsletter'], function () {
            Route::get('/', [NewsletterSubscriptionController::class, 'index']);
            Route::delete('/{newsletterSubscription}', [NewsletterSubscriptionController::class, 'destroy']);

        });

        Route::get('/request-demos', [RequestDemoController::class, 'index']);

        // Admin settings
        Route::post('/settings/request-update', [\App\Http\Controllers\Api\AdminSettingsController::class, 'requestUpdate']);
        Route::post('/settings/confirm-update', [\App\Http\Controllers\Api\AdminSettingsController::class, 'confirmUpdate']);


        Route::group(['prefix' => 'help-center'], function () {
            // Help Categories
            Route::get('categories', [App\Http\Controllers\Api\HelpCategoryController::class, 'index']);
            Route::post('categories', [App\Http\Controllers\Api\HelpCategoryController::class, 'store']);
            Route::get('categories/{id}', [App\Http\Controllers\Api\HelpCategoryController::class, 'show']);
            Route::put('categories/{id}', [App\Http\Controllers\Api\HelpCategoryController::class, 'update']);
            Route::delete('categories/{id}', [App\Http\Controllers\Api\HelpCategoryController::class, 'destroy']);

            // Help Subcategories
            Route::get('subcategories', [App\Http\Controllers\Api\HelpSubcategoryController::class, 'index']);
            Route::post('subcategories', [App\Http\Controllers\Api\HelpSubcategoryController::class, 'store']);
            Route::get('subcategories/{id}', [App\Http\Controllers\Api\HelpSubcategoryController::class, 'show']);
            Route::put('subcategories/{id}', [App\Http\Controllers\Api\HelpSubcategoryController::class, 'update']);
            Route::delete('subcategories/{id}', [App\Http\Controllers\Api\HelpSubcategoryController::class, 'destroy']);

            // Help Topics
            Route::get('topics', [App\Http\Controllers\Api\HelpTopicController::class, 'index']);
            Route::post('topics', [App\Http\Controllers\Api\HelpTopicController::class, 'store']);
            Route::get('topics/{id}', [App\Http\Controllers\Api\HelpTopicController::class, 'show']);
            Route::put('topics/{id}', [App\Http\Controllers\Api\HelpTopicController::class, 'update']);
            Route::delete('topics/{id}', [App\Http\Controllers\Api\HelpTopicController::class, 'destroy']);
            Route::post('topics/upload-image', [App\Http\Controllers\Api\HelpTopicController::class, 'uploadContentImage']);
        });

        // add middleware to check file size
        // Webinar Events management
        Route::group(['prefix' => 'webinars/events'], function () {
            Route::get('/', [App\Http\Controllers\Api\WebinarEventController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\WebinarEventController::class, 'store']);
            Route::get('/{slug}', [App\Http\Controllers\Api\WebinarEventController::class, 'show']);
            Route::post('/{slug}', [App\Http\Controllers\Api\WebinarEventController::class, 'update']);
            Route::delete('/{slug}', [App\Http\Controllers\Api\WebinarEventController::class, 'destroy']);
        });
        // Webinar Videos management
        Route::group(['prefix' => 'webinars/videos'], function () {
            Route::get('/', [App\Http\Controllers\Api\WebinarVideoController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\WebinarVideoController::class, 'store']);
            Route::get('/{slug}', [App\Http\Controllers\Api\WebinarVideoController::class, 'show']);
            Route::post('/{slug}', [App\Http\Controllers\Api\WebinarVideoController::class, 'update']);
            Route::delete('/{slug}', [App\Http\Controllers\Api\WebinarVideoController::class, 'destroy']);
        });

    });

});

// Public integration routes
Route::get('integrations', [IntegrationController::class, 'indexPublic']);

// Public plans listing
Route::get('plans', [PlanController::class, 'indexPublic']);

// Legal Documents

Route::get('legal-documents/privacy-policy', [LegalDocumentController::class, 'getPrivacyPolicy']);
Route::get('legal-documents/terms-of-service', [LegalDocumentController::class, 'getTermsOfService']);

// Public newsletter subscribe
Route::post('newsletter/subscribe', [NewsletterSubscriptionController::class, 'subscribe']);

// Public landing page blogs
Route::get('landing/blogs/active', [BlogController::class, 'activeBlogs']);
Route::get('landing/blogs', [BlogController::class, 'publicIndex']);
Route::get('landing/blogs/{blog}', [BlogController::class, 'show']);
Route::get('landing/blogs/recent/added', [BlogController::class, 'recentBlogs']);
Route::get('landing/blogs/{blog}/related', [BlogController::class, 'relatedBlogs']);

// Public request demo endpoint
Route::post('request-demo', [RequestDemoController::class, 'store']);

// Help Center Public APIs
Route::get('help-center', [App\Http\Controllers\Api\HelpCenterController::class, 'index']);
Route::get('help-center/category/{id}', [App\Http\Controllers\Api\HelpCenterController::class, 'getCategory']);
Route::get('help-center/subcategory/{id}', [App\Http\Controllers\Api\HelpCenterController::class, 'getSubcategory']);
Route::get('help-center/topic/{slug}', [App\Http\Controllers\Api\HelpCenterController::class, 'showTopic']);
Route::get('help-center/search', [App\Http\Controllers\Api\HelpCenterController::class, 'search']);


Route::get('/google-calendar/auth', function () {
    $client = new Google\Client();
    $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
    $client->addScope(Google\Service\Calendar::CALENDAR_EVENTS);
    $client->setRedirectUri('http://localhost:8000/api/google-calendar/oauth-callback');
    $client->setAccessType('offline');
    $client->setPrompt('consent'); // Ensures refresh token is returned

    $authUrl = $client->createAuthUrl();
    return redirect()->away($authUrl);
});

Route::get('/google-calendar/oauth-callback', function (Request $request) {
    $client = new Google\Client();
    $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
    $client->addScope(Google\Service\Calendar::CALENDAR_EVENTS);
    $client->setRedirectUri('http://localhost:8000/api/google-calendar/oauth-callback');

    try {
        if (!$request->has('code')) {
            throw new \Exception('Missing authorization code');
        }

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            Log::error('Google OAuth Error', $token);
            return response()->json(['error' => $token['error_description'] ?? 'Failed to get access token'], 400);
        }

        Storage::put('google-calendar/oauth-token.json', json_encode($token));

        return response()->json([
            'success' => true,
            'message' => 'Successfully authenticated!'
        ]);
    } catch (\Exception $e) {
        Log::error('Google Auth Exception: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Public Webinar APIs
Route::get('webinars/events', [App\Http\Controllers\Api\WebinarEventController::class, 'publicIndex']);
Route::get('webinars/events/{slug}', [App\Http\Controllers\Api\WebinarEventController::class, 'publicShow']);
Route::post('webinars/events/{slug}/register', [App\Http\Controllers\Api\WebinarEventRegistrationController::class, 'register']);
Route::get('webinars/videos', [App\Http\Controllers\Api\WebinarVideoController::class, 'publicIndex']);
Route::get('webinars/videos/{slug}', [App\Http\Controllers\Api\WebinarVideoController::class, 'publicShow']);
