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

function validationError($errors, $message = 'Validation failed', $code = 422) {
    return response()->json([
        'status' => 'error',
        'message' => $message,
        'errors' => $errors,
    ], $code);
}

function unauthorized($message = 'Unauthorized', $code = 401) {
    return response()->json([
        'status' => 'error',
        'message' => $message,
    ], $code);
}

function forbidden($message = 'Forbidden', $code = 403) {
    return response()->json([
        'status' => 'error',
        'message' => $message,
    ], $code);
}