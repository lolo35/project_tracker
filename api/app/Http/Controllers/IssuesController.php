<?php
namespace App\Http\Controllers;
use App\Models\Issues;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssuesController extends Controller {

    public function countIssues(Request $request){
        $this->validate($request, [
            'project_id' => 'required'
        ]);

        try {
            $opened = Issues::selectRaw('count(status) as opened')->where('status', '=', 1)->where('project_id', '=', $request['project_id'])->get();
            $closed = Issues::selectRaw('count(status) as closed')->where('status', '=', 0)->where('project_id', '=', $request['project_id'])->get();
            return response()->json(array('success' => true, 'opened' => $opened, 'closed' => $closed), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addIssue(Request $request){
        $this->validate($request, [
            'project_id' => 'required',
            'issue' => 'required',
            'description' => 'required',
            'user_id' => 'required',
        ]);

        try {
            $insert = new Issues();
            $insert->project_id = $request['project_id'];
            $insert->issue = $request['issue'];
            $insert->description = $request['description'];
            $insert->status = 1;
            $insert->opened_by = $request['user_id'];
            $insert->save();
            
            $data = DB::table('issues')
                ->join('users as opened_by', 'issues.opened_by', '=', 'opened_by.id')
                ->select('issues.*', 'opened_by.name as open_name', 'opened_by.autoliv_id as open_autolivid')
                ->where('issues.id', '=', $insert['id'])
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function fetchIssues(Request $request){
        $this->validate($request, [
            'project_id' => 'required'
        ]);
        try {
            //$data = Issues::where('project_id', '=', $request['project_id'])->get();
            $data = DB::table('issues')
                ->join('users as opened_by', 'issues.opened_by', '=', 'opened_by.id')
                //->join('users as closed_by', 'issues.closed_by', '=', 'closed_by.id')
                ->select('issues.*', 'opened_by.name as open_name', 'opened_by.autoliv_id as open_autolivid')//, 'closed_by.name as close_name', 'closed_by.autoliv_id as close_autolivid')
                ->where('issues.project_id', '=', $request['project_id'])
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function fetchClosedBy(Request $request){
        $this->validate($request, [
            'issue_id' => 'required',
        ]);

        try {
            $data = DB::table('issues')
                ->join('users', 'issues.closed_by', '=', 'users.id')
                ->select('issues.closed_by','issues.reason', 'users.name')
                ->where('issues.id', '=', $request['issue_id'])
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);

        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function closeIssue(Request $request){
        $this->validate($request, [
            'issue_id' => 'required',
            'user_id' => 'required',
            'reason' => 'required'
        ]);

        try {
            Issues::where('id', '=', $request['issue_id'])->update(
                [
                    'status' => 0, 
                    'closed_by' => $request['user_id'],
                    'reason' => $request['reason'],
            ]);
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

}