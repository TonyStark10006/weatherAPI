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
            CURLOPT_CONNECTTIMEOUT => 5,
            //CURLOPT_ENCODING => "gzip",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.113 Safari/537.36"
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

    public static function select($html, $selector, $remove = false)
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
            if ($remove) {
                // xpath取走查询到的节点，保存原来存放在domObj的html数据即可
                $content = self::$domObj->saveXML($element);
            } else {
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

    public static function remove($html, $selector)
    {
        return self::select($html, $selector, $remove = true);
    }

}
