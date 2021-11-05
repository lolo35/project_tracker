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
                    $userInfo = ['id' => $user[0]['id'], 'name' => $user[0]['name'], 'email' => $user[0]['email'], 'autoliv_id' => $user[0]['autoliv_id']];
                    return response()->json(array('success' => true, 'user' => $userInfo), 200);
                }
            }
        } catch(Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function createUser(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'autolivId' => 'required',
            'password' => 'required',
        ]);

        $password = hash('sha512', $request['password']);
        try {
            $check = User::where('email', '=', $request['email'])->get();
            if($check->isEmpty()){
                $insert = new User();
                $insert->name = $request['name'];
                $insert->email = $request['email'];
                $insert->password = $password;
                $insert->autoliv_id = $request['autolivId'];
                if($insert->save()){
                    return response()->json(array('success' => true), 200);
                }else{
                    return response()->json(array('success' => false, 'error' => 'userCreation'), 200);
                }
            }else{
                return response()->json(array('success' => false, 'error' => 'userExists'), 200);
            }
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function changePassword(Request $request){
        $this->validate($request, [
            'user_id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        try {
            $user = User::where('id', '=', $request['user_id'])->get()->makeVisible('password')->toArray();
            $password = hash('sha512', $request['current_password']);
            if($user[0]['password'] === $password){
                $newPass = hash('sha512', $request['new_password']);
                User::where('id', '=', $request['user_id'])->update(['password' => $newPass]);
                return response()->json(array('success' => true), 200);
            }else{
                return response()->json(array('success' => false, 'error' => 'wrongPass'), 200);
            }
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}