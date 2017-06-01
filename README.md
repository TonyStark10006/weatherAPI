# weatherAPI
## 介绍

## 详细内容

调用地址：<https://www.baidu.com>

请求方式：GET

返回类型：JSON

### 请求参数

|  字段 | 数据类型  | 是否为空  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
| appkey  | string  | 必填  | 分配给用户的身份验证码  |
| city  | string  | 必填  | 查询城市名字  |

### 请求示例(php)　

```php
<?php
    $url = "http://localhost";
    //$ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    //curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    //curl_setopt ( $ch, CURLOPT_POST, 1 );//启用POST提交
    curl_setopt ( $ch, CURLOPT_ENCODING, "gzip");
    $weather_messages = curl_exec ( $ch );
	
```

### 接口返回示例(json)　

```json
{
    "Code": "200", 
    "Message": "OK", 
    "Data": {
        "city": "广州", 
        "temperature": "27", 
        "tips": "各项气象条件适宜，发生感冒机率较低。但请避免长期处于空调房间中，以防感冒。", 
        "low_temp_today": "低温 25℃", 
        "high_temp_today": "高温 31℃", 
        "type_today": "小雨", 
        "wind_direction_today": "西南风", 
        "wind_power_today": "3-4级", 
        "low_temp_tomorrow": "低温 24℃", 
        "high_temp_tomorrow": "高温 29℃", 
        "type_tomorrow": "中雨", 
        "wind_direction_tomorrow": "无持续风向", 
        "wind_power_tomorrow": "微风级", 
        "update_time": "2017-06-01 07:00:20"
    }
}
```
