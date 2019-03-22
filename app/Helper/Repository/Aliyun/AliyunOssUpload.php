<?php
USE OSS\OssClient;
USE OSS\Core\OssException;

//处理成功结果状态码
define('SUCCESS_RESULT',0);
//处理失败结果状态码
define('FAIL_RESULT',1);

Class AliyunOssUpload{

    //上传配置
    private $config = [
        'accessKeyId'       => 'LTAIDburTeybOnfl',
        'accessKeySecret'   => 'LgThXJgMMZSDsw4rQbMekEhpKxkjMO',
        'bucketName'        => 'yunlike-cloud',
        'endpoint'          => 'http://oss-cn-beijing.aliyuncs.com'
    ];

    private $user_id = null;
    private $upload_type = 'web';

    public function __construct($upload_type='web',$user_id=null,$config=null){
        $this->upload_type = $upload_type;
        if(!empty($user_id))
        {
            $this->user_id = $user_id;
        }
        $this->init($config);
    }

    /**
     * 初始化
     * @param  [type] $configition [description]
     * @return [type]              [description]
     */
    public function init($configition=null){
        $config = $this->config;
        if(!is_null($configition))
        {
            $config = $configition;
            $this->config = $configition;
        }
        $this->Access_Key   = $config['accessKeyId'];
        $this->Secret_Key   = $config['accessKeySecret'];
        $this->bucket       = $config['bucketName'];
        $this->endpoint     = $config['endpoint'];
    }

    /**
     * 通用上传
     * @param  string $upload_file_type  [description]
     * @param  string $ossUploadBasePath [description]
     * @return [type]                    [description]
     */
    public  function ossUpload($upload_file_type = 'file',$ossUploadBasePath = 'upload/')
    {
        #OSS存储路径
        $ossUploadPath = $ossUploadBasePath.$upload_file_type;

        #post提交
        if($_POST)
        {
            foreach($_POST as $base64Image)
            {
                #base64处理
                $result = $this->base64_upload($base64Image,$upload_file_type);
                #成功处理
                if (isset($result['message']))
                {
                    $fileResult = &$result['message'];
                    $filePath = $fileResult['path'] . $fileResult['name'];
                    $ossFileName = implode('/', [$ossUploadPath,date('Ymd'),$fileResult['name']]);
                    try {
                        $config = $this->config;
                        $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
                        $result = $ossClient->uploadFile($config['bucketName'], $ossFileName, $filePath);
                        /*组合返回数据*/
                        $arr = [
                            'oss_url' => $result['info']['url'],  //上传成功后返回的该图片的oss地址
                            'relative_path' => $ossFileName     //数据库保存名称(相对路径)
                        ];
                    } catch (OssException $e) {
                        return zcjy_callback_data($e->getCode() . $e->getMessage(),FAIL_RESULT,$this->upload_type);
                    }finally {
                        unlink($filePath);
                    }
                    return  zcjy_callback_data($arr['oss_url'],SUCCESS_RESULT,$this->upload_type);
                }
                #失败处理 返回结果
                return zcjy_callback_data($result,FAIL_RESULT,$this->upload_type);
            }
            
        }
        else
        {
            #文件上传 dropzone.js等插件
            if($_FILES)
            {
                $fileAll = $_FILES;
                foreach($fileAll as $file)
                {
                    if($file['error']===0)
                    {
                        $name = $file['name'];
                        $format = strrchr($name, '.');//截取文件后缀名如 (.jpg)

                        #处理文件规范
                        $filter = $this->dealFileRule($upload_file_type,$format);

                        if($filter)
                        {
                            return zcjy_callback_data('文件格式不在允许范围内哦',FAIL_RESULT,$this->upload_type);
                        }

                        // 尝试执行
                        try {

                            $config = $this->config;
                            //实例化对象 将配置传入
                            $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
                            //这里是有sha1加密 生成文件名 之后连接上后缀
                            $fileName = $ossUploadPath.'/'. $_SERVER['SERVER_NAME'] .'/'. date("Ymd") . '/' ;

                            if(!empty($this->user_id))
                            {
                                $fileName .= 'user/'.$this->user_id.'/';
                            }

                            $fileName = $fileName.sha1(date('YmdHis', time()) . uniqid()) . $format;

                            if($upload_file_type == 'video')
                            {
                                $result = $ossClient->multiuploadFile($config['bucketName'], $fileName, $file['tmp_name']);
                            }
                            else{
                                //执行阿里云上传
                                $result = $ossClient->uploadFile($config['bucketName'], $fileName, $file['tmp_name']);
                            }
                         
                            /*组合返回数据*/
                            $arr = [
                                'oss_url' => $result['info']['url'],  //上传资源地址
                                'relative_path' => $fileName     //数据库保存名称(相对路径)
                            ];
                        } catch (OssException $e) {
                            return zcjy_callback_data($e->getMessage(),FAIL_RESULT,$this->upload_type);
                        }
                        //将结果返回
                        return zcjy_callback_data($arr['oss_url'],SUCCESS_RESULT,$this->upload_type);
                    }
                    return zcjy_callback_data('文件不存在',FAIL_RESULT,$this->upload_type);
                }
            }
        }
    }

    /**
     * 将Base64数据转换成二进制并存储到指定路径
     * @param        $base64
     * @param string $path
     *
     * @return array
     */
    private function base64_upload($base64,$upload_file_type='file',$local_path = './upload/temp/') 
    {
        $data = explode(',',$base64);
        trace($data,'api');
        unset($base64);
        if (count($data) !== 2){
            return'文件格式错误';
        }
        if (preg_match('/^(data:\s*'.$upload_file_type.'\/(\w+);base64)/', $data[0], $result)){

            #处理文件规范
            $filter = $this->dealFileRule($upload_file_type,$result[2]);

            if($filter)
            {
                return '文件格式不在允许范围内哦';
            }

            $image_name = md5(uniqid()).'.'.$result[2];
            $image_path = $local_path;
            $image_file = $image_path . $image_name;
            if(!file_exists($image_path)){
                mkdir($image_path, 0777, true); 
            }
            //服务器文件存储路径
            try {
                if (file_put_contents($image_file, base64_decode($data[1]))) {
                    return  zcjy_callback_data(['name' => $image_name, 'path' => $image_path]);
                } else {
                    return  '文件保存失败';
                }
            }catch (\Exception $e){
                return $e->getMessage();
            }
        }
        return '文件格式错误';
    }

    /**
     * 处理上传文件规则
     * @param  string $upload_file_type [description]
     * @param  [type] $format           [description]
     * @return [type]                   [description]
     */
    private function dealFileRule($upload_file_type='file',$format)
    {
        $status = false;
        if($upload_file_type == 'image')
        {
              $allow_type = ['.jpg', '.jpeg', '.gif', '.bmp', '.png'];
              if (!in_array($format, $allow_type)) 
              {
                    $status = '文件格式不在允许范围内哦';
              }
        }
        return $status;
    }

    /**
     * 处理私有化上传
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    private function dealPivateUploadPath($path)
    {
        return $path.'?Expires='.time().'&OSSAccessKeyId';
    }

}