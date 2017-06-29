<?php



	/*
	|--------------------------------------------------------------------------
	| Browser Sniffing
	|--------------------------------------------------------------------------
	*/

	//Browser Update Page
	Route::get('/browser',function(){
		return view('browser');
	});


	Route::group([ 'middleware' => 'browser' ],function(){ 




		/*
		|--------------------------------------------------------------------------
		| Application Routes
		|--------------------------------------------------------------------------
		*/

		//Dashboard Page
		Route::get('/{all}',['middleware' => 'auth', 'as' => 'dashboard', 'uses' => 'DashboardController@index' ])->where([ 'all' => '(?!api)(?!auth)(?!password).*' ]);

		//Login
		Route::get('auth/login', 'Auth\AuthController@getLogin');
		Route::post('auth/login', 'Auth\AuthController@postLogin');
		Route::get('auth/logout', 'Auth\AuthController@getLogout');

		//Forgot Email
		Route::get('password/email', 'Auth\PasswordController@getEmail');
		Route::post('password/email', 'Auth\PasswordController@postEmail');

		//Reset Password
		Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
		Route::post('password/reset', 'Auth\PasswordController@postReset');






		/*
		|--------------------------------------------------------------------------
		| API Routes
		|--------------------------------------------------------------------------
		*/


		//Patterns
		Route::pattern('userid',			'[0-9]+');
		Route::pattern('roleid',			'[0-9]+');
		Route::pattern('storeid',			'[0-9]+');
		Route::pattern('newsid',			'[0-9]+');
		Route::pattern('eventid',			'[0-9]+');
		Route::pattern('notificationid',	'[0-9]+');
		Route::pattern('recipeid', 			'[0-9]+');
		Route::pattern('promoid', 			'[0-9]+');
		Route::pattern('reportid', 			'[0-9]+');
		Route::pattern('logoutid', 			'[0-9]+');
		Route::pattern('payrollid', 		'[0-9]+');
		Route::pattern('sickdayid', 		'[0-9]+');
		Route::pattern('doctorid', 			'[0-9]+');
		Route::pattern('techid', 			'[0-9]+');
		Route::pattern('page',				'[0-9]+');
		Route::pattern('limit',				'[0-9]{1,100}+');

		//API Version 1
		Route::group([ 'prefix' => '/api/v1' , 'middleware' => 'auth' ], function(){

			//Page Setup - GET
				Route::get( 	'layout/navigation', 										'Api\v1\LayoutController@getNavigation' );
				Route::get( 	'layout/breadcrumbs', 										'Api\v1\LayoutController@getBreadcrumbs' );
				Route::get( 	'layout/{category}/{file}', 								'Api\v1\LayoutController@getLayout' );
				Route::get( 	'layout/controller', 										'Api\v1\LayoutController@getController' );

			//General Access - Stores
				Route::get( 	'stores', 													'Api\v1\StoreController@getAllStores' );
				Route::get( 	'store/{storeid}/users', 									'Api\v1\StoreController@getStoreUsers' );

			//General Access - Users
				Route::get( 	'user/{userid?}', 											'Api\v1\UserController@getUser' );
				Route::get( 	'user/roles/{userid?}', 									'Api\v1\UserController@getRoles' );
				Route::get( 	'user/stores/{userid?}', 									'Api\v1\UserController@getStores' );
				Route::get( 	'user/permissions/{userid?}', 								'Api\v1\UserController@getPermissions' );
				Route::get( 	'user/permissions/custom/{userid?}', 						'Api\v1\UserController@getCustomPermissions' );
				Route::get( 	'user/exists', 												'Api\v1\UserController@findUser' );

			//General Access - Roles
				Route::get( 	'roles/{limit?}/{page?}', 									'Api\v1\RoleController@getAllRoles' );
				Route::get( 	'role/permissions',		 									'Api\v1\RoleController@getAllPermissions' );
				Route::get( 	'role/exists', 												'Api\v1\RoleController@findRole' );
				Route::get( 	'role/{roleid}', 											'Api\v1\RoleController@getRole' );
				Route::get( 	'role/{roleid}/permissions',	 							'Api\v1\RoleController@getRolePermissions' );

			//General Access - Stores
				Route::get( 	'stores/{limit}/{page}', 									'Api\v1\StoreController@getAllStores' );
				Route::get( 	'store/{storeid}', 											'Api\v1\StoreController@getStore' );
				Route::get( 	'store/exists', 											'Api\v1\StoreController@findStore' );
				
				
			//General Access - Profile
				Route::post( 	'profile', 													'Api\v1\ProfileController@editProfile' );
			
			//Notifications
				Route::get( 	'user/notifications/{limit}/{notificationid?}', 			'Api\v1\NotificationController@getNotifications' );
				Route::post( 	'user/notifications/{notificationid}', 						'Api\v1\NotificationController@readNotification' );
				Route::get( 	'user/notifications/unread',							 	'Api\v1\NotificationController@getUnreadTotal' );
				Route::get( 	'user/notifications/poll/{notificationid?}', 				'Api\v1\NotificationController@getNewNotifications' );

			//General Access - Files
				Route::get( 	'download/{slug}', 											'Api\v1\AttachmentController@serveFile' );


			//General Access - News
				Route::get( 	'user/news/{limit}/{newsid?}', 								'Api\v1\NewsController@getUserArticles' );

			//General Access - Events
				Route::get( 	'user/events',												'Api\v1\EventController@getUserEvents' );

			//General Access - Events
				Route::get( 	'search/{limit}/{page?}/{query?}',							'Api\v1\SearchController@getSearch' );

			//Users
				Route::get( 	'users/{limit?}/{page?}', 			[ 'middleware' => 'permission:users' , 																'uses' => 'Api\v1\UserController@getAllUsers' ] );
				Route::put( 	'user', 							[ 'middleware' => 'permission:users.create' , 														'uses' => 'Api\v1\UserController@addUser' ] );
				Route::post( 	'user/{userid}', 					[ 'middleware' => 'permission:users.edit' , 														'uses' => 'Api\v1\UserController@editUser' ] );
				Route::delete( 	'user/{userid}', 					[ 'middleware' => 'permission:users.delete' , 														'uses' => 'Api\v1\UserController@deleteUser' ] );
				
			//Roles				
				Route::put( 	'role', 							[ 'middleware' => 'permission:roles.create' , 														'uses' => 'Api\v1\RoleController@addRole' ] );
				Route::post( 	'role/{roleid}',					[ 'middleware' => 'permission:roles.edit' , 														'uses' => 'Api\v1\RoleController@editRole' ] );
				Route::delete( 	'role/{roleid}',					[ 'middleware' => 'permission:roles.delete' , 														'uses' => 'Api\v1\RoleController@deleteRole' ] );
				
			//Stores				
				Route::put(	 	'store', 							[ 'middleware' => 'permission:stores.create' , 														'uses' => 'Api\v1\StoreController@addStore' ] );
				Route::post( 	'store/{storeid}',					[ 'middleware' => 'permission:stores.edit' , 														'uses' => 'Api\v1\StoreController@editStore' ] );
				Route::delete( 	'store/{storeid}',					[ 'middleware' => 'permission:stores.delete' , 														'uses' => 'Api\v1\StoreController@deleteStore' ] );
				
			//News				
				Route::get( 	'news/{limit}/{page}', 				[ 'middleware' => 'permission:news' , 																'uses' => 'Api\v1\NewsController@getAllArticles' ] );
				Route::get( 	'news/{newsid}', 					[ 'middleware' => 'permission:news.view' , 															'uses' => 'Api\v1\NewsController@getArticle' ] );
				Route::post( 	'news/attach', 						[ 'middleware' => 'permission:news.create|permission:news.edit' ,									'uses' => 'Api\v1\NewsController@attach' ] );
				Route::put( 	'news', 							[ 'middleware' => 'permission:news.create' , 														'uses' => 'Api\v1\NewsController@addArticle' ] );
				Route::post( 	'news/{newsid}', 					[ 'middleware' => 'permission:news.edit' , 															'uses' => 'Api\v1\NewsController@editArticle' ] );
				Route::delete( 	'news/{newsid}', 					[ 'middleware' => 'permission:news.delete' , 														'uses' => 'Api\v1\NewsController@deleteArticle' ] );
										
			//Events				
				Route::get( 	'events/{limit}/{page}',	 		[ 'middleware' => 'permission:events' , 															'uses' => 'Api\v1\EventController@getAllEvents' ] );
				Route::get( 	'events/{eventid}', 				[ 'middleware' => 'permission:events.view' , 														'uses' => 'Api\v1\EventController@getEvent' ] );
				Route::post( 	'events/attach', 					[ 'middleware' => 'permission:events.create|permission:events.edit' ,								'uses' => 'Api\v1\EventController@attach' ] );
				Route::put( 	'events', 							[ 'middleware' => 'permission:events.create' , 														'uses' => 'Api\v1\EventController@addEvent' ] );
				Route::post( 	'events/{eventid}',					[ 'middleware' => 'permission:events.edit' , 														'uses' => 'Api\v1\EventController@editEvent' ] );
				Route::delete( 	'events/{eventid}',					[ 'middleware' => 'permission:events.delete' , 														'uses' => 'Api\v1\EventController@deleteEvent' ] );
				
			//Menu				
				Route::get( 	'menu/{limit}/{page}',	 			[ 'middleware' => 'permission:menu' , 																'uses' => 'Api\v1\MenuController@getAllRecipes' ] );
				Route::get( 	'menu/{recipeid}', 					[ 'middleware' => 'permission:menu.view' , 															'uses' => 'Api\v1\MenuController@getRecipe' ] );
				Route::post( 	'menu/attach', 						[ 'middleware' => 'permission:menu.create|permission:menu.edit' , 									'uses' => 'Api\v1\MenuController@attach' ] );
				Route::put( 	'menu', 							[ 'middleware' => 'permission:menu.create' , 														'uses' => 'Api\v1\MenuController@addRecipe' ] );
				Route::post( 	'menu/{recipeid}',					[ 'middleware' => 'permission:menu.edit' , 															'uses' => 'Api\v1\MenuController@editRecipe' ] );
				Route::delete( 	'menu/{recipeid}',					[ 'middleware' => 'permission:menu.delete' , 														'uses' => 'Api\v1\MenuController@deleteRecipe' ] );
				Route::post( 	'menu/feedback/{recipeid}',			[ 'middleware' => 'permission:menu.view' , 															'uses' => 'Api\v1\MenuController@sendFeedback' ] );
							
			//Bar				
				Route::get( 	'bar/{limit}/{page}',	 			[ 'middleware' => 'permission:menu' , 																'uses' => 'Api\v1\BarController@getAllRecipes' ] );
				Route::get( 	'bar/{recipeid}', 					[ 'middleware' => 'permission:menu.view' , 															'uses' => 'Api\v1\BarController@getRecipe' ] );
				Route::post( 	'bar/attach', 						[ 'middleware' => 'permission:menu.create|permission:menu.edit' , 									'uses' => 'Api\v1\BarController@attach' ] );
				Route::put( 	'bar', 								[ 'middleware' => 'permission:menu.create' , 														'uses' => 'Api\v1\BarController@addRecipe' ] );
				Route::post( 	'bar/{recipeid}',					[ 'middleware' => 'permission:menu.edit' , 															'uses' => 'Api\v1\BarController@editRecipe' ] );
				Route::delete( 	'bar/{recipeid}',					[ 'middleware' => 'permission:menu.delete' , 														'uses' => 'Api\v1\BarController@deleteRecipe' ] );
				Route::post( 	'bar/feedback/{recipeid}',			[ 'middleware' => 'permission:menu.view' , 															'uses' => 'Api\v1\BarController@sendFeedback' ] );
							
			//Marketing & Promotions				
				Route::get( 	'promos/{limit}/{page}',			[ 'middleware' => 'permission:promos' , 															'uses' => 'Api\v1\PromoController@getAllPromos' ] );
				Route::get( 	'promos/{promoid}', 				[ 'middleware' => 'permission:promos.view' , 														'uses' => 'Api\v1\PromoController@getPromo' ] );
				Route::post( 	'promos/attach', 					[ 'middleware' => 'permission:promos.create|permission:promos.edit' , 								'uses' => 'Api\v1\PromoController@attach' ] );
				Route::put( 	'promos', 							[ 'middleware' => 'permission:promos.create' , 														'uses' => 'Api\v1\PromoController@addPromo' ] );
				Route::post( 	'promos/{promoid}',					[ 'middleware' => 'permission:promos.edit' , 														'uses' => 'Api\v1\PromoController@editPromo' ] );
				Route::delete( 	'promos/{promoid}',					[ 'middleware' => 'permission:promos.delete' , 														'uses' => 'Api\v1\PromoController@deletePromo' ] );
				Route::post( 	'promos/feedback/{promoid}',		[ 'middleware' => 'permission:promos.view' , 														'uses' => 'Api\v1\PromoController@sendFeedback' ] );
										
			//Marketing & Promotions				
				Route::get( 	'documents/{limit}/{page}',			[ 'middleware' => 'permission:documents' , 															'uses' => 'Api\v1\DocumentController@getPagedFiles' ] );
				Route::get( 	'documents',						[ 'middleware' => 'permission:documents' , 															'uses' => 'Api\v1\DocumentController@getAllFiles' ] );
				Route::post( 	'documents',						[ 'middleware' => 'permission:documents.manage' , 													'uses' => 'Api\v1\DocumentController@manageFiles' ] );
				Route::post( 	'documents/attach', 				[ 'middleware' => 'permission:documents.manage' , 													'uses' => 'Api\v1\DocumentController@attach' ] );
								
			//Document Folders
				Route::get( 	'folders/{limit?}/{page?}', 		[ 'middleware' => 'permission:folders' , 											'uses' => 'Api\v1\FolderController@getAllFolders' ] );
				Route::get( 	'folder/exists', 					[ 'middleware' => 'permission:folders.create|permission:folders.edit' , 			'uses' => 'Api\v1\FolderController@findFolder' ] );
				Route::get( 	'folder/{folderid}', 				[ 'middleware' => 'permission:folders.edit' , 										'uses' => 'Api\v1\FolderController@getFolder' ] );
				Route::put( 	'folder', 							[ 'middleware' => 'permission:folders.create' , 									'uses' => 'Api\v1\FolderController@addFolder' ] );
				Route::post( 	'folder/{folderid}',				[ 'middleware' => 'permission:folders.edit' , 										'uses' => 'Api\v1\FolderController@editFolder' ] );
				Route::delete( 	'folder/{folderid}',				[ 'middleware' => 'permission:folders.delete' , 									'uses' => 'Api\v1\FolderController@deleteFolder' ] );
				
			//Weekly Reports				
				Route::get( 	'reports/{limit}/{page}',			[ 'middleware' => 'permission:reports' , 															'uses' => 'Api\v1\ReportController@getAllReports' ] );
				Route::get( 	'reports/last', 					[ 'middleware' => 'permission:reports.view' , 														'uses' => 'Api\v1\ReportController@getLastReport' ] );
				Route::get( 	'reports/{reportid}', 				[ 'middleware' => 'permission:reports.view' , 														'uses' => 'Api\v1\ReportController@getReport' ] );
				Route::post( 	'reports/attach', 					[ 'middleware' => 'permission:reports.create|permission:reports.edit' , 							'uses' => 'Api\v1\ReportController@attach' ] );
				Route::put( 	'reports', 							[ 'middleware' => 'permission:reports.create' , 													'uses' => 'Api\v1\ReportController@addReport' ] );
				Route::post( 	'reports/{reportid}',				[ 'middleware' => 'permission:reports.edit' , 														'uses' => 'Api\v1\ReportController@editReport' ] );
				Route::delete( 	'reports/{reportid}',				[ 'middleware' => 'permission:reports.delete' , 													'uses' => 'Api\v1\ReportController@deleteReport' ] );
												
			//Daily Logouts				
				Route::get( 	'logouts/{limit}/{page}',			[ 'middleware' => 'permission:logouts' , 															'uses' => 'Api\v1\LogoutController@getAllLogouts' ] );
				Route::get( 	'logouts/{logoutid}', 				[ 'middleware' => 'permission:logouts.view' , 														'uses' => 'Api\v1\LogoutController@getLogout' ] );
				Route::get( 	'logouts/report', 					[ 'middleware' => 'permission:logouts.report' , 													'uses' => 'Api\v1\LogoutController@getLogoutReport' ] );
				Route::get( 	'logouts/active/{storeid}', 		[ 'middleware' => 'permission:logouts.create' , 													'uses' => 'Api\v1\LogoutController@getStoreLogout' ] );
				Route::put( 	'logouts', 							[ 'middleware' => 'permission:logouts.create' , 													'uses' => 'Api\v1\LogoutController@sendLogout' ] );
				Route::put( 	'logouts/save', 					[ 'middleware' => 'permission:logouts.create' , 													'uses' => 'Api\v1\LogoutController@saveLogout' ] );
				Route::post( 	'logouts/{logoutid}',				[ 'middleware' => 'permission:logouts.edit' , 														'uses' => 'Api\v1\LogoutController@editLogout' ] );
				Route::delete( 	'logouts/{logoutid}',				[ 'middleware' => 'permission:logouts.delete' , 													'uses' => 'Api\v1\LogoutController@deleteLogout' ] );
				
			//Buyer Logouts				
				Route::get( 	'buyerslogout/{limit}/{page}',		[ 'middleware' => 'permission:buyerslogouts' , 														'uses' => 'Api\v1\BuyersLogoutController@getAllLogouts' ] );
				Route::get( 	'buyerslogout/{logoutid}', 			[ 'middleware' => 'permission:buyerslogouts.view' , 												'uses' => 'Api\v1\BuyersLogoutController@getLogout' ] );
				Route::get( 	'buyerslogout/report', 				[ 'middleware' => 'permission:buyerslogouts.report' , 												'uses' => 'Api\v1\BuyersLogoutController@getLogoutReport' ] );
				Route::get( 	'buyerslogout/active/{storeid}', 	[ 'middleware' => 'permission:buyerslogouts.create' , 												'uses' => 'Api\v1\BuyersLogoutController@getStoreLogout' ] );
				Route::put( 	'buyerslogout', 					[ 'middleware' => 'permission:buyerslogouts.create' , 												'uses' => 'Api\v1\BuyersLogoutController@sendLogout' ] );
				Route::put( 	'buyerslogout/save', 				[ 'middleware' => 'permission:buyerslogouts.create' , 												'uses' => 'Api\v1\BuyersLogoutController@saveLogout' ] );
				Route::post( 	'buyerslogout/{logoutid}',			[ 'middleware' => 'permission:buyerslogouts.edit' , 												'uses' => 'Api\v1\BuyersLogoutController@editLogout' ] );
				Route::delete( 	'buyerslogout/{logoutid}',			[ 'middleware' => 'permission:buyerslogouts.delete' , 												'uses' => 'Api\v1\BuyersLogoutController@deleteLogout' ] );
							
			//Promotions				
				Route::get( 	'promotions/{limit?}/{page?}', 		[ 'middleware' => 'permission:promotions' , 														'uses' => 'Api\v1\PromotionController@getAllPromotions' ] );
				Route::get( 	'promotion/exists', 				[ 'middleware' => 'permission:promotions.create|permission:promotions.edit' , 						'uses' => 'Api\v1\PromotionController@findPromotion' ] );
				Route::get( 	'promotion/{promotionid}', 			[ 'middleware' => 'permission:promotions.edit' , 													'uses' => 'Api\v1\PromotionController@getPromotion' ] );
				Route::put( 	'promotion', 						[ 'middleware' => 'permission:promotions.create' , 													'uses' => 'Api\v1\PromotionController@addPromotion' ] );
				Route::post( 	'promotion/{promotionid}',			[ 'middleware' => 'permission:promotions.edit' , 													'uses' => 'Api\v1\PromotionController@editPromotion' ] );
				Route::delete( 	'promotion/{promotionid}',			[ 'middleware' => 'permission:promotions.delete' , 													'uses' => 'Api\v1\PromotionController@deletePromotion' ] );			
				
			//Positions				
				Route::get( 	'positions/{limit?}/{page?}', 		[ 'middleware' => 'permission:positions' , 															'uses' => 'Api\v1\PositionController@getAllPositions' ] );
				Route::get( 	'position/exists', 					[ 'middleware' => 'permission:positions.create|permission:positions.edit' , 						'uses' => 'Api\v1\PositionController@findPosition' ] );
				Route::get( 	'position/{positionid}', 			[ 'middleware' => 'permission:positions.edit' , 													'uses' => 'Api\v1\PositionController@getPosition' ] );
				Route::put( 	'position', 						[ 'middleware' => 'permission:positions.create' , 													'uses' => 'Api\v1\PositionController@addPosition' ] );
				Route::post( 	'position/{positionid}',			[ 'middleware' => 'permission:positions.edit' , 													'uses' => 'Api\v1\PositionController@editPosition' ] );
				Route::delete( 	'position/{positionid}',			[ 'middleware' => 'permission:positions.delete' , 													'uses' => 'Api\v1\PositionController@deletePosition' ] );
								
			//Payrolls				
				Route::get( 	'payrolls/{limit}/{page}',			[ 'middleware' => 'permission:payrolls' , 															'uses' => 'Api\v1\PayrollController@getAllPayrolls' ] );
				Route::get( 	'payrolls/{payrollid}', 			[ 'middleware' => 'permission:payrolls.view' , 														'uses' => 'Api\v1\PayrollController@getPayroll' ] );
				Route::get( 	'payrolls/download/{payrollid}', 	[ 'middleware' => 'permission:payrolls.view' , 														'uses' => 'Api\v1\PayrollController@downloadPayroll' ] );
				Route::get( 	'payrolls/active/{storeid}', 		[ 'middleware' => 'permission:payrolls.create' , 													'uses' => 'Api\v1\PayrollController@getStorePayroll' ] );
				Route::put( 	'payrolls', 						[ 'middleware' => 'permission:payrolls.create' , 													'uses' => 'Api\v1\PayrollController@addPayroll' ] );
				Route::put( 	'payrolls/save', 					[ 'middleware' => 'permission:payrolls.create' , 													'uses' => 'Api\v1\PayrollController@savePayroll' ] );
				Route::post( 	'payrolls/{payrollid}',				[ 'middleware' => 'permission:payrolls.edit' , 														'uses' => 'Api\v1\PayrollController@editPayroll' ] );
				Route::delete( 	'payrolls/{payrollid}',				[ 'middleware' => 'permission:payrolls.delete' , 													'uses' => 'Api\v1\PayrollController@deletePayroll' ] );			
				
			//Sick Days				
				Route::get( 	'sickdays/{limit?}/{page?}', 		[ 'middleware' => 'permission:sickdays' , 															'uses' => 'Api\v1\SickDayController@getAllSickDays' ] );
				Route::get( 	'sickday/{sickdayid}', 				[ 'middleware' => 'permission:sickdays.edit' , 														'uses' => 'Api\v1\SickDayController@getSickDay' ] );
				Route::put( 	'sickday', 							[ 'middleware' => 'permission:sickdays.create' , 													'uses' => 'Api\v1\SickDayController@addSickDay' ] );
				Route::post( 	'sickday/{sickdayid}',				[ 'middleware' => 'permission:sickdays.edit' , 														'uses' => 'Api\v1\SickDayController@editSickDay' ] );
				Route::delete( 	'sickday/{sickdayid}',				[ 'middleware' => 'permission:sickdays.delete' , 													'uses' => 'Api\v1\SickDayController@deleteSickDay' ] );
							
			//Medical Referrals				
				Route::get( 	'medicals/{limit?}/{page?}', 		[ 'middleware' => 'permission:medical' , 															'uses' => 'Api\v1\MedicalController@getAllReferrals' ] );
				Route::get( 	'medical/{medicalid}', 				[ 'middleware' => 'permission:medical.edit' , 														'uses' => 'Api\v1\MedicalController@getReferral' ] );
				Route::put( 	'medical', 							[ 'middleware' => 'permission:medical.create' , 													'uses' => 'Api\v1\MedicalController@addReferral' ] );
				Route::post( 	'medical/{medicalid}',				[ 'middleware' => 'permission:medical.edit' , 														'uses' => 'Api\v1\MedicalController@editReferral' ] );
				Route::delete( 	'medical/{medicalid}',				[ 'middleware' => 'permission:medical.delete' , 													'uses' => 'Api\v1\MedicalController@deleteReferral' ] );
							
			//Doctors				
				Route::get( 	'doctors/{limit?}/{page?}', 		[ 'middleware' => 'permission:doctors' , 															'uses' => 'Api\v1\DoctorController@getAllDoctors' ] );
				Route::get( 	'doctor/{doctorid}', 				[ 'middleware' => 'permission:doctors.edit' , 														'uses' => 'Api\v1\DoctorController@getDoctor' ] );
				Route::get( 	'doctor/exists', 					[ 'middleware' => 'permission:doctors.create|permission:doctors.edit' , 							'uses' => 'Api\v1\DoctorController@findDoctor' ] );
				Route::put(	 	'doctor', 							[ 'middleware' => 'permission:doctors.create' , 													'uses' => 'Api\v1\DoctorController@addDoctor' ] );
				Route::post( 	'doctor/{doctorid}',				[ 'middleware' => 'permission:doctors.edit' , 														'uses' => 'Api\v1\DoctorController@editDoctor' ] );
				Route::delete( 	'doctor/{doctorid}',				[ 'middleware' => 'permission:doctors.delete' , 													'uses' => 'Api\v1\DoctorController@deleteDoctor' ] );
									
			//Tech				
				Route::get( 	'tech/{limit}/{page}',				[ 'middleware' => 'permission:tech' , 																'uses' => 'Api\v1\TechController@getAllProducts' ] );
				Route::get( 	'tech/{techid}', 					[ 'middleware' => 'permission:tech|permission:tech.edit' , 											'uses' => 'Api\v1\TechController@getProduct' ] );
				Route::put( 	'tech', 							[ 'middleware' => 'permission:tech.create' , 														'uses' => 'Api\v1\TechController@addProduct' ] );
				Route::post( 	'tech/attach', 						[ 'middleware' => 'permission:tech.create|permission:tech.edit' , 									'uses' => 'Api\v1\TechController@attach' ] );
				Route::post( 	'tech/{techid}',					[ 'middleware' => 'permission:tech.edit' , 															'uses' => 'Api\v1\TechController@editProduct' ] );
				Route::delete( 	'tech/{techid}',					[ 'middleware' => 'permission:tech.delete' , 														'uses' => 'Api\v1\TechController@deleteProduct' ] );
		
            //Email request
                Route::put(     'emailrequest',                     [ 'middleware' => 'permission:emailrequest' ,                                                       'uses' => 'Api\v1\EmailrequestController@send' ] );

	
		});


	});