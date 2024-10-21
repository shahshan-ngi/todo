<?php
function success($data,$message,$code=200){

    return response()->json([
        'status' => 'success',
        'data'=>$data,
        'message' => $message
    ], $code);
}
function error($message, $code = 500)
{
    return response()->json([
        'status' => 'error',
        'message' => $message,
    ], $code);
}