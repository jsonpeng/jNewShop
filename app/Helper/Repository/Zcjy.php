<?php

// #引入Trait
if (is_file(__DIR__ . '/Trait/ZcjyTrait.php')) {
    require_once __DIR__ . '/Trait/ZcjyTrait.php';
}

// #引入微信模块
if (is_file(__DIR__ . '/Wechat/Wechat.php')) {
    require_once __DIR__ . '/Wechat/Wechat.php';
}

// #引入阿里云OSS上传
if (is_file(__DIR__ . '/Aliyun/AliyunOssUpload.php')) {
    require_once __DIR__ . '/Aliyun/AliyunOssUpload.php';
}

use Illuminate\Support\Facades\Cache;

/**
 * 基类主文件 核心调用函数类
 */
class Zcjy
{
    #引用主函数库
	use ZcjyRepoTrait;

    #微信类
    public static function wechat()
    {
        return new Wechat();
    }

    #商城类
    public static function shop()
    {
        return new Shop();
    }

    #form类构建器
	public static function form()
	{
		return new ZcjyForm();
	}

    #文件上传
    public static function upload($set_type='oss',$upload_type='web',$user_id=null,$config=null)
    {
        if($set_type === 'oss')
        {
            #阿里云OSS文件上传
            return new AliyunOssUpload($upload_type,$user_id,$config);
        }
    }

    #laravel内部缓存
    public static function cache()
    {
        return Cache::class;
    }
}

/**
 * Form表单处理类
 */
class ZcjyForm
{
	use ZcjyFormTrait;
}

/**
 * 商城常用类
 */
class Shop
{
    use ZcjyShopTrait;
}


