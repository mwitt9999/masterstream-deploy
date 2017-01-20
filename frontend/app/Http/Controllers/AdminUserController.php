<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPostRequest;
use App\AdminUser;
use App\Http\Requests\UpdateUserPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    protected $user;

    public function __construct(AdminUser $user)
    {
        $this->user = $user;
    }

    public function showUsers() {
        $user = Auth::user();
        return view('users')->with('authenticatedUserId', $user->id);
    }

    public function addUser(StoreUserPostRequest $request) {

        $this->user->saveUser($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updateUser(UpdateUserPostRequest $request) {
        $this->user->editUser($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deleteUser(Request $request) {
        $this->user->deleteUser($request->input('userId'));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllUsers() {
        return $this->user->getUserDataTable();
    }

}
