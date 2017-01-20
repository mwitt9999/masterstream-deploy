<?php

Route::get('/build/test', 'BuildController@testBuild');
Route::get('/build/test/show', 'BuildController@showTestBuild');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'LoginController@showLogin');
    Route::get('/login', 'LoginController@showLogin');
});

Route::post('/login/authenticate', 'LoginController@authenticateUser');
Route::post('/login/resetpassword/submit', 'LoginController@submitResetPassword');
Route::get('/login/resetpassword', 'LoginController@resetPassword')->name('resetpassword');
Route::get('/login/forgotpassword', 'LoginController@forgotPassword');
Route::get('/logout', 'LoginController@logout');

Route::group(['middleware' => 'Authenticated'], function () {
//    Route::get('/deployment', 'DeploymentController@showDeploy');
//    Route::post('/deployment/submit', 'DeploymentController@submitDeploy');

    Route::get('/build', 'BuildController@showBuild');
    Route::get('/build/all', 'BuildController@getAllBuilds');
    Route::post('/build/submit', 'BuildController@submitBuild');
    Route::get('/build/delete/{id}', 'BuildController@deleteBuildById');
    Route::get('/build/commits/get', 'BuildController@getCommitsBySiteId');

//    Route::get('/rollback', 'RollbackController@showRollback');
//    Route::post('/rollback/submit', 'RollbackController@submitRollback');

    Route::get('/history', 'HistoryController@showHistory');
    Route::get('/history/all', 'HistoryController@getAllOperationHistory');

    Route::get('/server', 'ServerController@showServers');
    Route::post('/server/add', 'ServerController@addServer');
    Route::post('/server/update', 'ServerController@updateServer');
    Route::get('/server/delete/{serverId}', 'ServerController@deleteServer');
    Route::get('/server/all', 'ServerController@getAllServers');

    Route::get('/site', 'SiteController@showSites');
    Route::post('/site/add', 'SiteController@addSite');
    Route::post('/site/update', 'SiteController@updateSite');
    Route::get('/site/delete/{siteId}', 'SiteController@deleteSite');
    Route::get('/site/all', 'SiteController@getAllSites');

    Route::get('/status', 'StatusController@showStatus');
    Route::post('/status/add', 'StatusController@addStatus');
    Route::post('/status/update', 'StatusController@updateStatus');
    Route::get('/status/delete/{statusId}', 'StatusController@deleteStatus');
    Route::get('/status/all', 'StatusController@getAllStatus');

    Route::get('/pipeline', 'PipelineController@showPipeline');
    Route::post('/pipeline/add', 'PipelineController@addPipeline');
    Route::post('/pipeline/update', 'PipelineController@updatePipeline');
    Route::get('/pipeline/delete/{statusId}', 'PipelineController@deletePipeline');
    Route::get('/pipeline/all', 'PipelineController@getAllPipelines');
    Route::get('/pipeline/tasks/all/{id}', 'PipelineController@getAllTasksByPipelineId');
    Route::post('/pipeline/tasks/update', 'PipelineController@updateTaskListForPipelineById');

    Route::get('/task', 'TaskController@showTask');
    Route::post('/task/add', 'TaskController@addTask');
    Route::post('/task/update', 'TaskController@updateTask');
    Route::get('/task/delete/{statusId}', 'TaskController@deleteTask');
    Route::get('/task/all', 'TaskController@getAllTasks');

    Route::get('/status', 'StatusController@showStatus');
    Route::post('/status/add', 'StatusController@addStatus');
    Route::post('/status/update', 'StatusController@updateStatus');
    Route::get('/status/delete/{statusId}', 'StatusController@deleteStatus');
    Route::get('/status/all', 'StatusController@getAllStatus');

    Route::group(['middleware' => 'AdminCheck'], function () {
        Route::get('/user', 'AdminUserController@showUsers');
        Route::post('/user/add', 'AdminUserController@addUser');
        Route::post('/user/update', 'AdminUserController@updateUser');
        Route::get('/user/delete', 'AdminUserController@deleteUser');
        Route::get('/user/all', 'AdminUserController@getAllUsers');
    });
});
