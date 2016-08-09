<?php

use Faker\Provider\Image;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// routes for all the admin
Route::group(['prefix'=> 'admin' ,'namespace'=>'Admin'], function(){
	// all the routes for front-end site
	Route::get('/sample', ['as' => 'sample', 'uses' => function () {
		return View('backend.sample-list');

	}]);

    Route::get('/', ['as' => 'admin', 'uses' => 'Auth\AdminAuthController@index']);
    Route::get('login', [ 'middleware' => 'guest.admin', 'uses' => 'Auth\AdminAuthController@getLogin'] );
	Route::post('login', [ 'as' => 'adminLogin', 'uses' => 'Auth\AdminAuthController@postLogin'] );
	Route::get('logout', [ 'as' => 'logout', 'uses' => 'Auth\AdminAuthController@getLogout']);

	Route::get('dashboard', ['middleware'=>'auth.admin', 'as' => 'adminDashboard', 'uses'=>'AdminDashboardController@index']);	

	Route::group(['prefix' => 'address', 'middleware'=>'auth.admin'], function(){
		Route::get('/{id}/create', ['as' => 'adminMemberAddressCreate', 'uses' => 'AddressController@create']);
		Route::post('/{id}/create', ['as' => 'adminMemberAddressStore', 'uses' => 'AddressController@store']);
		Route::get('/{id}/edit', ['as' => 'adminMemberAddressEditDetails', 'uses' => 'AddressController@edit']);
		Route::post('/{id}/edit', ['as' => 'adminMemberAddressUpdateDetails', 'uses' => 'AddressController@update']);
	});	

	Route::group(['prefix' => 'contact', 'middleware'=>'auth.admin'], function(){
		Route::get('/{id}/edit', ['as' => 'adminMemberContactDetails', 'uses' => 'ContactController@edit']);
		Route::post('/{id}/edit', ['as' => 'adminMemberContactDetails', 'uses' => 'ContactController@update']);
	});	
	Route::group(['prefix' => 'institution-head', 'middleware'=>'auth.admin'], function(){
		Route::get('/{id}/edit', ['as' => 'adminMemberInstitutionHeadDetails', 'uses' => 'InstitutionHeadController@edit']);
		Route::post('/{id}/edit', ['as' => 'adminMemberInstitutionHeadDetails', 'uses' => 'InstitutionHeadController@update']);
	});
	Route::group(['prefix' => 'payments', 'middleware'=>'auth.admin'], function(){
		Route::get('/{id}/rejection-reason/{narration_id}', ['as' => 'adminMemberPaymentRejectionReason', 'uses' => 'PaymentController@viewRejectionReason']);
		Route::post('/getresource/{resource}', ['as' => 'adminMemberPaymentGetResource', 'uses' => 'PaymentController@getResource']);
		Route::post('/{id}', ['as' => 'adminMemberPaymentSettle', 'uses' => 'PaymentController@store']);
		Route::get('/{id}/accept/{narration_id}', ['as' => 'adminMemberPaymentAccept', 'uses' => 'PaymentController@accept']);
		Route::post('/{id}/reject/{narration_id}', ['as' => 'adminMemberPaymentReject', 'uses' => 'PaymentController@reject']);
		Route::post('/{id}/update/{narration_id}', ['as' => 'adminMemberPaymentUpdate', 'uses' => 'PaymentController@update']);
		Route::get('/{id}', ['as' => 'adminMemberPaymentDetails', 'uses' => 'PaymentController@index']);
		Route::get('/', [ 'as' => 'adminMembershipContent', 'uses'=>'MembershipController@index' ]);

	});
	Route::group(['prefix' => 'memberships', 'middleware'=>'auth.admin'], function(){
		Route::get('/', [ 'as' => 'adminMembershipContent', 'uses'=>'MembershipController@index' ]);
		Route::get('/nominees/{id}', ['as' => 'adminInstitutionNominees', 'uses' => 'MembershipController@institutionNominees']);
		Route::get('/profile', [ 'as' => 'adminProfileIDContent', 'uses'=>'ProfileIdentityController@index' ]);
		Route::get('/profile/{id}/accept', [ 'as' => 'adminProfileIDAccept', 'uses'=>'ProfileIdentityController@accept' ]);
		Route::get('/profile/{id}/reject', [ 'as' => 'adminProfileIDReject', 'uses'=>'ProfileIdentityController@reject' ]);
		Route::get('/profile/{id}/view', [ 'as' => 'adminShowProfile', 'uses'=>'ProfileIdentityController@profile' ]);
	});
	Route::group(['prefix' => 'student-branch', 'middleware'=>'auth.admin'], function(){
		Route::get('/', [ 'as' => 'adminStudentBranchContent', 'uses'=>'StudentBranchController@index' ]);
		Route::get('/{id}/confirm', [ 'as' => 'adminStudentBranchConfirm', 'uses'=>'StudentBranchController@store' ]);
		Route::post('/{id}/decline', [ 'as' => 'adminStudentBranchDecline', 'uses'=>'StudentBranchController@destroy' ]);
		Route::get('/{id}/decline', [ 'as' => 'adminStudentBranchRejectionReason', 'uses'=>'StudentBranchController@viewRejectionReason' ]);
	});	
	Route::group(['prefix' => 'bulk-payments', 'middleware'=>'auth.admin'], function(){
		Route::get('/', [ 'as' => 'adminMemberBulkPayments', 'uses'=>'BulkPaymentController@index' ]);
		Route::get('/{id}', [ 'as' => 'adminMemberBulkPaymentDetails', 'uses'=>'BulkPaymentController@details' ]);
		Route::get('/{id}/accept/{narration_id}', [ 'as' => 'adminMemberBulkPaymentAccept', 'uses'=>'BulkPaymentController@accept' ]);
		Route::post('/{id}/reject/{narration_id}', [ 'as' => 'adminMemberBulkPaymentReject', 'uses'=>'BulkPaymentController@reject' ]);
		Route::get('/{id}/rejection-reason/{narration_id}', ['as' => 'adminMemberPaymentRejectionReason', 'uses' => 'BulkPaymentController@viewRejectionReason']);
		Route::post('/update/{narration_id}', [ 'as' => 'adminMemberBulkPaymentUpdate', 'uses'=>'BulkPaymentController@update' ]);
		Route::post('/getresource/{resource}', ['as' => 'adminMemberBulkPaymentGetResource', 'uses' => 'BulkPaymentController@getResource']);
		Route::post('/{id}/store', ['as' => 'adminMemberBulkPaymentSettle', 'uses' => 'BulkPaymentController@store']);
	});	
	Route::group(['prefix' => 'division', 'middleware'=>'auth.admin'], function(){
		Route::get('/region', [ 'as' => 'adminRegionContent', 'uses'=>'RegionController@index' ]);
		Route::get('/world-countries', [ 'as' => 'adminWorldCountryContent', 'uses'=>'WorldCountryController@index' ]);
		Route::post('/world-countries/upload', [ 'as' => 'adminWorldCountryUpload', 'uses'=>'WorldCountryController@upload' ]);
		Route::post('/world-countries/{alpha3_code}/edit', [ 'as' => 'adminWorldCountryEdit', 'uses'=>'WorldCountryController@update' ]);
		Route::get('/world-countries/{country_code}/world-states', [ 'as' => 'adminWorldStateContent', 'uses'=>'WorldStateController@index' ]);
		Route::get('/world-countries/{country_code}/world-states/upload', [ 'as' => 'adminWorldStateUpload', 'uses'=>'WorldStateController@upload' ]);
		Route::post('/world-countries/{country_code}/world-states/{state_code}/edit', [ 'as' => 'adminWorldStateEdit', 'uses'=>'WorldStateController@update' ]);
		Route::post('/region/upload', [ 'as' => 'adminRegionUpload', 'uses'=>'RegionController@upload' ]);
		Route::post('/region/{id}/edit', [ 'as' => 'adminRegionEdit', 'uses'=>'RegionController@update' ]);
		Route::get('/region/{id}/state', [ 'as' => 'adminStateContent', 'uses'=>'StateController@index' ]);
		Route::post('/region/{id}/state/upload', [ 'as' => 'adminStateUpload', 'uses'=>'StateController@upload' ]);
		Route::get('/region/{id}/state/{state_code}/chapter', [ 'as' => 'adminChapterContent', 'uses'=>'ChapterController@index' ]);
		Route::post('/region/{id}/state/{state_code}/chapter/upload', [ 'as' => 'adminChapterUpload', 'uses'=>'ChapterController@upload' ]);
		Route::post('/region/{id}/state/{state_code}/chapter/{chapter_id}/edit', [ 'as' => 'adminChapterEdit', 'uses'=>'ChapterController@update' ]);
	});

	Route::group(['prefix'=>'authorize', 'middleware'=>'auth.admin'], function(){
		Route::get('/', ['as'=>'adminAuthorizations', 'uses'=>'AdminController@index']);
		Route::get('/view', ['as'=>'viewAdmins', 'uses'=>'AdminController@viewAdmins']);
		Route::get('/create', ['as'=>'createAdmin', 'uses'=>'AdminController@createAdmin']);
		Route::post('/create', ['as'=>'storeAdminUser', 'uses'=>'AdminController@storeAdmin']);
	});

	Route::get('proofs/{filename}', ['as' => 'adminPaymentProof', 'uses' => function($filename){
	    $path = storage_path() . '/uploads/payment_proofs/' . $filename;
		$filetype = File::mimeType($path);
	    if( $filetype=="application/pdf" || ends_with($filename, '.pdf') ){
	    	return Response::download($path, $filename, ['Content-Type' => 'application/pdf']);
	    } else{
			$filetype = File::mimeType($path);
			$imgbinary = fread(fopen($path, "r"), filesize($path));
			$file = 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
		    //$file = base64_encode(file_get_contents($path));
		    return $file;
		}
	}]);

	Route::get('profile-proofs/{filename}', ['as' => 'adminProfileProof', 'uses' => function($filename){
	    $path = storage_path() . '/uploads/profile_proofs/' . $filename;
		$filetype = File::mimeType($path);
		if(!File::exists($path)){
			$path = storage_path() . '/uploads/profile_proofs/sample.jpg';
		} else if( $filetype=="application/pdf" || ends_with($filename, '.pdf') ){
			return Response::download($path, $filename, ['Content-Type' => 'application/pdf']);
		} 
		$filetype = File::mimeType($path);
		$imgbinary = fread(fopen($path, "r"), filesize($path));
    	$file = 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	    //$file = base64_encode(file_get_contents($path));
	    return $file;
   	
	}]);

});


Route::get('proofs/{filename}', ['as' => 'userPaymentProof', 'uses' => function($filename){
	$path = storage_path() . '/uploads/payment_proofs/' . $filename;
	$filetype = File::mimeType($path);
    if( $filetype=="application/pdf" || ends_with($filename, '.pdf') ){
    	return Response::download($path, $filename, ['Content-Type' => 'application/pdf']);
    } else{
		$filetype = File::mimeType($path);
		$imgbinary = fread(fopen($path, "r"), filesize($path));
		$file = 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	    //$file = base64_encode(file_get_contents($path));
	    return $file;
	}
}]);

Route::get('profile/photographs/{filename}', ['as' => 'UserProfilePhotograph', 'uses' => function($filename){
    $path = storage_path() . '/uploads/profile_photographs/' . $filename;

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
}]);

Route::get('profile/signatures/{filename}', ['as' => 'UserProfileSignatures', 'uses' => function($filename){
    $path = storage_path() . '/uploads/signatures/' . $filename;

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
}]);
Route::get('/{id}/rejection-reason/{narration_id}', ['as' => 'MemberPaymentRejectionReason', 'uses' => 'MembershipPaymentController@viewRejectionReason']);

// all the routes for front-end site
Route::get('/', function () {
	return View('frontend.index');

});

// all the routes for front-end site
Route::get('/home', function () {
	return View('frontend.index');

});

Route::group(['prefix' => 'register'], function(){
	Route::get('{entity}', ['as'=>'register', 'uses'=>'RegisterController@create']);

	Route::post('getresource/{resource}', 'RegisterController@getResource');
	Route::post('{entity}/payments', ['as' => 'submitToPayments', 'uses' => 'RegisterController@submitToPayments']);
	Route::group(['prefix' => '{entity}', 'middleware' => 'auth'], function(){
		Route::get('payments', ['as' => 'createSubmitToPayments', 'uses' => 'RegisterController@createSubmitToPayments']);
		Route::post('/payments/{mode}', ['as' => 'CreatePayments', 'uses' => 'RegisterController@store']);
	});
});

Route::get('/{id}/nominee-request-form', ['middleware' => 'auth.individual', 'as' => 'NomineeRequestForm', 'uses' => 'RegisterController@requestform']);
Route::post('/{id}/nominee-request', ['middleware' => 'auth.individual', 'as' => 'NomineeRequest', 'uses' => 'RegisterController@request']);

Route::group(['prefix' => 'payments'], function(){
	Route::get('/', ['as' => 'viewAllMembershipPayments', 'uses' => 'MembershipPaymentController@index']);
	Route::get('/{pid}', ['as' => 'CreateMembershipPayments', 'uses' => 'MembershipPaymentController@create']);
	Route::post('/{pid}/{mode}', ['as' => 'DoMembershipPayments', 'uses' => 'MembershipPaymentController@store']);
	Route::get('/{id}/rejection-reason/{narration_id}', ['as' => 'MemberPaymentRejectionReason', 'uses' => 'MembershipPaymentController@viewRejectionReason']);
});


// Authentication routes...
Route::get('login', ['middleware' =>['guest'] ,'uses' => 'Auth\AuthController@getLogin']);
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

Route::get('/sample', ['as' => 'sample', 'uses' => function () {
	return View('frontend.sample-list');

}]);

Route::group(['prefix' => 'address', 'middleware'=>'auth'], function(){
	Route::get('/create', ['as' => 'MemberAddressCreate', 'uses' => 'AddressController@create']);
	Route::post('/create', ['as' => 'MemberAddressStore', 'uses' => 'AddressController@store']);
	Route::get('/{id}/edit', ['as' => 'MemberAddressEditDetails', 'uses' => 'AddressController@edit']);
	Route::post('/{id}/edit', ['as' => 'MemberAddressUpdateDetails', 'uses' => 'AddressController@update']);
});

Route::group(['prefix' => 'contact', 'middleware'=>'auth'], function(){
	Route::get('/edit', ['as' => 'MemberContactEditDetails', 'uses' => 'ContactController@edit']);
	Route::post('/edit', ['as' => 'MemberContactUpdateDetails', 'uses' => 'ContactController@update']);
});	

Route::group(['prefix' => 'institution-head', 'middleware'=>'auth'], function(){
	Route::get('/edit', ['as' => 'MemberInstitutionHeadEditDetails', 'uses' => 'InstitutionHeadController@edit']);
	Route::post('/edit', ['as' => 'MemberInstitutionHeadUpdateDetails', 'uses' => 'InstitutionHeadController@update']);
});
Route::group(['middleware'=>'checkUserPaymentsVerified'], function(){
	Route::group(['prefix' => 'bulk-payments', 'middleware'=>'auth'], function(){
		Route::get('/', ['as' => 'BulkPaymentsView', 'uses' => 'BulkPaymentsController@index']);
		Route::post('/upload', ['as' => 'BulkPaymentsCreate', 'uses' => 'BulkPaymentsController@uploadCSV']);
		Route::post('/{id}/edit', ['as' => 'BulkPaymentsEdit', 'uses' => 'BulkPaymentsController@uploadCSVEdit']);
		Route::get('/{id}/getfile', ['as' => 'BulkPaymentsGetFile', 'uses' => 'BulkPaymentsController@downloadCSV']);
		Route::get('/{id}/getfile/sample', ['as' => 'BulkPaymentsGetFileSample', 'uses' => 'BulkPaymentsController@downloadSampleCSV']);
		Route::get('/{id}/payments', ['as' => 'BulkPaymentsDoPayment', 'uses' => 'BulkPaymentsController@create']);
		Route::post('/{id}/payments/{mode}', ['as' => 'BulkPaymentsStore', 'uses' => 'BulkPaymentsController@store']);
		Route::get('/{id}/rejection-reason/{narration_id}', ['as' => 'adminMemberPaymentRejectionReason', 'uses' => 'BulkPaymentsController@viewRejectionReason']);
	});

	Route::group(['prefix' => 'nominees', 'middleware' =>'auth'], function(){
		Route::get('/', ['as' => 'NomineeView', 'uses' => 'NomineeController@index']);
		Route::post('/add', ['as' => 'NomineeCreate', 'uses' => 'NomineeController@store']);
		Route::get('/{id}/accept', ['as' => 'NomineeAccept', 'uses' => 'NomineeController@accept']);
		Route::get('/{id}/reject', ['as' => 'NomineeReject', 'uses' => 'NomineeController@reject']);
		Route::get('/{id}/remove', ['as' => 'NomineeRemove', 'uses' => 'NomineeController@remove']);
		Route::get('/{id}/renew', ['as' => 'NomineeRenew', 'uses' => 'NomineeController@renew']);
		Route::get('/{id}/delete', ['as' => 'NomineeDelete', 'uses' => 'NomineeController@destroy']);


	});

	Route::group(['prefix' => 'student-branch', 'middleware' =>'auth'], function(){
		Route::get('/', ['as' => 'RequestStudentBranch', 'uses' => 'StudentBranchController@index']);
		Route::get('/request', ['as' => 'SendRequestStudentBranch', 'uses' => 'StudentBranchController@store']);
	});


});

Route::get('/password/reset/{type}/{token}', ['middleware'=>'guest', 'as' => 'MemberForgetPasswordShow', 'uses' => 'SecurityController@show']);
Route::group(['prefix' => 'security'], function(){
	Route::get('/password/reset', ['middleware'=>'guest', 'as' => 'MemberForgetPassword', 'uses' => 'SecurityController@create']);
	Route::post('/password/reset', ['middleware'=>'guest', 'as' => 'MemberForgetPasswordRequest', 'uses' => 'SecurityController@store']);
	Route::post('/password/update/{type}/{token}', ['middleware'=>'guest', 'as' => 'MemberForgetPasswordUpdate', 'uses' => 'SecurityController@edit']);
	Route::get('/passwd', ['middleware'=>'auth', 'as' => 'MemberChangePassword', 'uses' => 'SecurityController@index']);
	Route::post('/passwd', ['middleware'=>'auth', 'as' => 'MemberUpdatePassword', 'uses' => 'SecurityController@update']);
});	

Route::group(['prefix' => 'userprofile', 'middleware'=>'auth'], function(){
	Route::post('/images/upload/{type}', ['as' => 'uploadImage', 'uses' => 'ImagesController@update']);
	Route::get('/printcard/download', ['as' => 'downloadCsiCard', 'uses' => 'CardController@download']);
	Route::get('/printcard', ['as' => 'printcard', 'uses' => 'CardController@index']);
	Route::post('/printcard/editcardname', ['middleware'=>['checkUserPaymentsVerified'], 'as' => 'EditCardName', 'uses' => 'CardController@update']);
	Route::post('/profile-id/upload', [ 'as' => 'adminEditProfile', 'uses'=>'ProfileController@update' ]);
	Route::get('/{id}/view', [ 'as' => 'userViewProfile', 'uses'=>'ProfileController@profile' ]);
});

Route::get('/dashboard', ['middleware'=>['auth'], 'as' => 'userDashboard', 'uses'=>'UserDashboardController@index']);


Route::get('/payments-not-verified', ['middleware'=>['auth'], 'as' => 'userDashboard', 'uses'=>'UserDashboardController@index']);

