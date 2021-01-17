<?php 

namespace App\Traits;

class Helper{

    public static function sendResponse($status, $data, $code, $message){   
        return json()->response([
            'status'  => $status,
            'data'    => $data,
            'message' => $message 
            ] , $code
            );

    }

    public static function sendResponseWithoutData($status, $code, $message) {
        return json()->response([
            'status'  => $status,
            'message' => $message,
            'code'    => $code
        ]);
    }

    public static function sendResponseSuccess($data){
        return response()->json([
                'status' => true,
                'message' => 'Success!',
                'data' => $data ], 200);
    }


}

?>