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

        $teamId = Teams::max('teamId');
        if(is_numeric($teamId)){
            $teamId += 1;
        }else{
            $teamId = 1;
        }

        $members = explode(",", $request['members']);
        try{
            foreach($members as $member){
                if(strlen($member) > 0){
                    $insert = new Teams();
                    $insert->team = $request['team'];
                    $insert->teamId = $teamId;
                    $insert->leader = $request['leader'];
                    $insert->members = $member;
        
                    $insert->save();
                }
            }
            $newTeam = Teams::where('teamId', '=', $teamId)->get();
            return response()->json(array('success' => true, 'team' => $newTeam), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
        
    }

    public function getTeams(){
        try {
            $data = Teams::get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e) {
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function deleteTeam(Request $request){
        $this->validate($request, [
            'teamId' => 'required'
        ]);

        try {
            Teams::where('teamId', '=', $request['teamId'])->delete();
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}