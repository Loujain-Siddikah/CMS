<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function getPermissions(){
        $permissions = Permission::pluck('name')->toArray();
        return $this->jsonSuccessResponse('permissions get successfully',['permissions' => $permissions]);
    }
}
