# 一、 天气API
## 1.介绍
1. 目前支持各一级城市的天气预报查询，包括实时天气气温、最高最低温度、风力，感冒等指数。 <br />
2. 七天(包括今天)天气、风力、最低最高温度等。 <br />
3. 实时爬取中国天气网的数据，Redis保存六个钟，请求频率最高80次/秒。 <br />

## 2.用法及详细内容

调用地址：<https://www.baidu.com>

请求方式：GET

返回类型：JSON

### 2.1请求参数

| 字段  | 数据类型  | 是否为空  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
| city  | string / int  | 必填  | 城市中文名称或者城市代码  |

### 2.2请求示例(PHP)　

```php
<?php
    $url = "http://localhost";
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $ch, CURLOPT_ENCODING, "gzip");
    $weather_messages = curl_exec ( $ch );
    var_dump(curl_exec($curl));
	
```

### 2.3 调用成功返回示例(JSON)　

```json
{
    "status": 200,
    "msg": "YOU GOT IT",
    "city": "北京市",
    "updateTime": "2018-08-13 11:30更新",
    "data": [
        {
            "date": "31日（今天）",
            "text": "晴",
            "icon": "d00",
            "tem": "35 - 20℃",
            "winDirect": "南风 转 西北风",
            "winPower": "<3级",
            "tips": "天气有点热，运动多补水。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        },
        {
            "date": "1日（明天）",
            "text": "晴",
            "icon": "d00",
            "tem": "36 - 21℃",
            "winDirect": "南风 转 南风",
            "winPower": "3-4级转<3级",
            "tips": "天热风大，可选择低强度运动。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        },
        {
            "date": "2日（后天）",
            "text": "多云",
            "icon": "d01",
            "tem": "36 - 21℃",
            "winDirect": "南风 转 西北风",
            "winPower": "3-4级转<3级",
            "tips": "天热风大，可选择低强度运动。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        },
        {
            "date": "3日（周日）",
            "text": "多云转晴",
            "icon": "d01",
            "tem": "31 - 19℃",
            "winDirect": "西北风 转 西北风",
            "winPower": "<3级",
            "tips": "天气较舒适，减肥正当时。",
            "dressTips": "适合穿T恤、短薄外套等夏季服装。"
        },
        {
            "date": "4日（周一）",
            "text": "多云",
            "icon": "d01",
            "tem": "34 - 20℃",
            "winDirect": "南风 转 南风",
            "winPower": "<3级",
            "tips": "天气有点热，运动多补水。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        },
        {
            "date": "5日（周二）",
            "text": "多云",
            "icon": "d01",
            "tem": "34 - 21℃",
            "winDirect": "南风 转 东北风",
            "winPower": "<3级",
            "tips": "天气有点热，运动多补水。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        },
        {
            "date": "6日（周三）",
            "text": "晴转多云",
            "icon": "d00",
            "tem": "35 - 22℃",
            "winDirect": "东南风 转 北风",
            "winPower": "<3级",
            "tips": "天气有点热，运动多补水。",
            "dressTips": "建议穿短衫、短裤等清凉夏季服装。"
        }
    ]
}

```

### 2.4 调用失败返回示例(JSON)　

```json
{
    "status": 404,
    "msg": "NO GOOD",
    "city": "nothing found",
    "updateTime": "",
    "data": "nothing found"
}

```
#### 错误代码及原因
|  code |  原因 |
| ------------ | ------------ |
|  200 |  成功 |
|  404 |  查询不到相关结果 |


### 如有问题可发邮件到下面邮箱
www&#64;qq.com

# 二、 城市查询API
## 1.介绍
1. 城市列表查询，支持省、一级 <br />

## 2.用法及详细内容

调用地址：<https://www.baidu.com>

请求方式：GET

返回类型：JSON

### 2.1请求参数

| 字段  | 数据类型  | 是否为空  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
| code  | int  | 必填  | 城市代码(0为查询所有省)  |

### 2.2请求示例(PHP)　

```php
<?php
    $url = "http://localhost";
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $ch, CURLOPT_ENCODING, "gzip");
    $weather_messages = curl_exec ( $ch );
    var_dump(curl_exec($curl));
	
```

### 2.3 调用成功返回示例(JSON)　

```json
{
    "message": "",
    "status": true,
    "statusCode": "R5",
    "result": [
        {
            "code": 110000,
            "fullName": "北京"
        },
        {
            "code": 120000,
            "fullName": "天津"
        },
        {
            "code": 130000,
            "fullName": "河北省"
        },
        {
            "code": 140000,
            "fullName": "山西省"
        },
        {
            "code": 150000,
            "fullName": "内蒙古自治区"
        },
        {
            "code": 210000,
            "fullName": "辽宁省"
        },
        {
            "code": 220000,
            "fullName": "吉林省"
        },
        {
            "code": 230000,
            "fullName": "黑龙江省"
        },
        {
            "code": 310000,
            "fullName": "上海"
        },
        {
            "code": 320000,
            "fullName": "江苏省"
        },
        {
            "code": 330000,
            "fullName": "浙江省"
        },
        {
            "code": 340000,
            "fullName": "安徽省"
        },
        {
            "code": 350000,
            "fullName": "福建省"
        },
        {
            "code": 360000,
            "fullName": "江西省"
        },
        {
            "code": 370000,
            "fullName": "山东省"
        },
        {
            "code": 410000,
            "fullName": "河南省"
        },
        {
            "code": 420000,
            "fullName": "湖北省"
        },
        {
            "code": 430000,
            "fullName": "湖南省"
        },
        {
            "code": 440000,
            "fullName": "广东省"
        },
        {
            "code": 450000,
            "fullName": "广西壮族自治区"
        },
        {
            "code": 460000,
            "fullName": "海南省"
        },
        {
            "code": 500000,
            "fullName": "重庆"
        },
        {
            "code": 510000,
            "fullName": "四川省"
        },
        {
            "code": 520000,
            "fullName": "贵州省"
        },
        {
            "code": 530000,
            "fullName": "云南省"
        },
        {
            "code": 540000,
            "fullName": "西藏自治区"
        },
        {
            "code": 610000,
            "fullName": "陕西省"
        },
        {
            "code": 620000,
            "fullName": "甘肃省"
        },
        {
            "code": 630000,
            "fullName": "青海省"
        },
        {
            "code": 640000,
            "fullName": "宁夏回族自治区"
        },
        {
            "code": 650000,
            "fullName": "新疆维吾尔自治区"
        },
        {
            "code": 710000,
            "fullName": "台湾"
        },
        {
            "code": 810000,
            "fullName": "香港特别行政区"
        }
    ]
}

```

### 2.4 调用失败返回示例(JSON)　

```json
{
    "message": "",
    "status": true,
    "statusCode": "R5"
}


```
#### 错误代码及原因
无

