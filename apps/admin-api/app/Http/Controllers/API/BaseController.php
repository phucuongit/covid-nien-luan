<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = '',  $code = 200)
    {
    	$response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMsg, $errorLog = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'code' => $code,
            'message' => $errorMsg,
            'errors' => $errorLog
        ];
        return response()->json($response, $code);
    }
}