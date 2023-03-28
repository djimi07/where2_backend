<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// privacy policy
Route::get('/getBars', 'Api\Bar_RestaurantController@getBars');
/*....... ADMIN ROUTE GROUP ........*/
Route::group(['prefix' => 'admin'], function ()
{

    Route::group(['middleware' => 'AdminViewUnAuth'], function ()
    {
        Route::get('/', 'AdminViewController@login_view');
        Route::post('/set-auth-session', 'AdminViewController@set_auth_session');
        Route::get('/forget-password', 'AdminViewController@forget_password_view');
        Route::get('/password-reset', 'AdminViewController@password_reset_view');
        

    });

    Route::group(['middleware' => 'AdminViewAuth'], function ()
    {
        Route::get('/unset-auth-session', 'AdminViewController@unset_auth_session');
        Route::get('/dashboard', 'AdminViewController@dashboard_view');
        Route::get('/profile', 'AdminViewController@profile_view');
        Route::get('/change-password', 'AdminViewController@change_password_view');
        Route::get('/user_management', 'AdminViewController@users_list_view');
        Route::get('/owner_management', 'AdminViewController@owner_management');
        Route::get('/add_bar_restaurant', 'AdminViewController@add_bar_restaurant');
        Route::get('/view_bar_restaurant', 'AdminViewController@view_bar_restaurant');
        Route::get('/privacy_policy', 'AdminViewController@privacy_policy');
        
        // Route::get('/terms_condition', 'AdminViewController@terms_condition');
    });
});

    Route::group(['prefix' => 'owner'], function ()
    {
    
    Route::group(['middleware' => 'UserViewUnAuth'], function ()
    {
        Route::get('/', 'OwnerViewController@login_view');
        Route::get('/forget-password', 'OwnerViewController@forget_password_view');
        Route::get('/password-reset', 'OwnerViewController@password_reset_view');
        Route::post('/login', 'Api\OwnerController@login');
        Route::post('/forget-password', 'Api\OwnerController@forget_password');
        Route::post('/update-reset-password', 'Api\OwnerController@update_reset_password');

    });

    Route::group(['middleware' => 'OwnerViewAuth'], function ()
    {
        Route::get('/unset-auth-session', 'OwnerViewController@unset_auth_session');
        Route::get('/dashboard', 'OwnerViewController@dashboard_view');
        Route::get('/profile', 'OwnerViewController@profile_view');
        Route::get('/change-password', 'OwnerViewController@change_password_view');
        Route::get('/logout', 'OwnerViewController@unset_auth_session');
        Route::post('/update_profile', 'Api\OwnerController@update_profile');
        Route::post('/change-password', 'Api\OwnerController@change_password_api');
        Route::post('/update-profile-image', 'Api\OwnerController@update_owner_profile_image');
        Route::get('/bar_restaurant', 'OwnerViewController@list_bar_restaurant');
        Route::get('/event_page/{id}', 'OwnerViewController@event_page');
       
        Route::group(['prefix' => 'Bar-restaurant'], function ()
        {
            Route::post('/owner-search-api', 'Api\Bar_RestaurantController@owner_search_api');
            Route::post('/get_comment', 'Api\Bar_RestaurantController@get_comment');
            Route::post('/invited_user_list', 'Api\InviteController@get_invited_user_list');
            Route::post('/checkedinlist', 'Api\CheckedInController@checked_in_user_list');
            Route::get('/edit_basic_details/{id}', 'Api\Bar_RestaurantController@edit_basic_details');
            Route::post('/update_bar_res', 'Api\Bar_RestaurantController@update_bar_res');
            Route::post('/upload_image', 'Api\Bar_RestaurantController@upload_images');
            Route::post('/delete_img', 'Api\Bar_RestaurantController@delete_img');
            ///Add Event
           
        });
        Route::group(['prefix' => 'event'], function ()
        {
            Route::post('/add_update_event', 'Api\EventController@add_update_event');
            Route::post('/get_all_event', 'Api\EventController@get_event');
            Route::get('/edit_event/{id}', 'Api\EventController@Edit');
            Route::post('/delete_event', 'Api\EventController@delete');
            Route::post('/update_status', 'Api\EventController@update_status');
            Route::post('/upload_image', 'Api\EventController@upload_image');
        });

    });
});



Route::get('/cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    dd(Artisan::output());
});

Route::get('/vender', function () {
    Artisan::call('composer:update'); 
    dd(Artisan::output());
});

Route::get("/privacy-policy", "Api\AdminController@privacy_policy_api");
Route::get('/getBars/{id}', 'Api\Bar_RestaurantController@getBars');
Route::get('clearcache',function(){
    Session::put('AuthAdminWebToken', '');
    return redirect('/admin');     
});