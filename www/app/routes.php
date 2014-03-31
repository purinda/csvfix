<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Common
Route::get('/',                             array('uses' => 'HomeController@showWelcome'));
Route::post('/upload',                      array('uses' => 'HomeController@upload'));

// User management
Route::post('/auth/register',               array('uses' => 'UserManagement@register'));
Route::post('/auth/signin',                 array('uses' => 'UserManagement@signin'));
Route::post('/user/save_mappings',          array('uses' => 'UserManagement@saveMappings'));
Route::get('/user/show_mappings',           array('uses' => 'UserManagement@showMappings'));
Route::get('/auth/signin/{id}',             array('uses' => 'UserManagement@signin'));
Route::get('/auth/logout',                  array('uses' => 'UserManagement@logout'));

// CSV Related
Route::get('/view/{id}',                          array('uses' => 'CSVProcessController@view'));
Route::get('/view/get_file_content/{id}',         array('uses' => 'CSVProcessController@getFileData'));
Route::post('/process/merge_fields/{id}/{limit}', array('uses' => 'CSVProcessController@processMergeFields'));
Route::post('/process/preview/{id}/{limit}',      array('uses' => 'CSVProcessController@preview'));

