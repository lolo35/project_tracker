<?php
namespace App\Http\Controllers;
use App\Models\RecurringTasksHistory;
use Exception;
use Illuminate\Http\Request;

class RecurringTasksHistoryController extends Controller {
    public function recurringHistoryForUser(Request $request){
        $this->validate($request, [
            'user_id' => 'required'
        ]);

        try {
            $data = RecurringTasksHistory::where('user_id', '=', $request['user_id'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}