<?php
class getZTOCNRegions
{
    const URL = 'http://japi.zto.cn/zto/api_utf8/baseArea?msg_type=GET_AREA&data=';
    public $provinces;
    static $conn;

    public function __construct()
    {
        $this->provinces = json_decode(
            file_get_contents( self::URL . 0), true);
        self::$conn = new PDO("mysql:host=127.0.0.1;dbname=mall", "", "");
    }

    public function getRegions()
    {
        //获取省份的城市列表
        foreach ($this->provinces['result'] as $province) {
            //time_nanosleep(0, 500000000);
            $cites = json_decode(
                file_get_contents(
                    self::URL . $province['code'])
                , true);
            //获取城市的地级市或者区列表
            foreach ($cites['result'] as $city) {
                $districts = json_decode(
                    file_get_contents(
                        self::URL . $city['code'])
                    , true);
                if (count($districts['result']) < 2) {
                    self::insertRecord(
                        $province['code'], $province['fullName'], $city['code'],
                        $city['fullName']
                    );
                    echo '写入一个' . $city['fullName'] . '成功啦' . PHP_EOL;
                } else {
                    // 获取具体的区或者地级市名字
                    foreach ($districts['result'] as $district) {
                        self::insertRecord(
                            $province['code'], $province['fullName'], $city['code'],
                            $city['fullName'], $district['code'], $district['fullName']
                        );
                        echo '写入一个' . $district['fullName'] . '成功啦' . PHP_EOL;
                    }
                }

            }
        }
    }

    public function insertRecord($provinceCode, $provinceName, $cityCode, $cityName, $districtCode = null, $districtName = null)
    {
        $sql = "INSERT INTO `cn_regions` (
                `province_code`, `province_name`, `city_code`, `city_name`, `district_code`, `district_name`) 
                VALUES (
                '$provinceCode', '$provinceName', '$cityCode', '$cityName', '$districtCode', '$districtName'
                )";
        try {
            self::$conn->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}


$run = new getZTOCNRegions();
$run->getRegions();
echo PHP_EOL;
exit;