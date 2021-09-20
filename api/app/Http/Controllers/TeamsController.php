<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Teams;
use Exception;

class TeamsController extends Controller {
    public function addTeam(Request $request){
        $this->validate($request, [
            'team' => 'required',
            'leader' => 'required',
            'members' => 'required'
        ]);


        $members = explode(",", $request['members']);
        try{
            foreach($members as $member){
                $insert = new Teams();
                $insert->team = $request['team'];
                $insert->leader = $request['leader'];
                $insert->members = $member;
    
                $insert->save();
            }
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
        
    }
}