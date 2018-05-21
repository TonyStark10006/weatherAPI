<?php
namespace App\Utils;

class Crawler
{
    protected $data;
    public static $errorMsg;
    public static $domObj;
    public static $httpCode;

    /**
     * @return mixed
     */
    public static function getHttpCode()
    {
        return self::$httpCode;
    }

    /**
     * @return mixed
     */
    public static function getErrorMsg()
    {
        return self::$errorMsg;
    }

    public function __construct()
    {
        //
    }

    public static function download($url)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            //CURLOPT_ENCODING => "gzip",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT => 20
        ]);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
            self::$errorMsg = curl_error($ch);
            return false;
        }

        if ((self::$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) == 200) {
            $html = @mb_convert_encoding($html, 'UTF-8');
            curl_close($ch);
            return $html;
        }

        curl_close($ch);
        return false;

    }

    public static function select($html, $selector)
    {
        if (!is_object(self::$domObj)) {
            self::$domObj = new \DOMDocument();
        }

        //加入xml声明标签，保证二次筛选不会出现乱码
        @self::$domObj->loadHTML('<?xml encoding="UTF-8">' . $html);
        $task = new \DOMXPath(self::$domObj);
        $elements = $task->query($selector);
        $data = array();

        foreach ($elements as $element) {
            //根据标签类型保存信息，
            $nodeType = $element->nodeType;
            if ($nodeType == 1 && in_array($nodeType, array('img'))) {
                $content = $element->getAttribute('src');
            } elseif ($nodeType == 2 || $nodeType == 3 || $nodeType == 4) {
                $content = $element->nodeValue;
            } else {
                //去除选取标签本身
                $content = preg_replace(
                    array("#^<{$element->nodeName}.*>#isU","#</{$element->nodeName}>$#isU"),
                    array('', ''),
                    self::$domObj->saveXML($element)
                );
            }
            $data[] = $content;
        }

        if (empty($data)) {
            return false;
        }

        return count($data) > 1 ? $data : $data[0];
    }

    public static function extraRule($html, $pattern, $replacement)
    {
        return preg_replace($pattern, $replacement, $html);
    }

}