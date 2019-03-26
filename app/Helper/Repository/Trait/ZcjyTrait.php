<?php
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Overtrue\EasySms\EasySms;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

//form表单提交处理 trait
trait ZcjyFormTrait{
    private $config = 
    [
        'id'                    => '唯一id',
        'name'                  => '名称',
        'username'              => '用户名',
        'email'                 => '邮箱',
        'first_name'            => '名',
        'last_name'             => '姓',
        'password'              => '密码',
        'enter_password'        => '确认密码',
        'password_confirmation' => '确认密码',
        'city'                  => '城市',
        'country'               => '国家',
        'address'               => '地址',
        'phone'                 => '电话',
        'mobile'                => '手机号',
        'age'                   => '年龄',
        'sex'                   => '性别',
        'gender'                => '性别',
        'day'                   => '天',
        'month'                 => '月',
        'year'                  => '年',
        'hour'                  => '时',
        'minute'                => '分',
        'second'                => '秒',
        'title'                 => '标题',
        'content'               => '内容',
        'description'           => '描述',
        'excerpt'               => '摘要',
        'date'                  => '日期',
        'time'                  => '时间',
        'available'             => '可用的',
        'size'                  => '大小',
        'subtitle'              => '副标题',
        'price'                 => '价格',
        'province'              => '省',
        'district'              => '区',
        'detail'                => '详细信息',
        'value'                 => '数值',
        'time_begin'            => '开始时间',
        'time_end'              => '结束时间',
        'image'                 => '图片',
        'product_num'           => '参与数量',
        'buy_limit'             => '限购数量',
        'expire_hour'           => '过期时间',
        'member'                => '成团人数',
        'base'                  => '基本金额',
        'weixin_qq'             => '微信或QQ',
        'num'                   => '数量',
        'id_card'               => '身份证号码',
        'face_image'            => '人脸照',
        'back_image'            => '背面国徽照',
        'hand_image'            => '手持身份证照',
        'type'                  => '类型',
        'tel'                   => '电话',
        'word'                  => '字段',
        'cout'                  => '数量',
        'product_id'            => '商品id'
    ];

    /**
     * [默认直接通过数组的值 否则通过数组的键]
     * @param  [type] $input      [description]
     * @param  array  $attr       [description]
     * @param  string $valueOrKey [description]
     * @return [type]             [description]
     */
    public function varifyInputParam($input,$attr=[],$valueOrKey='value')
    {
        #过滤空字符串
        $input = $this->filterNullInput($input);
        $status = false;
        if(!is_array($attr))
        {
            $attr = explode(',',$attr);
        }
        #一种是针对提交的指定键值
        if(count($attr)){
            foreach ($attr as $key => $val) 
            {
                if($valueOrKey == 'value')
                {
                    if(!array_key_exists($val,$input))
                    {
                        $status = $this->validation($val);
                        return $status;
                    } 
                    if(array_key_exists($val,$input) && $input[$val] == null )
                    {
                        $status = $this->validation($val,1);
                         return $status;
                    }
                }
                else
                {
                     if(!array_key_exists($key,$input))
                     {
                        $status = $this->validation($key);
                         return $status;
                     } 
                     if(array_key_exists($key,$input) &&  $input[$key] == null)
                     {
                        $status =  $this->validation($key,1);
                        return $status;
                    }
                }
            }
        }
        else{
           #另一种是带键值但值为空的情况
            foreach ($input as $key => $val) 
            {
                if(array_key_exists($key,$input))
                {
                    if($input[$key] == null)
                    {
                        $status = $this->validation($key,1);
                        return $status;
                    }
                }
            }
        }
        return $status;
    }

    /**
     * [过滤空的输入]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function filterNullInput($input)
    {
        foreach ($input as $key => $value) 
        {
            if(is_null($value) || $value == '' || empty($value) && $value != 0)
            {
               unset($input[$key]);
            }
        }
        return $input;
    }

     /**
     * [根据参数键值返回中文提示]
     * @param  [type] $key  [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function validation($key,$type=0)
    {
        $validation_arr = $this->getConfig();
        if(isset($validation_arr[$key])){
            return  '请输入'.$validation_arr[$key];
        }
        else{
            return  '请输入'.$key ;
        }
    }

    /**
     * 基础配置
     * @return [type] [description]
     */
    private function getConfig()
    {
        return $this->config;
    }
}

//常用函数 trait
trait ZcjyRepoTrait{

    private $obj;

    //阿里云短信发送配置
    private static $aliyun_sms_config = 
    [
        'access_key_id'=>'LTAIBZxu3Qvq95tQ',
        'access_key_secret'=>'iJ4OGZ3b11sMvAG4HjsfDywHlbjta9',
        'sign_name'=>'阿里云短信测试专用',
        'template'=>'SMS_101005146'
    ];

    public function Paginate($page = 15 ,$sort = 'created_at')
    {
        return $this->model()::orderBy($sort,'desc')->paginate($page);
    }

    /**
     * 简单guzzle请求func
     * @param  string $url    [description]
     * @param  string $method [description]
     * @param  array  $param  [description]
     * @return [type]         [description]
     */
    public static function simpleGuzzleRequest($url= '',$method='GET',$param= [])
    {
        try{
            $client = new Client();
            $url_suffix = '?';
            if(is_array($param) && count($param))
            {
                foreach ($param as $key => $value) 
                {
                    $url_suffix .= $key.'='.$value.'&';
                }
                $url_suffix = substr($url_suffix,0,strlen($url_suffix)-1); 
            }
            $url .= $url_suffix;
            $response = $client->request($method, $url);
            return $response->getBody();
        }catch(Exception $e){
            return '请求异常';
        }
    }

    /**
     * 发起guzzel请求
     */
    public static function guzzleRequest($request_config = array('url'=>'','method'=>'GET','form'=>'','headers'=>''), $type = "api")
    {
        try{
            $client = new Client();
            $response = $client->request($request_config['method'], $request_config['url'], [
                'headers' => isset($request_config['headers']) ? $request_config['headers'] : [] ,
                'form_params' => $request_config['form']
            ]);
            // return ($response);
            #解析结果
            $data = json_decode($response->getBody(),true);
            return zcjy_callback_data($data,0,$type);
        } catch (Exception $e) {
            return zcjy_callback_data('请求异常',1,$type);
        }
       
    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies  
    public static function curl($url,$post='',$cookie='', $returnCookie=0){
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
         curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
         curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
         if($post) 
         {
             curl_setopt($curl, CURLOPT_POST, 1);
             //http_build_query
             curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
         }
         if($cookie) {
             curl_setopt($curl, CURLOPT_COOKIE, $cookie);
         }
         curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
         curl_setopt($curl, CURLOPT_TIMEOUT, 10);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         $data = curl_exec($curl);
         if (curl_errno($curl)) {
             return curl_error($curl);
         }
         curl_close($curl);
         if($returnCookie){
             list($header, $body) = explode("\r\n\r\n", $data, 2);
             preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
             $info['cookie']  = substr($matches[1][0], 1);
             $info['content'] = $body;
             return $info;
         }else{
             $data = json_decode($data,true);
             return $data;
         }
     }

     //curl get
    public static function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dom = curl_exec($ch);
        curl_close($ch);
        return $dom;
    }

    //curl post
    public static function curl_post($url, $post_data)
    {
          //初始化
          $curl = curl_init();
          //设置抓取的url
          curl_setopt($curl, CURLOPT_URL, $url);
          //设置头文件的信息作为数据流输出
          curl_setopt($curl, CURLOPT_HEADER, 1);
         //设置获取的信息以文件流的形式返回，而不是直接输出。
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          //设置post方式提交
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          $result = json_decode(curl_exec($curl),1);
          //关闭URL请求
          curl_close($curl);
          return $result;
    }

    public static function make($class) 
    {
        $base_dir = app_path();
        
        $file = $base_dir . DIRECTORY_SEPARATOR . $class;
        
        if (file_exists($file)) {
            return new $file;
        } else {
            exit("can't find {$file}");
            
        }
    }

    /**
     * 发送短信验证码
     */
    public static function sendMobileCode($mobile,$aliyun_config=null)
    {

        if(is_null($aliyun_config))
        {
            $aliyun_config = self::$aliyun_sms_config;
        }

        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => $aliyun_config['access_key_id'],
                    'access_key_secret' => $aliyun_config['access_key_secret'],
                    'sign_name' => $aliyun_config['sign_name'],
                ]
            ],
        ];

        $easySms = new EasySms($config);

        $num = rand(1000, 9999); 

        $easySms->send($mobile, [
            'content'  => '验证码'.$num,
            'template' => $aliyun_config['template'],
            'data' => [
                'code' => $num
            ],
        ]);

        return $num;
    }

    /**
     * 直接获取必须参数
     * @param  [type]  $model        [description]
     * @param  boolean $return_array [description]
     * @return [type]                [description]
     */
    public static function modelRequiredParam($model,$return_array=false){
        if(method_exists($model,'model'))
        {
            $model = $model->model();
        }
        $requireds = $model::$rules;
        $attr = [];
        foreach ($requireds as $key => $value) 
        {
            array_push($attr,$key);
        }
        $attr = !$return_array ? implode(',',$attr) : $attr;
       return $attr;
    }

    /**
     * [处理类对象]
     * @param  [type] $obj [description]
     * @return [type]      [description]
     */
    private function dealObj($obj)
    {
        if(empty($obj))
        {
            $obj = $this;
        }
        $this->obj = $obj;
        return $obj;
    }

    /**
     * [冒泡排序]
     * @param  [type] $arr [description]
     * @return [type]      [description]
     */
    public static function bubbleSort($arr){
        $arrLen = count($arr);
        if($arrLen){
            #step1
            // for ($i=1; $i < $arrLen; $i++) { 
            //     for ($k=0; $k <$arrLen - $i ; $k++) { 
            //        if($arr[$k] > $arr[$k+1]){
            //              $temp = $arr[$k+1];
            //              $arr[$k+1] = $arr[$k];
            //              $arr[$k] = $temp;
            //        }
            //     }
            // }
            #step2
            for ($i=$arrLen;$i>0;$i--) { 
                for ($k=$arrLen-$i-1;$k>=0;$k--) { 
                     if($arr[$k] > $arr[$k+1]){
                        $temp = $arr[$k+1];
                        $arr[$k+1] = $arr[$k];
                        $arr[$k] = $temp;
                     }
                }
            }
            #step3
            // $arr_arr = []; 
            // foreach ($arr as $key1 => $val1) {
            //    if($key1>0){
            //     //dd($key1);
            //     foreach ($arr as $key2 => $val2) {
            //         if($key2 < $arrLen-$key1){
            //             if($arr[$key2] > $arr[$key2+1]){
            //                 $temp = $arr[$key2+1];
            //                 $arr[$key2+1] = $arr[$key2];
            //                 $arr[$key2] = $arr[$key2+1];
            //             }
            //         }

            //     }
            //    }
            // }
        }
        return $arr;
        }

    /**
     * [模型默认分页]
     * @param  [type] $obj  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public static function defaultPaginate($obj=null,$page=null,$created_at_sort='desc')
    {
        $obj = $this->dealObj($obj);
        return empty($page) ? $obj->orderBy('created_at',$created_at_sort)->paginate(15) : $obj->orderBy('created_at',$created_at_sort)->paginate($page);
    }

    /**
     * [初始化查询索引状态]
     * @param  [Repository / Model] $obj [description]
     * @return [type]                    [description]
     */
    public static function defaultSearchState($obj=null)
    {
       $obj = $this->dealObj($obj);
       return !empty(optional($obj)->model())
            ?($obj->model())::where('id','>',0)
            :$obj::where('id','>',0);
    }

    /**
     * [手动分页]
     * @param  [type]  $data    [description]
     * @param  [type]  $request [description]
     * @param  integer $perPage [description]
     * @return [type]           [description]
     */
    public function operatPaginate($data,$request,$perPage = 3)
    {
        if(!is_array($data)){
            $data = $data->toArray();
        }
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } 
        else {
            $current_page = 1;
        }
        $item = array_slice($data, ($current_page-1)*$perPage, $perPage);//$data为要分页的数组
        $totals = count($data);
        $paginator =new LengthAwarePaginator($item, $totals, $perPage, $current_page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        return $paginator;
    }

      /**
       * 截取指定字符串的指定前几位位置
       * @param  [type] $str [description]
       * @param  [type] $num [description]
       * @return [type]      [description]
       */
      public static function des($str, $num)
      {
                global $Briefing_Length; 
                mb_regex_encoding("UTF-8");     
                $Foremost = mb_substr($str, 0, $num); 
                $re = "<(\/?) 
            (P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI| 
            BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN)[^>]*(>?)"; 
                $Single = "/BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|BR/i";     

                $Stack = array(); $posStack = array(); 

                mb_ereg_search_init($Foremost, $re, 'i'); 

                while($pos = mb_ereg_search_pos()){ 
                    $match = mb_ereg_search_getregs(); 

                    if($match[1]==""){ 
                        $Elem = $match[2]; 
                        if(mb_eregi($Single, $Elem) && $match[3] !=""){ 
                            continue; 
                        } 
                        array_push($Stack, mb_strtoupper($Elem)); 
                        array_push($posStack, $pos[0]);             
                    }else{ 
                        $StackTop = $Stack[count($Stack)-1]; 
                        $End = mb_strtoupper($match[2]); 
                        if(strcasecmp($StackTop,$End)==0){ 
                            array_pop($Stack); 
                            array_pop($posStack); 
                            if($match[3] ==""){ 
                                $Foremost = $Foremost.">"; 
                            } 
                        } 
                    } 
                } 

                $cutpos = array_shift($posStack) - 1;     
                $Foremost =  mb_substr($Foremost,0,$cutpos,"UTF-8"); 
                return strip_tags($Foremost); 
        }

        //截取内容中的图片
        public static function get_content_img($text){   
            //取得所有img标签，并储存至二维数组 $match 中   
            preg_match_all('/(src)=("[^"]*")/i', $text, $matches);
          
            $images_arr = $matches[0];
            $match_arr = [];
            if(count($images_arr)){
                foreach ($images_arr as $key => $value) {
                    array_push($match_arr,substr($value,5));
                }   
            }
            return $match_arr;
        }

        /**
         * 计算两点地理坐标之间的距离
         * @param  Decimal $longitude1 起点经度
         * @param  Decimal $latitude1  起点纬度
         * @param  Decimal $longitude2 终点经度 
         * @param  Decimal $latitude2  终点纬度
         * @param  Int     $unit       单位 1:米 2:公里
         * @param  Int     $decimal    精度 保留小数位数
         * @return Decimal
         */
       public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2)
       {

            if(empty($longitude1) || empty($latitude1) || empty($longitude2) || empty($latitude2)){
                return '???';
            }

            $EARTH_RADIUS = 6370.996; // 地球半径系数
            $PI = 3.1415926;

            $radLat1 = $latitude1 * $PI / 180.0;
            $radLat2 = $latitude2 * $PI / 180.0;

            $radLng1 = $longitude1 * $PI / 180.0;
            $radLng2 = $longitude2 * $PI /180.0;

            $a = $radLat1 - $radLat2;
            $b = $radLng1 - $radLng2;

            $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
            $distance = $distance * $EARTH_RADIUS * 1000;

            if($unit==2){
                $distance = $distance / 1000;
            }

            return round($distance, $decimal);

        }
}


//商城常用 trait
trait ZcjyShopTrait{
    /**
     * 笛卡尔积
     * @return [type] [description]
     */
   public static function combineDika() {
        $data = func_get_args();
        $data = current($data);
        $cnt = count($data);
        $result = array();
        $arr1 = array_shift($data);
        foreach($arr1 as $key=>$item) 
        {
            $result[] = array($item);
        }       

        foreach($data as $key=>$item) 
        {                                
            $result = self::combineArray($result,$item);
        }
        return $result;
    }

    /**
     * 两个数组的笛卡尔积
     * @param unknown_type $arr1
     * @param unknown_type $arr2
     */
    public static function combineArray($arr1,$arr2) {         
        $result = array();
        foreach ($arr1 as $item1) 
        {
            foreach ($arr2 as $item2) 
            {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }
}