<?php

namespace App\Http\Controllers;

use App\Models\CNRegions;
use Illuminate\Http\Request;

class GetRegionsController extends Controller
{
    //
    public $queryCode;
    const MSG = ['message' => '', 'status' => true, 'statusCode' => 'R5'];

    public function __construct(Request $request)
    {
        debugbar()->info(gettype($request->query('code')));
        $this->queryCode = filter_var($request->query('code'), FILTER_SANITIZE_NUMBER_INT);
    }

    public function getRegions()
    {
        if ($this->queryCode != '') {
            if ($this->queryCode == '0') {
                return self::getAllRegions();
            }

            if (substr($this->queryCode, 3, -2) !== '00') {
                //dd($this->queryCode);
                return self::getCites($this->queryCode);
            }
        }

        return response()->json(array_merge(self::MSG, ['result' => 'nothing found']), '200', [], 256);
    }

    public function getAllRegions()
    {
        $results = CNRegions::select('province_code', 'province_name')->groupBy('province_code')->get();

        return self::outPutResult($results);
    }

    public function getCites($provinceCode)
    {
        $results = CNRegions::where('province_code', $provinceCode)
            ->groupBy('city_code')->select('city_code', 'city_name')->get();

        return self::outPutResult($results, 2);
    }

    public function outPutResult($results, $type = 1)
    {
        $data = [];
        if ($type == 1) {
            foreach ($results as $key => $result) {
                $data['result'][$key]['code'] = $result->province_code;
                $data['result'][$key]['fullName'] = $result->province_name;
            }
        }

        if ($type == 2) {
            foreach ($results as $key => $result) {
                $data['result'][$key]['code'] = $result->city_code;
                $data['result'][$key]['fullName'] = $result->city_name;
            }
        }

        return response()->json(array_merge(self::MSG, $data), '200', [], 256);
    }
}
