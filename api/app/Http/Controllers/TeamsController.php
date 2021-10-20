<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Teams;
use App\Models\User;
use Exception;

class TeamsController extends Controller {
    public function addTeam(Request $request){
        $this->validate($request, [
            'team' => 'required',
            'leader' => 'required',
            'members' => 'required',
            'leaderId' => 'required',
        ]);
        //return response()->json($request, 200);
        $teamId = Teams::max('teamId');
        if(is_numeric($teamId)){
            $teamId += 1;
        }else{
            $teamId = 1;
        }
        
        $members = explode(",", $request['members']);
        //try{
            foreach($members as $member){
                if(strlen($member) > 0){
                    $name = User::where('id', '=', $member)->get('name');
                    $insert = new Teams();
                    $insert->team = $request['team'];
                    $insert->teamId = $teamId;
                    $insert->leader = $request['leader'];
                    $insert->leaderId = $request['leaderId'];
                    $insert->members = $name[0]['name'];
                    $insert->memberId = $member;
        
                    $insert->save();
                }
            }
            $newTeam = Teams::where('teamId', '=', $teamId)->get();
            return response()->json(array('success' => true, 'team' => $newTeam), 200);
        // } catch (Exception $e){
        //     return response()->json(array('success' => false, 'error' => $e), 200);
        // }
        
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

    public function getMyTeam(Request $request){
        $this->validate($request, [
            'userId' => 'required'
        ]);

        $lead = Teams::where('leaderId', '=', $request['userId'])->get();
        if($lead->isEmpty()){
            $member = Teams::where('memberId', '=', $request['userId'])->get();
            
            if($member->isEmpty()){
                return response()->json(array("success" => false, 'error' => 'noTeam'), 200);
            }else{
                $team = Teams::where('teamId', '=', $member[0]['teamId'])->get();
                return response()->json(array('success' => true, 'data' => $team), 200);
            }
        }else{
            return response()->json(array('success' => true, 'data' => $lead), 200);
        }
    }

    public function addTeamMember(Request $request){
        $this->validate($request, [
            'userId' => 'required',
            'name' => "required",
            'teamId' => "required",
            'team' => "required",
            'leader' => "required",
            'leaderId' => "required",
        ]);

        try {
            $insert = new Teams();
            $insert->teamId = $request['teamId'];
            $insert->team = $request['team'];
            $insert->leader = $request['leader'];
            $insert->leaderId = $request['leaderId'];
            $insert->members = $request['name'];
            $insert->memberId = $request['userId'];
            $insert->save();

            return response()->json(array('success' => true, 'data' => $insert), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function deleteTeamMember(Request $request){
        $this->validate($request, [
            'memberId' => 'required'
        ]);

        try {
            Teams::where('memberId', '=', $request['memberId'])->delete();
            return response()->json(array('success' => true), 200);
        } catch (Exception $e) {
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}