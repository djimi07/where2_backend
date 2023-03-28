<?php


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

Route::group(['prefix' => 'admin'], function () {

    Route::post('/login', 'Api\AdminController@login');
    Route::post('/forget-password', 'Api\AdminController@forget_password');
    Route::post('/update-reset-password', 'Api\AdminController@update_reset_password');
  
    Route::group(['middleware' => ['AdminAuth','auth:api']], function()
    {
        Route::get('/get-profile', 'Api\AdminController@get_profile');
        Route::get('/update-sidebar-toggle', 'Api\AdminController@update_sidebar_toggle');
        Route::post('/upload-image', 'Api\UserController@upload_image_into_temp');
        Route::post('/update-profile-image', 'Api\AdminController@update_admin_profile_image');
        Route::post('/update-profile', 'Api\AdminController@update_profile');
        Route::post('/change-password', 'Api\AdminController@change_password_api');
        Route::get('/logout', 'Api\AdminController@logout');
        Route::post('/add_or_update', 'Api\UserController@user_profile_update');
        Route::post('/users/search-api', 'Api\UserController@search_user_api');
        Route::post('/users/search-api-report', 'Api\UserController@search_user_api_report');
        Route::post('/delete_single_user', 'Api\UserController@delete_single_user');
        Route::get('/get_users_details/{id}', 'Api\UserController@get_users_details');
        Route::post('/update-cms-policy-api', 'Api\CmsController@update_policy_api');
        //user history
        Route::post('/User/history', 'Api\UserController@user_history');
        ///user friend list
        Route::post('/User/friend_list', 'Api\UserController@user_friend_list');
        

        Route::group(['prefix' => 'owner'], function ()
        {
            Route::post('/search-api-owner', 'Api\OwnerController@search_owner_api');
            Route::post('/search-api-user', 'Api\OwnerController@search_user_api');
            Route::post('/make_owner', 'Api\OwnerController@make_owner');
            Route::post('/owner_add_or_update', 'Api\OwnerController@owner_profile_update');
            Route::get('/get_single_owner/{id}', 'Api\OwnerController@get_single_owner');
            Route::post('/update_status', 'Api\OwnerController@update_status');
            Route::post('/delete', 'Api\OwnerController@Delete');
            Route::post('/view_bar_res', 'Api\OwnerController@view_bar_res');
            
  
        });

        Route::group(['prefix' => 'Bar-restaurant'], function ()
        {
            Route::post('/search-api-from-yelp', 'Api\Bar_RestaurantController@search_api_from_yelp');
            Route::post('/search-api', 'Api\Bar_RestaurantController@search_api');
            Route::post('/delete', 'Api\Bar_RestaurantController@Delete');
            Route::post('/add_or_update', 'Api\Bar_RestaurantController@add_or_update');
            Route::get('/edit/{id}', 'Api\Bar_RestaurantController@Edit');
            Route::post('/add_bar_restaurant', 'Api\Bar_RestaurantController@add_bar_restaurant');
            Route::post('/update_status', 'Api\Bar_RestaurantController@update_status');
            Route::post('/add_single_bar_restaurant', 'Api\Bar_RestaurantController@add_single_bar_restaurant');
            Route::post('/upload_image', 'Api\Bar_RestaurantController@upload_images');
            Route::post('/get_comment', 'Api\Bar_RestaurantController@get_comment');
            Route::post('/invited_user_list', 'Api\InviteController@get_invited_user_list');
            Route::post('/checkedinlist', 'Api\CheckedInController@checked_in_user_list');
            Route::get('/assign_owner/{id}', 'Api\Bar_RestaurantController@assign_owner');
            Route::post('/add_update_assign_owner', 'Api\Bar_RestaurantController@add_update_assign_owner');
            Route::post('/delete_img', 'Api\Bar_RestaurantController@delete_img');
            
        });
       
    });
});

       

Route::group([], function () {
    Route::post('/login', 'Api\UserController@user_login');
    Route::post('/signup', 'Api\UserController@user_signup');
    Route::post('/forgot_user_password', 'Api\UserController@forgot_user_password');
    Route::post('/reset_password', 'Api\UserController@Reset_Password');
    
    

    Route::group(['middleware' => 'auth:api'], function()
    {
        
         Route::post('/upload_user_image', 'Api\UserController@upload_user_image_with_id');
         Route::get('/logout', 'Api\UserController@logout');
         Route::get('/privacy_policy', 'Api\UserController@privacyPolicy');
         Route::post('/send_message', 'Api\UserController@send_message');
         Route::get('/get_images', 'Api\ImageController@Get_images');
         Route::post('/Add_or_Update', 'Api\ImageController@Add_or_Update');
         Route::post('/user_profile_update', 'Api\UserController@user_profile_update');
         Route::get('/get_user_info', 'Api\UserController@get_users_info_api');
         Route::get('/get_bar_rest', 'Api\Bar_RestaurantController@get_bar_rest');

         Route::post('/set_notification_token', 'Api\UserController@notification_token');
        // ......Get bar/Restaurant.....
         Route::post('/get_api_bar_restaurant', 'Api\Bar_RestaurantController@get_api_bar_restaurant');
         Route::post('/get_details', 'Api\Bar_RestaurantController@get_details_api');
         ////////Invitation
         Route::post('/invite_friend', 'Api\InviteController@invite_friend');
         Route::post('/send_request', 'Api\InviteController@Send_request');
         Route::post('/notification', 'Api\InviteController@notification');
         Route::post('/accept', 'Api\InviteController@Accept_invitation');
         Route::post('/decline', 'Api\InviteController@Decline');
         Route::post('/my_friends', 'Api\InviteController@Search_friend');
         Route::post('/send_friend_request', 'Api\InviteController@send_friends_request');
         
        /// checked in 
        Route::post('/checkedin', 'Api\CheckedInController@user_check_in');
        //Comment
        Route::post('/add_comment', 'Api\Bar_RestaurantController@Comment_api');
        
        Route::post('/my_friends_list', 'Api\InviteController@my_friends_list');
        Route::post('/remove_friend', 'Api\InviteController@remove_friend');

        
            
       
    });
});
