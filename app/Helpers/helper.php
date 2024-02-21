<?php

use Illuminate\Http\Request;

//$code = acc_codedef_generate('LDG202401', 14);
if (!function_exists('acc_codedef_generate')) {
    function acc_codedef_generate($first_digit, $patern_length)
    {
        $first_digit_num = strlen($first_digit);
        $last_digit = "";
        $i_end = $patern_length - $first_digit_num;
        for ($i = 0; $i < $i_end; $i++) {
            $last_digit = "0" . $last_digit;
        }
        return $first_digit . $last_digit;
        //acc_codedef_generate("10",5);
    }
}
if (!function_exists('acc_code_generate')) {
    function acc_code_generate($last_code, $patern_length, $first_digit_num)
    {
        $first_digit = substr($last_code, 0, $first_digit_num);
        $last_digit = (int) substr($last_code, $first_digit_num) + 1;
        $last_digit_length = strlen($last_digit);
        $i_end = $patern_length - $first_digit_num - $last_digit_length;
        for ($i = 0; $i < $i_end; $i++) {
            $last_digit = "0" . $last_digit;
        }
        return $first_digit . $last_digit;
        //acc_code_generate("10099",5,2);
    }
}

if (!function_exists('passw_gnr')) {
    function passw_gnr($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}

/**
 * success response method.
 *
 * @return \Illuminate\Http\Response
 */
function sendResponse($result, $message, $output = 'json')
{
    $response = [
        'success' => true,
        'data' => $result,
        'message' => $message,
    ];

    if ($output == 'json') {
        return response()->json($response, 200);
    } else {
        return (object) $response;
    }
}

/**
 * return error response.
 *
 * @return \Illuminate\Http\Response
 */
function sendError($error, $errorMessages = [], $code = 404, $output = 'json')
{
    $response = [
        'success' => false,
        'message' => $error,
    ];

    if (!empty($errorMessages)) {
        $response['data'] = $errorMessages;
    }

    if ($output == 'json') {
        return response()->json($response, $code);
    } else {
        return (object) $response;
    }
}

function setRequest($data)
{
    $request = new Request();
    $request->setMethod('POST');
    $request->request->add($data);
    return $request;
}
