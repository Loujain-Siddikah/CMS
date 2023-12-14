<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddEditorRequest;
use App\Http\Requests\UpdateEditorRequest;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    use JsonResponseTrait;

    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function addEditor(AddEditorRequest $request){
        try{
          
            $editor = User::create([
                'name'=> $request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            $token=$editor->createToken('authToken'.$editor->name)->plainTextToken;
            if ($request->has('permissions')) {
                $editor->syncPermissions($request->permissions);
            }
            $editor->assignRole('editor');
            return $this->jsonSuccessResponse('Editor added successfully',['token' => $token], 201);
        }catch(ValidationException $e){
            return $this->jsonErrorResponse('Validation error',402,['errors' => $e->errors()]);
        }  
    }

    public function updateEditor(UpdateEditorRequest $request,$id){
        try{
            $editor = User::findOrFail($id);
            $editor->update([
                'name' => $request->name,
                'email' => $request->email, 
                'password' => $request->password ? Hash::make($request->password) : $editor->password,
            ]);
            if ($request->has('permissions')) {
                $editor->syncPermissions($request->permissions);
            }
            return $this->jsonSuccessResponse('Editor edited successfully');
        }catch(ValidationException $e){
            return $this->jsonErrorResponse('Validation error',402,['errors' => $e->errors()]);
        }
    }

    public function deleteEditor($id){
        $editor = User::findOrFail($id);
        $editor->delete();
        return $this->jsonSuccessResponse('Editor deleted successfully');
    }
}
