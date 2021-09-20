<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller {
    public function getUsers(){
        $data = User::get();
        return response()->json(array('success' => true, 'data' => $data), 200);
    }
}