<?php
namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller {
    //
    public function getAllUsers() {
        $users = User::all();
        return response()->json($users);
    }
    public function show($id) {
        $user = User::findOrFail($id);

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }
}
