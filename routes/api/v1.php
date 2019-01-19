<?php
/*
|--------------------------------------------------------------------------
| V1 API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for V1.
|
*/
Route::get('/manifest', function ()
{
    return [
        'servers' => [
            [
                'label' => 'v0.1',
                'url' => 'http://104.248.44.122/api/v1'
            ],
            [
                'label' => 'v0.2',
                'url' => 'http://167.99.193.195/api/v1'
            ],
            [
                'label' => 'Internal Testing 1',
                'url' => 'http://104.248.253.106/api/v1'
            ],
            [
                'label' => 'Internal Testing 2',
                'url' => 'http://167.99.93.110/api/v1'
            ]
        ]
    ];
});

Route::post('/login', 'LoginController@store');
Route::post('/register', 'RegisterController@store');

/**
 *  Business
 */
Route::get('/businesses/geo-json', 'BusinessesController@geoJson');
Route::get('/businesses/stats', 'BusinessesController@stats');
Route::post('/business-cover', 'BusinessCoverController@store');


Route::group(['middleware' => ['auth:api']], function ()
{
    Route::delete('/login', 'LoginController@destroy');

    /**
     * User
     */
    Route::patch('/users/{id}', 'UsersController@update');

    /**
     * User categories
     */
    Route::get('/user-categories', 'UserCategoriesController@index');
    Route::post('/user-categories', 'UserCategoriesController@store');

    /**
     * User businesses
     */
    Route::post('/user-businesses', 'UserBusinessesController@store');
    Route::delete('/user-businesses', 'UserBusinessesController@delete');

    /**
     * User businesses optional_attributes
     */
    Route::group(['prefix' => '/user-businesses/optional-attributes'], function () {
        Route::get('/', 'UserOptionalAttributesController@index');
        Route::post('/', 'UserOptionalAttributesController@store');
        Route::patch('/', 'UserOptionalAttributesController@update');
        Route::delete('/', 'UserOptionalAttributesController@delete');
    });

    /**
     * Business
     */
    Route::get('/businesses', 'BusinessesController@index');
    Route::post('/businesses', 'BusinessesController@store');
    Route::put('/businesses', 'BusinessesController@update');
    Route::delete('/businesses', 'BusinessesController@delete');

    Route::get('/businesses/{id}', 'BusinessesController@show');
    Route::get('/business-search', 'BusinessSearchController@index');
	Route::post('/businesses/{id}/avatar', 'BusinessesController@updateAvatar');
	Route::get('/businesses/{id}/avatar/delete', 'BusinessesController@deleteAvatar');
	Route::get('/top-categories', 'TopCategoriesSearchController@search');

    /**
     * Business Bio
     */
    Route::get('/business-bio/{id}', 'BusinessBioController@show');
    Route::patch('/business-bio', 'BusinessBioController@update');

    /**
     * Business Bio
     */
    Route::get('/business-bio/{id}', 'BusinessBioController@show');
    Route::patch('/business-bio', 'BusinessBioController@update');

    /**
     * Business Posts
     */
    Route::post('/business-posts', 'BusinessPostsController@store');
    Route::get('/business-posts', 'BusinessPostsController@index');
    Route::put('/business-posts', 'BusinessPostsController@update');
    Route::delete('/business-posts', 'BusinessPostsController@delete');
    Route::get('/business-posts/{id}', 'BusinessPostsController@show');
    Route::get('/active-business-posts', 'ActiveBusinessPostsController@index');
    Route::put('/business-hours/{id}', 'BusinessHoursController@updateOpenHours');

    /**
     * Business Reviews
     */
    Route::post('/business-reviews', 'BusinessReviewsController@store');

    /**
     * Business Feed
     */
    Route::get('/business-feed/{businessId}', 'BusinessFeedController@index');

    /**
     * User feed
     */
    Route::get('/user-feed', 'UserFeedController@index');

    /**
     * Images
     */
    Route::any('/face-detection', 'FaceDetectionController@index');

    /**
     * Explore
     */
    Route::get('/explore', 'ExploreController@index');

    /**
     *  Discover
     */
    Route::get('/discover', 'DiscoverController@index');

    /**
     *  Map Presets
     */
    Route::get('/map-presets', 'MapPresetsController@index');

    /**
     * Stickers
     */
    Route::get('/sticker-categories', 'StickerCategoriesController@index');
    Route::get('/stickers', 'StickersController@index');

    /**
     * Categories
     */
    Route::get('/categories', 'CategoriesController@index');
    Route::post('/categories', 'CategoriesController@store');
    Route::put('/categories', 'CategoriesController@update');
    Route::delete('/categories', 'CategoriesController@delete');

    /**
     * Ownership
     */
    Route::get('/ownership-methods/{businessId}', 'Ownership\MethodsController@index');
    Route::post('/ownership-requests/{businessId}', 'Ownership\RequestsController@store');
    Route::get('/ownership-requests/{businessId}', 'Ownership\RequestsController@index');
    Route::post('/confirm-ownership/{businessId}', 'Ownership\ConfirmController@index');

    /**
     * Feed
     */
    Route::get('/feed', 'FeedController@index');

    /**
     * Logged in user data
     */
    Route::get('/user', function (Illuminate\Http\Request $request)
    {
        return $request->user();
    });
});
