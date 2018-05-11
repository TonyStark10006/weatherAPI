<?php
namespace App\Http\Controllers;

use App\Models\CNRegions;
use App\Traits\APIMsg;
use App\Utils\Crawler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class GetWeatherController extends Controller
{
    //
    use APIMsg;

    public function getWeather(Request $request)
    {
        $queryString = $request->query('city');

        if (empty($queryString)) {
            $city = $data = 'nothing found';
        } elseif (ctype_digit($queryString) && mb_strlen($queryString) == 6) {
            if ($city = self::getCityName((int) $queryString)) {
                //dd($city);
                $data = self::getWeatherMsg((int) self::getCityCode($city)[0]);
            } else {
                $city = $data = 'nothing found';
            }
        } else {
            if ($result = self::getCityCode($queryString)) {
                $data = self::getWeatherMsg((int) $result[0]);
                $city = $result[1];
            } else {
                $city = $data = 'nothing found';
            }
        }

        return gettype($data) == 'array' ? self::mergeResponse(self::$success, $data, 'city', $city) :
            self::mergeResponse(self::$failure, $data, 'city', $city);
    }

    public function getWeatherMsg(int $target)
    {
        if (Redis::keys($target)) {
            $wea = unserialize(Redis::get($target));
        } else {
            $crawler = new Crawler();
            $url = 'http://www.weather.com.cn/weather/' . $target . '.shtml';
            $originHtml = $crawler->download($url);
            //dd($url);
            if (!$originHtml) {
                Log::error('下载城市代码为' . $target . '的天气信息网页出错 - 错误信息: ' . $crawler->getErrorMsg());
                return 'nothing found';
            }

            $filtedHtml = $crawler->extraRule($originHtml,'/<[0-9]/', '&lt;3');
            $coldDress = $crawler->select($filtedHtml, "
            //ul[contains(@class, 'clearfix')]/li[contains(@class, 'li2')]//p | 
            //ul[contains(@class, 'clearfix')]/li[@class='li3 hot']/a/p"
            );
            $data = $crawler->select($filtedHtml,
                "//ul[contains(@class, 't clearfix')]/li[contains(@class, 'sky skyid')]");

            debugbar()->info($data);
            for ($j = 0, $z = 0; $j < count($data); $j++, $z = $z + 2) {
                $wea[$j][] = $crawler->select($data[$j], '//h1');
                $wea[$j][] = $crawler->select($data[$j], "//p[@class='wea']");
                if (gettype($winDirect = $crawler->select($data[$j], "//p[@class='win']/em//@title")) == 'array') {
                    $wea[$j][] = $crawler->select($data[$j], "//p[@class='tem']/span") . " - " .
                        $crawler->select($data[$j], "//p[@class='tem']/i");
                    $wea[$j][] = $winDirect[0] . " - " . $winDirect[1];
                } else {
                    $wea[$j][] = $crawler->select($data[$j], "//p[@class='tem']/i");
                    $wea[$j][] = $winDirect;
                }

                $wea[$j][] = preg_replace("/&lt;/", '<',
                    $crawler->select($data[$j], "//p[@class='win']//i"));
                $wea[$j][] = $coldDress[$z];
                $wea[$j][] = $coldDress[$z + 1];
            }
            Redis::set($target, serialize($wea));
            Redis::expire($target, 21600);
        }

        return $wea;
//        return response()->json(
//            array_merge($this->success, ['city' => $target['cityName']], ['data' => $wea]),
//            200, [], 256);
    }

    public function getCityCode(string $city)
    {
        $result = CNRegions::where([
                        ['city_name', 'like', '%' . $city . '%'],
                        ['china_weather_city_code', '!=', null]
                    ])->select('china_weather_city_code', 'city_name')->groupBy('city_name')->get();
        //dd($result[0]);
        //$crawler = new Crawler();
        //$result = $crawler->select(file_get_contents(__DIR__ . '/../../../../public/weatherCityCode.xml'),
        //    "//county[contains('{$city}', @name)]/@weathercode");
        return count($result) == 1 ? [$result[0]->china_weather_city_code, $result[0]->city_name] : false;
    }

    public function getCityName(int $cityCode)
    {
        return CNRegions::where('city_code', $cityCode)->value('city_name');
    }

    public function getCityCodeByInt(int $cityCode)
    {
        return CNRegions::where([
            ['city_name', $cityCode],
            ['china_weather_city_code', '!=', null]
        ])->value('china_weather_city_code');
    }

}
