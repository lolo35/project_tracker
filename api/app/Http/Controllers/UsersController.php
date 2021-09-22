<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UsersController extends Controller {
    public function getUsers(){
        $data = User::get();
        return response()->json(array('success' => true, 'data' => $data), 200);
    }

    public function validateLogin(Request $request){
        $this->validate($request, [
            'user' => 'required',
            'pass' => 'required',
        ]);
        try {   
            $user = User::where('email', '=', $request['user'])->get()->makeVisible('password')->toArray();
            if(!empty($user)){
                $rpass = hash('sha512', $request['pass']);
                if($rpass === $user[0]['password']){
                    $userInfo = ['id' => $user[0]['id'], 'name' => $user[0]['name'], 'email' => $user[0]['email']];
                    return response()->json(array('success' => true, 'user' => $userInfo), 200);
                }
            }
        } catch(Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}