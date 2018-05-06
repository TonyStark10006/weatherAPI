<?php
namespace App\Traits;

trait APIMsg
{
    public $success = ['status' => '200', 'msg' => 'YOU GOT IT'];

    public $failure = ['status' => '404', 'msg' => 'NO GOOD'];

    public function mergeResponse(array $status, array $data, $title = null)
    {
        if (empty($title)) {
            return response()->json(
                array_merge($status, ['data' => $data]),
                200, [], 256);
        } else {
            return response()->json(
                array_merge($status, [$title => $data]),
                200, [], 256);
        }
    }
}