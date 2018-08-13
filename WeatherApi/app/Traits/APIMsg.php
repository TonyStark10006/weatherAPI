<?php
namespace App\Traits;

trait APIMsg
{
    public static $success = ['status' => 200, 'msg' => 'YOU GOT IT'];

    public static $failure = ['status' => 404, 'msg' => 'NO GOOD'];

    public static function mergeResponse(array $status, $data, array $appendContent = null)
    {
        if (empty($appendContent)) {
            return response()->json(
                array_merge($status, $data),
                200, [], 256);
        } else {
            return response()->json(
                array_merge($status, $appendContent, $data),
                200, [], 256);
        }
    }
}
