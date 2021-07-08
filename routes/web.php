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

// Route::get('/', function () {
//     return view('welcome');
// });


// Admin Login Section Routes

Route::get('/','Admin\LoginSignUp\LoginController@showLoginForm');
Route::post('/','Admin\LoginSignUp\LoginController@login');
Route::get('/forget-password','Admin\LoginSignUp\RegisterController@showRegisterForm');
Route::post('/signup','Admin\LoginSignUp\RegisterController@register');

Route::get('/forget-password','Admin\LoginSignUp\LoginController@forgetPasswordForm');
Route::post('/forgetpassword','Admin\LoginSignUp\LoginController@forgetpassword');
Route::post('/resetPassword','Admin\LoginSignUp\LoginController@resetpassword');
Route::get('/password/reset/{token}','Admin\LoginSignUp\LoginController@forgetPasswordForm');

Route::get('/terms',function(){
    return view('tnc');
});

Route::get('/privacy',function(){
    return view('privacy');
});

Auth::routes();
Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => ['auth:admin,manager'] ], function(){
        
        Route::get('dashboard','Admin\DashboardController@index');
        Route::get('logout','Admin\LoginSignUp\LoginController@logOut');

        /** App Permissions. **/

        Route::get('app-permissions','Admin\AppPermissionsController@appPermissionsList');
        Route::get('addAppPermissions','Admin\AppPermissionsController@addAppPermissionForm');
        Route::post('addAppPermission','Admin\AppPermissionsController@addAppPermission');
        Route::get('editAppPermission/{permissionId}','Admin\AppPermissionsController@editAppPermissionForm');
        
        Route::post('editAppPermission','Admin\AppPermissionsController@editAppPermission');
        Route::post('/deleteAppPermission','Admin\AppPermissionsController@deleteAppPermission');

        Route::post('/activeInActivePermissions','Admin\AppPermissionsController@activeInActivePermission');

        Route::post('/deleteSelectedPermissions','Admin\AppPermissionsController@deleteSelectedPermissions');

        

        /** User Role  **/

        Route::get('role-management','Admin\RoleController@roleList');
        Route::get('add-roles','Admin\RoleController@addRoleForm');
        Route::post('addRoles','Admin\RoleController@addRole');

        Route::get('editRoles/{roleId}','Admin\RoleController@editRoleForm');
        Route::post('editRole','Admin\RoleController@editRole');
        Route::post('/deleteRole','Admin\RoleController@deleteRole');
        Route::post('/activeInActiveRoles','Admin\RoleController@activeInActiveRoles');

        /** Mobile Brands. **/

        Route::get('mobile-brands','Admin\MobileBrandsController@mobileBrandList');
        Route::get('addMobileBrands','Admin\MobileBrandsController@addMobileBrandsForm');
        Route::post('addMobileBrands','Admin\MobileBrandsController@addMobileBrands');
        
        Route::get('editMobileBrands/{brandId}','Admin\MobileBrandsController@editMobileBrandsForm');
        
        Route::post('editMobileBrands','Admin\MobileBrandsController@editMobileBrands');
        
        Route::post('/deleteMobileBrand','Admin\MobileBrandsController@deleteMobileBrand');

        Route::post('/activeInActiveBrand','Admin\MobileBrandsController@activeInActiveBrand');

        /** Model Listing  **/

        Route::get('modellisting/{brandId}','Admin\MobileBrandsController@mobileModelList');
        Route::get('addMobileModels/{brandId}','Admin\MobileBrandsController@addMobileModelForm');
        Route::post('addMobileModels','Admin\MobileBrandsController@addMobileModels');
        Route::get('editMobileModels/{brandId}/{modelId}','Admin\MobileBrandsController@editMobileModelsForm');
        Route::post('editMobileModels','Admin\MobileBrandsController@editMobileModels');
        Route::post('/deleteMobileModel','Admin\MobileBrandsController@deleteMobileModel');
        Route::post('/activeInActiveModel','Admin\MobileBrandsController@activeInActiveModel');
        
        //Route::get('addRoles','Admin\RoleController@addAppPermissionForm');

        

        Route::get('delivery-partners','Admin\DeliveryPartnersController@deliveryPartners');
        Route::get('addDeliveryPartners','Admin\DeliveryPartnersController@addDeliveryPartnersForm');
        Route::post('addDeliveryPartners','Admin\DeliveryPartnersController@addDeliveryPartners');
        Route::get('editDeliveryPartners/{id}','Admin\DeliveryPartnersController@editDeliveryPartnersForm');
        Route::post('editDeliveryPartners','Admin\DeliveryPartnersController@editDeliveryPartners');
        Route::post('/deleteDeliveryPartner','Admin\DeliveryPartnersController@deleteDeliveryPartners');
        Route::post('/activeInActiveDeliveryPartner','Admin\DeliveryPartnersController@activeInActiveDeliveryPartners');


        Route::get('invoices','Admin\OrderInvoicesController@invoicesList');
        Route::get('invoice/payment/update/{id}','Admin\OrderInvoicesController@showInvoicePayment');
        Route::post('invoice/payment/update/{id}','Admin\OrderInvoicesController@updateInvoicePayment');


        Route::get('sellers/{brandId}','Admin\SellerController@sellersList');
        Route::get('addSellers','Admin\SellerController@addSellerForm');
        Route::post('addSellers','Admin\SellerController@addSellers');
        Route::post('/activeInActiveSeller','Admin\SellerController@activeInActiveSeller');
        Route::post('/deleteSeller','Admin\SellerController@deleteSeller');
        Route::get('seller/export/{id}', 'Admin\SellerController@exportSeller');


        Route::get('/sellerInvoices/{id}','Admin\SellerController@invoicesList');

        Route::get('editSeller/{id}','Admin\SellerController@editSellersForm');
        Route::post('editSeller','Admin\SellerController@editSellers');
        Route::post('/verifySeller','Admin\SellerController@verifySeller');


        Route::get('faq','Admin\FaqController@faqList');
        Route::get('addfaq','Admin\FaqController@addFaqForm');
        Route::post('addFaq','Admin\FaqController@addFaq');
        Route::post('/activeInActiveFaq','Admin\FaqController@activeInActiveFaq');
        
        Route::get('editFaq/{id}','Admin\FaqController@editFaqForm');
        Route::post('editFaq','Admin\FaqController@editFaq');
        
        Route::get('feedback/list','Admin\FeedbackController@list');

        Route::get('query/list/{id}','Admin\TicketsController@list');
        Route::get('query/details/{id}','Admin\TicketsController@queryDetails');
        Route::post('query/comment/{id}','Admin\TicketsController@addComment');
        Route::get('query/close/{id}','Admin\TicketsController@closeQuery');
        Route::post('query/message','Admin\TicketsController@commentOnQuery');
        

    /*    

        Route::get('editDeliveryPartners/{id}','Admin\DeliveryPartnersController@editDeliveryPartnersForm');
        Route::post('editDeliveryPartners','Admin\DeliveryPartnersController@editDeliveryPartners');
        Route::post('/deleteSeller','Admin\DeliveryPartnersController@deleteDeliveryPartners');
        Route::post('/activeInActiveDeliveryPartner','Admin\DeliveryPartnersController@activeInActiveDeliveryPartners'); */

        

        /**  User Module  **/

        Route::get('users','Admin\UsersController@usersList');
        Route::get('editUser/{userId}','Admin\UsersController@editUserForm');
        Route::post('editUser','Admin\UsersController@editUser');
        Route::get('addUser','Admin\UsersController@addUserForm');
        Route::post('addUser','Admin\UsersController@addUser');
        Route::post('/activeInActiveUser','Admin\UsersController@activeInActiveUser');
        Route::post('/deleteUser','Admin\UsersController@deleteUser');        

    });

/*    Route::group(['middleware' => ['auth:manager']], function(){
    
        Route::get('dashboard','Admin\DashboardController@index');
        Route::get('logout','Admin\LoginSignUp\LoginController@logOutManager');

        Route::get('delivery-partners','Admin\DeliveryPartnersController@deliveryPartners');
        Route::get('addDeliveryPartners','Admin\DeliveryPartnersController@addDeliveryPartnersForm');
        Route::post('addDeliveryPartners','Admin\DeliveryPartnersController@addDeliveryPartners');
        Route::get('editDeliveryPartners/{id}','Admin\DeliveryPartnersController@editDeliveryPartnersForm');
        Route::post('editDeliveryPartners','Admin\DeliveryPartnersController@editDeliveryPartners');
        Route::post('/deleteDeliveryPartner','Admin\DeliveryPartnersController@deleteDeliveryPartners');
        Route::post('/activeInActiveDeliveryPartner','Admin\DeliveryPartnersController@activeInActiveDeliveryPartners');

        Route::get('sellers/{brandId}','Admin\SellerController@sellersList');
        Route::get('addSellers','Admin\SellerController@addSellerForm');
        Route::post('addSellers','Admin\SellerController@addSellers');
        Route::post('/activeInActiveSeller','Admin\SellerController@activeInActiveSeller');
        Route::post('/deleteSeller','Admin\SellerController@deleteSeller');

        Route::get('editSeller/{id}','Admin\SellerController@editSellersForm');
        Route::post('editSeller','Admin\SellerController@editSellers');
        Route::post('/verifySeller','Admin\SellerController@verifySeller');
        
    });  */

});

Route::get('/home', 'HomeController@index')->name('home');
