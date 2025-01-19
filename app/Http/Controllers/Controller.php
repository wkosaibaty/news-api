<?php

namespace App\Http\Controllers;

class Controller
{
    public function sendResponse($result, $message = 'success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result
        ], $code);
    }
}
