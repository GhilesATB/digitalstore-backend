<?php

namespace App\Http\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $permissions = Permission::all();

        $permissions = new PermissionCollection($permissions);

        return $permissions->withStatusCode(Response::HTTP_OK);
    }
}
