<?php

use App\Http\Controllers\RolePermissionsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware'=>['api'],
    'prefix'=>'v1/roles'
], function(){
    Route::get('/roles', [RolePermissionsController::class, 'indexRoles']);
    Route::get('/permissions', [RolePermissionsController::class, 'indexPermissions']);
    Route::post('/roles', [RolePermissionsController::class, 'storeRole']);
    Route::post('/permissions', [RolePermissionsController::class, 'storePermission']);
    Route::put('/roles/{id}', [RolePermissionsController::class, 'updateRole']);
    Route::put('/permissions/{id}', [RolePermissionsController::class, 'updatePermission']);
    Route::delete('/roles/{id}', [RolePermissionsController::class, 'destroyRole']);
    Route::delete('/permissions/{id}', [RolePermissionsController::class, 'destroyPermission']);
    Route::post('/assign/role', [RolePermissionsController::class, 'assignRoleToUser']);
    Route::post('/role/permission', [RolePermissionsController::class, 'assignPermissionToRole']);
    Route::post('/role/permission/revoke', [RolePermissionsController::class, 'revokePermissionFromRole']);
});
