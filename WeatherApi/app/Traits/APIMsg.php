<?php
namespace App\Traits;

trait APIMsg
{
    public static $success = ['status' => 200, 'msg' => 'YOU GOT IT'];

    public static $failure = ['status' => 404, 'msg' => 'NO GOOD'];

    public static function mergeResponse(array $status, $data, string $title = null, string $tContent = null)
    {
        if (empty($title)) {
            return response()->json(
                array_merge($status, ['data' => $data]),
                200, [], 256);
        } else {
            return response()->json(
                array_merge($status, ["{$title}" => $tContent, 'data' => $data]),
                200, [], 256);
        }
    }
}