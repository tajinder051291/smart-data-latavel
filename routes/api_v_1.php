<?php

	Route::group(['prefix'=>'v1'], function(){

		Route::group(['prefix'=>'seller'], function(){
			Route::post('/upload/file','Api\v1\Seller\Auth\RegisterController@uploadFile');
			Route::post('/register','Api\v1\Seller\Auth\RegisterController@register');
			Route::post('/login','Api\v1\Seller\Auth\LoginController@login');
			Route::post('/password/otp','Api\v1\Seller\Auth\ResetPasswordController@sendResetOtp');
			Route::post('/password/verify/otp','Api\v1\Seller\Auth\ResetPasswordController@verifyOtp');
			Route::post('/password/change','Api\v1\Seller\Auth\ResetPasswordController@changePassword');

			Route::group(['middleware' => 'auth:seller'], function() {

				Route::post('/logout','Api\v1\Seller\Auth\LoginController@logout');

				Route::get('/profile','Api\v1\Seller\SellerController@getMyProfile');
				Route::post('/profile/edit','Api\v1\Seller\SellerController@editSellerProfile');


				Route::get('/orders/status/list','Api\v1\Seller\SellerController@allOrderStatusList');
				Route::get('/orders/all','Api\v1\Seller\MobilesController@allOrders');

				Route::get('/mobile/list','Api\v1\Seller\MobilesController@list');

				Route::post('/place/bid/model/types','Api\v1\Seller\MobilesController@getModelTypes');
				Route::post('/place/bid','Api\v1\Seller\MobilesController@placeBid');

				Route::get('/my/bids','Api\v1\Seller\MobilesController@myBids');

				Route::post('/edit/bid','Api\v1\Seller\MobilesController@editBid');
				Route::post('/orders/negotiate','Api\v1\Seller\MobilesController@negotiateOrder');


				Route::post('/bid/delete/attachment','Api\v1\Seller\MobilesController@deleteOrderAttachment');

				Route::post('/bid/reject','Api\v1\Seller\MobilesController@rejectOffer');
				Route::post('/bid/accept','Api\v1\Seller\MobilesController@acceptOffer');

				Route::post('/orders','Api\v1\Seller\MobilesController@listOrdersByStatus');
				Route::get('/orders/detail/','Api\v1\Seller\MobilesController@orderDetail');
				
				Route::post('/invoice/create','Api\v1\Seller\OrderInvoicesController@createInvoice');
				
				Route::post('/invoice/search','Api\v1\Seller\OrderInvoicesController@searchOrderInvoices');


				Route::get('/faqs','Api\v1\Seller\SellerController@getFaqs');
				Route::post('/feedback','Api\v1\Seller\SellerController@addFeedback');
				Route::post('/query/create','Api\v1\Seller\SellerController@createQuery');
				Route::post('/query/comment','Api\v1\Seller\SellerController@commentOnQuery');
				Route::get('/query/{id}','Api\v1\Seller\SellerController@getQueryDetails');
				Route::get('/list/queries','Api\v1\Seller\SellerController@listQueries');


				//settings
				Route::post('/settings/notification','Api\v1\Seller\SellerController@setNotifications');
				Route::post('/settings/change/password','Api\v1\Seller\Auth\ResetPasswordController@changeCurrentPassword');


			});

		});

		Route::group(['prefix'=>'user'], function(){
			
			Route::post('/upload/file','Api\v1\User\UserController@uploadFile');

			Route::post('/login','Api\v1\User\Auth\LoginController@login');
			Route::post('/password/otp','Api\v1\User\Auth\ResetPasswordController@sendResetOtp');
			Route::post('/password/verify/otp','Api\v1\User\Auth\ResetPasswordController@verifyOtp');
			Route::post('/password/change','Api\v1\User\Auth\ResetPasswordController@changePassword');

			Route::group(['middleware' => 'auth:user'], function() {

				Route::post('/logout','Api\v1\User\Auth\LoginController@logout');

				Route::get('/profile','Api\v1\User\UserController@getMyProfile');
				Route::post('/profile/edit','Api\v1\User\UserController@editUserProfile');

				Route::get('/orders/status/list','Api\v1\User\UserController@allOrderStatusList');

				//Manage Sellers
				Route::get('/seller/listing','Api\v1\User\UserController@listingSellers');
				Route::get('/states/list','Api\v1\User\UserController@allStatesList');
				Route::get('/seller/groups','Api\v1\User\UserController@listSellerGroups');
				Route::post('/seller/assign/group','Api\v1\User\UserController@assignGroupToSeller');
				Route::get('/sellers/list','Api\v1\User\UserController@listSellers');
				Route::post('/manage/seller/activate-deactivate','Api\v1\User\UserController@activateDeactivateSeller');
				Route::post('/manage/group/create','Api\v1\User\UserController@createSellersGroup');
				Route::post('/manage/group/edit','Api\v1\User\UserController@editSellersGroup');
				Route::post('/manage/seller/edit','Api\v1\User\UserController@editSeller');

				//Manage Teams
				Route::get('/team/roles/list','Api\v1\User\UserController@teamRolesList');
				Route::get('/list/users','Api\v1\User\UserController@listTeam'); //all users except DP
				Route::post('/manage/team/create','Api\v1\User\UserController@createTeam'); // create Team
				Route::post('/manage/team/edit','Api\v1\User\UserController@editTeam'); // edit Team
				Route::get('/manage/team/list','Api\v1\User\UserController@listTeams'); //list all teams
				Route::post('/manage/team/activate-deactivate','Api\v1\User\UserController@activateDeactivateTeamMember'); //activate/deactivate member
				Route::post('/manage/team/member/edit','Api\v1\User\UserController@editTeamMember'); //edit member
				
				// Manage Requirement
				Route::post('/manage/requirement','Api\v1\User\MobilesController@manageRequirement');
				Route::get('/model/list','Api\v1\User\MobilesController@listMobileModels');
				Route::get('/brand/model/list','Api\v1\User\MobilesController@list');
				Route::post('/update/model','Api\v1\User\MobilesController@updateModel');
				// end
				
				Route::post('/orders','Api\v1\User\OrdersController@listOrdersByStatus');
				Route::get('/orders/list','Api\v1\User\OrdersController@list');
				Route::get('/orders/warehouse','Api\v1\User\OrdersController@warehouseOrdersForManager');
				Route::get('/orders/detail/','Api\v1\User\OrdersController@orderDetail');
				Route::post('/orders/accept','Api\v1\User\OrdersController@acceptOrder');
				Route::post('/orders/negotiate','Api\v1\User\OrdersController@negotiateOrder');
				Route::get('/orders/all','Api\v1\User\OrdersController@allOrders');
				
				Route::post('/orders/negotiate/model/types','Api\v1\User\OrdersController@getModelTypes');
				
				
				Route::post('/orders/payment/update','Api\v1\User\OrdersController@updateInvoice');				
				Route::post('/orders/details/payment','Api\v1\User\OrdersController@orderPaymentInvoices');

				Route::get('/faqs','Api\v1\User\UserController@getFaqs');
				Route::post('/feedback','Api\v1\User\UserController@addFeedback');
				Route::post('/query/create','Api\v1\User\UserController@createQuery');
				Route::post('/query/comment','Api\v1\User\UserController@commentOnQuery');
				Route::get('/query/{id}','Api\v1\User\UserController@getQueryDetails');


				//Buyer routes
				Route::get('/bid/accepted/list/', 'Api\v1\User\OrdersController@acceptedOrdersList');
				Route::get('/partners/list/', 'Api\v1\User\UserController@listDeliveryPartners');
				Route::get('/logistic/list/', 'Api\v1\User\UserController@listLogisticUsers');
				Route::post('/pickup/add/', 'Api\v1\User\OrdersController@addPickup');
				Route::get('/pickup/assigned/', 'Api\v1\User\OrdersController@assignedPickups');

				//Logistic routes
				Route::get('/orders/logistic/aligned/', 'Api\v1\User\OrdersController@alignedLogisticOrders');
				Route::get('/orders/logistic/aligned/details', 'Api\v1\User\OrdersController@alignedLogisticOrderDetail');
				Route::post('/orders/logistic/confirm/stock', 'Api\v1\User\OrdersController@confirmStockLogistic');
				Route::post('/orders/logistic/deposit/stock', 'Api\v1\User\OrdersController@depositStockLogistic');
				// Route::post('/orders/logistic/dispatch/stock', 'Api\v1\User\OrdersController@dispatchStockLogistic');
				Route::post('/orders/logistic/status', 'Api\v1\User\OrdersController@lgOrdersByStatus');

				//DP routes
				Route::get('/orders/deliverypartner/aligned/', 'Api\v1\User\OrdersController@alignedDPOrders');
				Route::get('/orders/deliverypartner/aligned/details', 'Api\v1\User\OrdersController@alignedDPOrderDetail');
				Route::post('/orders/deliverypartner/confirm/stock', 'Api\v1\User\OrdersController@confirmStockDeliveryPartner');
				Route::post('/orders/deliverypartner/deposit/stock', 'Api\v1\User\OrdersController@depositStockDeliveryPartner');
				Route::post('/orders/deliverypartner/dispatch/stock', 'Api\v1\User\OrdersController@dispatchStock');
				Route::post('/orders/deliverypartner/status', 'Api\v1\User\OrdersController@dpOrdersByStatus');

				//Warehouse routes
				Route::get('/orders/warehouse/list/', 'Api\v1\User\OrdersController@listWarehouseOrders');
				Route::post('/orders/warehouse/accept/', 'Api\v1\User\OrdersController@acceptStock');
				Route::post('/orders/warehouse/hold/', 'Api\v1\User\OrdersController@holdStock');

				//settings
				Route::post('/settings/notification','Api\v1\User\UserController@setNotifications');
				Route::post('/settings/change/password','Api\v1\User\Auth\ResetPasswordController@changeCurrentPassword');


				//chat
				Route::post('/chat/send/', 'Api\v1\User\ChatController@sendMessage');				
				Route::get('/chat/details', 'Api\v1\User\ChatController@getChatDetails');
				Route::get('/chat/list', 'Api\v1\User\ChatController@listAllChats');
				Route::get('/chat/users/', 'Api\v1\User\ChatController@listUsers');
				Route::post('/chat/message/read', 'Api\v1\User\ChatController@setMessageAsRead');

			});

		});
		

	});
?>




