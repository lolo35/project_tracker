<?php
namespace App\CustomClasses;

use Exception;
use Requests;

class L2lApi {
    public $dispatchId;

    public function __construct($dispatchId)
    {
        $this->dispatchId = $dispatchId;
    }
    protected $url = "https://autoliv-eu2.leading2lean.com/api/1.0/";
    protected $auth = "?auth=ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh";
    protected $site = "&site=15";

    public function getDispatchesById(){
        $url = $this->url . "dispatches/" . $this->auth . $this->site . "&id=" . $this->dispatchId;
        try {
            $request = Requests::get($url);
            $response = json_decode($request->body, true);
            if($response['success']){
                return array('success' => true, 'data' => $response['data']);
            }else{
                return array('success' => false, 'error' => $response['error']);
            }
        } catch (Exception $e){
            return array('success' => false, 'error' => $e);
        } 

    }

    public function updateDispatchDescription($id, $description, $add){
        $url = $this->url . "dispatches/set_description/$id/";
        $headers = ['Content-type' => 'application/x-www-form-urlencoded'];
        $options = ['auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh', 'site' => 15, 'description' => $add . " " . $description];

        $request = Requests::post($url, $headers, $options);
        $response = json_decode($request->body, true);
        if($response['success']){
            return array('success' => true);
        }else{
            return array('success' => false, 'error' => $response['error']);
        }
    }

    public function completeDispatch($id){
        $url = $this->url . "dispatches/complete/$id/";
        $headers = ['Content-type' => 'application/x-www-form-urlencoded'];
        $options = ['auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh', 'site' => 15];

        $request = Requests::post($url, $headers, $options);
        $response = json_decode($request->body, true);
        if($response['success']){
            return array('success' => true);
        }else {
            return array('success' => false, 'error' => $response['error']);
        }
    }
}