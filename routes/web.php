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

Route::group(['middleware' => 'auth'], function(){

	// Route::get('/file/{id}', 'DocumentController@update');

	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/home/allfiles', 'HomeController@municipalityFiles');
	
	// load more functionality
	Route::post('/home/loadmore', 'HomeController@loadMore');

	Route::get('/home/name', 'HomeController@sortByName');

	Route::get('/allfiles/name', 'HomeController@sortAllByName');

	Route::get('/home/date', 'HomeController@sortByDate');

	Route::get('/allfiles/date', 'HomeController@sortAllByDate');

	Route::get('/home/sharedfiles', 'HomeController@sharedFiles');

	Route::get('/home/publicfiles', 'HomeController@publicFiles');

	Route::post('/editThumbnail/{id}', 'UserController@upload');

	Route::get('/edit_profile', function (){
		return view('user/edit_profile'); 
	});

	Route::get('/edited','UserController@update');

	Route::resource('/user', 'UserController');

	Route::get('/profile', 'UserController@viewProfile');
	
	Route::resource('/file', 'DocumentController');

	Route::get('/file/{id}/download', 'DocumentController@download');

	Route::get('/tag/{id}', 'DocumentController@taggedFile');

	Route::get('/tags', function(){
		return Auth::user()->usedTags();
	});

	Route::get('/locationTags' ,function(){
		return App\Tag::locationTags(Auth::user()->location);
	});

	Route::get('/leaderboard', function(){
		return view('/leaderboard');
	});

	Route::get('/reminders/{id}/inactive', 'RemindersController@checkInactive');

	Route::get('/reminders/{id}/active', 'RemindersController@checkActive');

	Route::get('/reminders', 'RemindersController@index')->name('reminders');

	Route::get('/reminders/uncomplied', 'RemindersController@checkUncomplied');

	Route::get('/reminders/complied', 'RemindersController@checkComplied');

	Route::get('/reminders/overdue', 'RemindersController@checkOverdue');

	Route::resource('/reminders', 'RemindersController');
	
	Route::get('/leaderboard', 'ComplianceMunicipalityController@checkScores');

	Route::get('/compliances', 'ComplianceUserController@checkCompliants');

	Route::get('/leaderboard', 'ComplianceMunicipalityController@checkScores');

	Route::get('/compliances', 'ComplianceUserController@checkCompliants');

	Route::get('/password_reset', 'UserController@retrieveUsers');

	Route::get('/reset', 'UserController@resetPassword');

});
 
Route::get('/', function () {
	return view('auth/login');
})->middleware('guest');
	
Auth::routes();

/* Font-end routes. Do not tamper. */

Route::get('/links', function (){
	return view('links');
});

Route::get('/dev', function (){
	return view('user/developers');
});

// Route::get('/password_reset', function (){
// 	return view('password_reset');
// });

Route::get('/compliances/{id}', 'ComplianceUserController@checkCompliants'); 

Route::get('/reminders/{id}/complied','ComplianceUserController@checkNotice');

Route::get('/compliances/{id}/action','ComplianceMunicipalityController@complianceAction');

Route::get('/reminders/{id}/inactive', 'RemindersController@checkInactive');

Route::get('/reminders/{id}/active', 'RemindersController@checkActive');
