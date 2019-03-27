<?php

namespace App\Repositories;

use App\Repositories\AddressRepository;
use App\Repositories\BannerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CouponRepository;
use App\Repositories\CouponUserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SpecProductPriceRepository;
use App\Repositories\OrderPrompRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\TeamSaleRepository;
use App\Repositories\FlashSaleRepository;
use App\Repositories\NoticeRepository;
use App\Repositories\SettingRepository;
use App\Repositories\StoreRepository;
use App\Repositories\ProjectsRepository;
use App\Repositories\CatsRepository;
use App\Repositories\CardRepository;
use App\Repositories\CertsRepository;
use App\Repositories\ProductEvalRepository;
use App\Repositories\CustomerServiceRepository;
use App\Repositories\KeFuFeedBackRepository;
use App\Repositories\CreditLogRepository;
use App\Repositories\UserRepository;
use App\Repositories\StatRepositoryRepository;
use App\Repositories\ShopTimesRepository;

use Auth;
use Config;
use ShoppingCart;
use Carbon\Carbon;
use App\Models\CouponUser;
use App\Models\UserLevel;
use App\Models\CreditLog;
use App\Models\MoneyLog;
use App\Models\RefundLog;
use App\Models\OrderAction;
use App\Models\DistributionLog;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\User;
use Log;

use Image;
use EasyWeChat\Factory;
use App\Events\OrderPay;
use Overtrue\EasySms\EasySms;

//支付宝
use AlipayTradeService;
use AlipayTradeWapPayContentBuilder;
use AopClient;
use AlipayFundTransToaccountTransferRequest;
use Excel;
use Request;

class CommonRepository
{
    private $addressRepository;
    private $productRepository;
    private $couponRepository;
    private $categoryRepository;
    private $couponUserRepository;
    private $specProductPriceRepository;
    private $orderPrompRepository;
    private $orderRepository;
    private $cityRepository;
    private $bannerRepository;
    private $countryRepository;
    private $teamSaleRepository;
    private $flashSaleRepository;
    private $noticeRepository;
    private $settingRepository;
    private $storeRepository;
    private $projectsRepository;
    private $catsRepository;
    private $cardRepository;
    private $certsRepository;
    private $productEvalRepository;
    private $customerServiceRepository;
    private $keFuFeedBackRepository;
    private $creditLogRepository;
    private $userRepository;
    private $statRepositoryRepository;
    private $ShopTimesRepository;
    public function __construct(
        AddressRepository $addressRepo,
        CouponRepository $couponRepo, 
        CategoryRepository $categoryRepo, 
        CouponUserRepository $couponUserRepo, 
        ProductRepository $productRepo,
        SpecProductPriceRepository $specProductPriceRepo,
        OrderRepository $orderRepo,
        OrderPrompRepository $orderPrompRepo,
        CityRepository $cityRepo,
        BannerRepository $bannerRepo,
        CountryRepository $countryRepo,
        TeamSaleRepository $teamSaleRepo,
        FlashSaleRepository $flashSaleRepo,
        NoticeRepository $noticeRepo,
        SettingRepository $settingRepo,
        StoreRepository $storeRepo,
        ProjectsRepository $ProjectsRepo,
        CatsRepository $catsRepo,
        CardRepository $cardRepo,
        CertsRepository $certsRepo,
        ProductEvalRepository $productEvalRepo,
        CustomerServiceRepository $customerServiceRepo,
        KeFuFeedBackRepository $keFuFeedBackRepo,
        CreditLogRepository $creditLogRepo,
        UserRepository $userRepo,
        StatRepositoryRepository $statRepo,
        ShopTimesRepository $ShopTimesRepo
    )
    {
        $this->addressRepository = $addressRepo;
        $this->productRepository = $productRepo;
        $this->couponRepository = $couponRepo;
        $this->categoryRepository = $categoryRepo;
        $this->couponUserRepository = $couponUserRepo;
        $this->specProductPriceRepository = $specProductPriceRepo;
        $this->orderPrompRepository = $orderPrompRepo;
        $this->orderRepository = $orderRepo;
        $this->cityRepository=$cityRepo;
        $this->bannerRepository = $bannerRepo;
        $this->countryRepository = $countryRepo;
        $this->teamSaleRepository = $teamSaleRepo;
        $this->flashSaleRepository = $flashSaleRepo;
        $this->noticeRepository = $noticeRepo;
        $this->settingRepository =$settingRepo;
        $this->storeRepository = $storeRepo;
        $this->projectsRepository = $ProjectsRepo;
        $this->catsRepository = $catsRepo;
        $this->cardRepository = $cardRepo;
        $this->certsRepository = $certsRepo;
        $this->productEvalRepository = $productEvalRepo;
        $this->customerServiceRepository = $customerServiceRepo;
        $this->keFuFeedBackRepository = $keFuFeedBackRepo;
        $this->creditLogRepository = $creditLogRepo;
        $this->userRepository = $userRepo;
        $this->statRepositoryRepository = $statRepo;
        $this->ShopTimesRepository = $ShopTimesRepo;
    }

    public function ShopTimesRepo()
    {
        return $this->ShopTimesRepository;
    }

    public function statRepo(){
        return $this->statRepositoryRepository;
    }

    public function userRepo(){
        return $this->userRepository;
    }

    public function creditLogRepo(){
        return $this->creditLogRepository;
    }

    public function keFuFeedBackRepo(){
        return $this->keFuFeedBackRepository;
    }

    public function customerServiceRepo(){
        return $this->customerServiceRepository;
    }

    public function productEvalRepo(){
        return $this->productEvalRepository;
    }

    public function certsRepo(){
        return $this->certsRepository;
    }

    public function cardRepo(){
        return $this->cardRepository;
    }

    public function catsRepo(){
        return $this->catsRepository;
    }
    
    public function projectsRepo(){
        return $this->projectsRepository;
    }

    public function storeRepo(){
        return $this->storeRepository;
    }

    public function addressRepo(){
        return $this->addressRepository;
    }

    public function settingRepo(){
        return $this->settingRepository;
    }

    public function bannerRepo()
    {
        return $this->bannerRepository;
    }

    public function orderRepo()
    {
        return $this->orderRepository;
    }

    public function countryRepo()
    {
        return $this->countryRepository;
    }

    public function categoryRepo()
    {
        return $this->categoryRepository;
    }

    public function teamSaleRepo()
    {
        return $this->teamSaleRepository;
    }

    public function flashSaleRepo()
    {
        return $this->flashSaleRepository;
    }

    public function productRepo()
    {
        return $this->productRepository;
    }

    public function noticeRepo()
    {
        return $this->noticeRepository;
    }

    /**
     * [通过本地图片地址转成base64编码]
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function dealImgToBase64($file){
        if($fp = fopen($file,"rb", 0)) 
        { 
            $gambar = fread($fp,filesize($file)); 
            fclose($fp); 
            $base64 = chunk_split(base64_encode($gambar)); 
            // 输出
            $encode = $base64; 
            return $encode; 
        } 
        else{
            return null;
        }
    }

    /**
     * 处理身份证的图像
     * @param  [type]  $card_path [description]
     * @param  integer $type      [1正面,2反面]
     * @return [type]             [description]
     */
    public function dealCardImage($card_path,$type=1){
        $base64_img = $this->dealImgToBase64($card_path);
        if(!empty($base64_img)){
            $bodys = "pic=".$base64_img.'&type='.$type;
            $host = "https://api05.aliyun.venuscn.com";
            $path = "/ocr/id-card";
            $method = "POST";
            $appcode = "ff2f203e351543c2ac32cbce9fff252d";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            #根据API的要求，定义相对应的Content-Type
            array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
            $querys = "";
            $url = $host . $path;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            #返回时是否带头信息
            curl_setopt($curl, CURLOPT_HEADER, false);
            if (1 == strpos("$".$host, "https://"))
            {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
            $output = curl_exec($curl);
            curl_close($curl);
            return json_decode($output,true);
        }
        else{
            return null;
        }
    }


    /**
     * 【微信版】用户提现金额到用户钱包余额 给
     * @param  [type] $input  [description]
     * @param  [type] $user   [description]
     * @param  string $reason [description]
     * @return [type]         [description]
     */
    public function companyGiveUserMoney($input,$user,$reason='提现'){
           //Log::info(Config::get('wechat.payment.default'));
           $app = Factory::payment(Config::get('wechat.payment.default'));
           $result = $app->transfer->toBalance([
                'partner_trade_no' => time(), // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                'openid' => $user->openid,
                'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                're_user_name' => $user->nickname, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                'amount' => $input['price']*100, // 企业付款金额，单位为分最少1元
                'desc' => $reason.$input['price'].'元到钱包余额', // 企业付款操作说明信息。必填
                'spbill_create_ip' => env('ip','118.190.201.81')//服务器ip地址
          ]);
         // $result = json_encode($result);
          //Log::info($result);
          $status = false;
          //$result  = optional($result);
          if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
            // $log->update(['status'=>'已完成']);
              #扣除余额
              $user->update(['user_money'=>$user->user_money-$input['current_price']]);
              #添加余额记录
              app('commonRepo')->addMoneyLog($user->user_money,-$input['current_price'],'提现'.$input['price'].'元到微信钱包,.额外扣除手续费'.$input['current_price']-$input['price'].'元',5,$user->id);
          }
          else{
            $status = true;

          }
          return $status;
    }


    /**
     * 【支付宝】用户提现余额到支付宝钱包
     * @param  [type] $input [description]
     * @param  [type] $user  [description]
     * @return [type]        [description]
     */
    public function alipayGiveUserMoney($input,$user)
    {
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $config = Config::get('alipay');
        #app_id
        $aop->appId = $config['app_id'];
        #开发者私钥
        $aop->rsaPrivateKey = $config['merchant_private_key'];
        #支付宝公钥
        $aop->alipayrsaPublicKey= $config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new AlipayFundTransToaccountTransferRequest();
        $request->setBizContent("{" .
        "\"out_biz_no\":\"".time()."\"," .
        "\"payee_type\":\"ALIPAY_LOGONID\"," .
        "\"payee_account\":\"".$user->mobile."\"," .
        "\"amount\":\"".$input['price']."\"," .
        "\"payer_show_name\":\"商城提现到账\"," .
        "\"payee_real_name\":\"".$user->name."\"," .
        "\"remark\":\"商城提现到账\"" .
        "}");
        $result = $aop->execute ($request); 

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        $status = false;
        if(!empty($resultCode) && $resultCode == 10000){
               #扣除余额
              $user->update(['user_money'=>$user->user_money-$input['current_price']]);
              #添加余额记录
              app('commonRepo')->addMoneyLog($user->user_money,-$input['current_price'],'提现'.$input['price'].'元到支付宝余额,.额外扣除手续费'.$input['current_price']-$input['price'].'元',5,$user->id);
        } else {
            $status = true;
        }
        return $status;
    }


    /**
     * [根据参数键值返回中文提示]
     * @param  [type] $key  [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function validation($key,$type=0){
        $validation_arr = Config::get('validation');
        if(isset($validation_arr[$key])){
            return '请输入'.$validation_arr[$key];
        }
        else{
            return '请输入'.$key;
        }
    }
    
    /**
     * [过滤空的输入]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function filterNullInput($input){
        foreach ($input as $key => $value) {
            if(is_null($value) || $value == '' || empty($value) && $value != 0){
               unset($input[$key]);
            }
        }
        return $input;
    }
    /**
     * [默认直接通过数组的值 否则通过数组的键]
     * @param  [type] $input      [description]
     * @param  array  $attr       [description]
     * @param  string $valueOrKey [description]
     * @return [type]             [description]
     */
    public function varifyInputParam($input,$attr=[],$valueOrKey='value'){
        $status = false;
        if(!is_array($attr)){
            $attr = explode(',',$attr);
        }
        #一种是针对提交的指定键值
        if(count($attr)){
            foreach ($attr as $key => $val) {
                if($valueOrKey == 'value'){
                    if(!array_key_exists($val,$input)){
                        $status = $this->validation($val);
                    } 
                    if(array_key_exists($val,$input) && $input[$val] == null ){
                        $status = $this->validation($val,1);
                    }
                }
                else{
                     if(!array_key_exists($key,$input)){
                        $status = $this->validation($key);
                     } 
                     if(array_key_exists($key,$input) &&  $input[$key] == null){
                        $status =  $this->validation($key,1);
                    }
                }
            }
        }
        else{
           #另一种是带键值但值为空的情况
            foreach ($input as $key => $val) {
                if(array_key_exists($key,$input)){
                    if($input[$key] == null){
                        $status = $this->validation($key,1);
                    }
                }
            }
        }
        return $status;
    }


    /**
     * [检查认证]
     * @param  [type] $user        [description]
     * @param  string $attach_word [description]
     * @param  string $api_type    [description]
     * @return [type]              [description]
     */
    public function varifyCert($user,$attach_word='您当前',$api_type="web"){
        if(empty($user)){
            return zcjy_callback_data('未知错误',1,$api_type);
        }
        $status = false;
        $cert = $user->cert()->first();
        if(empty($cert)){
            return zcjy_callback_data($attach_word.'未认证',1,$api_type);
        }

        if($cert->status == '审核中' || $cert->status =='未通过'){
            return zcjy_callback_data($attach_word.'认证审核中或未通过审核',1,$api_type);
        }
        return $status;
    }

    /**
     * [为数据添加星期]
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    public function attachWeek($list){
        $week_arr = [
           '周日',
           '周一',
           '周二',
           '周三',
           '周四',
           '周五',
           '周六'
        ];
        if(count($list)){
            foreach ($list as $key => $val) {
                if(isset($val->created_at)){
                    $val['week'] =  isset($week_arr[$val->created_at->dayOfWeek]) ? $week_arr[$val->created_at->dayOfWeek] : null;
                }
            }
        }
        return $list;
    }

    /**
     * 处理时间
     * @param  [type] $items  [description]
     * @param  string $format [description]
     * @param  string $time   [description]
     * @param  string $date   [description]
     * @return [type]         [description]
     */
    public function dealTimeFormat($items,$format='m-d',$time='time',$date='created_at')
    {
        foreach ($items as $key => $item) 
        {
            if(isset($item->created_at))
            {
                $item[$time] = Carbon::parse($item->{$date})->format($format);
            }
        }
        return $items;
    }

    //计算运费
    public function freight($address, $items)
    {
        if (empty($address)) {
            return 0;
        }
        //满额免运费
        // if (getSettingValueByKey('freight_free_limit') <= ShoppingCart::total()) {
        //     return 0;
        // }

        $freight_data=$this->cityRepository->getFreightInfoByAddress($address);

        $lufei = 0;
        //计费方式
        $weightType = $freight_data['freight_type'];
        //首重重量
        $freight_first_weight = $freight_data['freight_first_count'];
        //首重价格
        $freight_first_price = $freight_data['freight_first_price'];
        //续重重量
        $freight_continue_weight = $freight_data['freight_continue_count'];
        //续重价格
        $freight_continue_price = $freight_data['freight_continue_price'];

        if ($weightType == 1) {
            //按重计费
            $weight = 0; //计算运费商品重量
            //$items = ShoppingCart::all();

            foreach ($items as $item) {
                //确认用户购买的商品存在
                $tmp = explode('_', $item['id']);
                //不带规格
                $product = $this->productRepository->findWithoutFail($tmp[0]);
                if (!empty($product)) {
                    //计算运费
                    if (!$product->free_shipping) {
                        $weight += $product->weight * $item['qty'];
                    }
                }
            }
            if ($weight == 0) {
                $lufei = 0;
            } else {
                if ($freight_first_weight >= $weight) {
                    //首重内
                    $lufei = $freight_first_price;
                } else {
                    //超首重
                    $weight_exceed = $weight - $freight_first_weight;
                    if ($freight_continue_weight != 0) {
                        $exceed_num = ceil($weight_exceed/$freight_continue_weight);
                        $lufei = $freight_first_price + $exceed_num * $freight_continue_price;
                    }
                }
            }
        } else {
            //按件计费
            //$total_count = ShoppingCart::count();
            $total_count = 0;
            foreach ($items as $key => $value) {
                $total_count += $value['qty'];
            }
            if ($freight_first_weight >= $total_count) {
                //首次计件内
                $lufei = $freight_first_price;
            } else {
                //超首计件
                $weight_exceed = $total_count - $freight_first_weight;
                if ($freight_continue_weight != 0) {
                    $exceed_num = ceil($weight_exceed/$freight_continue_weight);
                    $lufei = $freight_first_price + $exceed_num * $freight_continue_price;
                }
            }
        }
        return empty($lufei) ? 0 : $lufei;
    }

    /**
     * 计算优惠券优惠金额
     * @param [integer] $coupon_id [优惠券ID]
     */
    public function CouponPreference($coupon_id, $total, $items)
    {
        //计算优惠券能不能用
        $coupon = $this->couponUserRepository->findWithoutFail($coupon_id);
        if (empty($coupon)) {
            return ['code' => 1, 'message' => '优惠券不存在'];
        }
        //检查优惠券状态
        if ($coupon->status != 0) {
            switch ($coupon->status) {
                case 1:
                    return ['code' => 1, 'message' => '优惠券被冻结'];
                    break;
                case 2:
                    return ['code' => 1, 'message' => '优惠券已使用'];
                    break;
                case 3:
                    return ['code' => 1, 'message' => '优惠券已过期'];
                    break;
                case 4:
                    return ['code' => 1, 'message' => '优惠券已作废'];
                    break;
                default:
                    return ['code' => 1, 'message' => '优惠券无法被使用'];
                    break;
            }
        }

        //检查优惠券的有效期
        $today = Carbon::today();
        if ( Carbon::parse($coupon->time_begin)->gt($today) || Carbon::parse($coupon->time_end)->lt($today) ) {
            return ['code' => 1, 'message' => '不在优惠券的使用期限内'];
        }
        //检查优惠券的使用条件
        //金额是否可以达到
        //$total = ShoppingCart::total();
        $originCoupon = $coupon->coupon;

        if ($originCoupon->base > $total) {
            return ['code' => 1, 'message' => '无法使用该优惠券，购物金额还差'.($originCoupon->base - $total)];
        }

        $totalPrice = 0;
        $youhui = 0;
        $preferPriceTotal = 0;

        //全场通用型
        if ($originCoupon->range == 0) {
            $preferPriceTotal = $total;
        }

        //指定分类型
        if ($originCoupon->range == 1) {
            $category = $this->categoryRepository->findWithoutFail($originCoupon->category_id);
            if (empty($category)) {
                return ['code' => 1, 'message' => '优惠券不符合使用条件，错误代码E050'];
            }
            //获取分类及其子分类的ID数组
            $productCats = $this->categoryRepository->getChildCatIds($category->id);
            array_push($productCats, $category->id);
            //$items = ShoppingCart::all();
            //计算能够优惠的商品金额
            foreach ($items as $item) {
                $tmp = explode('_', $item['id']);
                $product = $this->productRepository->findWithoutFail($tmp[0]);
                //商品是否在优惠范围内
                if (in_array($product->category_id, $productCats)) {
                    if ($tmp[1] < 1) {
                        //不带规格
                        $preferPriceTotal += $this->productRepository->getSalesPrice($product, false) * $item['qty'];
                    } else {
                        $specPrice = $this->specProductPriceRepository->findWithoutFail($tmp[1]);
                        $preferPriceTotal += $this->specProductPriceRepository->getSalesPrice($specPrice, false) * $item['qty'];
                    }
                }
            }
            //金额是否达到要求
            if ($originCoupon->base > $preferPriceTotal) {
                return ['code' => 1, 'message' => '无法使用该优惠券，优惠分类商品金额还差'.($originCoupon->base - $preferPriceTotal)];
            }
        }

        //指定商品
        if ($originCoupon->range == 2) {
            $okProducts = $originCoupon->products()->get();

            //获取分类及其子分类的ID数组
            //$items = ShoppingCart::all();
            $product = null;

            foreach ($items as $item) {
                $tmp = explode('_', $item['id']);
                $product = $this->isInProducts($tmp, $okProducts);
                if (!empty($product)) {
                    if ($tmp[1] < 1) {
                        //不带规格
                        $preferPriceTotal += $this->productRepository->getSalesPrice($product, false) * $item['qty'];
                    } else {
                        $specPrice = $this->specProductPriceRepository->findWithoutFail($tmp[1]);
                        $preferPriceTotal += $this->specProductPriceRepository->getSalesPrice($specPrice, false) * $item['qty'];
                    }
                }
            }
            //金额是否达到要求
            if ($originCoupon->base > $preferPriceTotal) {
                return ['code' => 1, 'message' => '无法使用该优惠券，优惠分类商品金额还差'.($originCoupon->base - $preferPriceTotal)];
            }
        }

        $name = '';
        if ($originCoupon->type == '满减') {
            $youhui = $originCoupon->given;
            $name = '满'.$originCoupon->base.'减'.$originCoupon->given;
        }else{
            $youhui = round((100 - $originCoupon->discount) * $preferPriceTotal/100, 2);
            $name = '满'.$originCoupon->base.'打'.$originCoupon->discount.'折';
        }

        return ['code' => 0, 'message' => [
            'discount' => $youhui,
            'coupon_id' => $coupon->id,
            'name' => $name
        ]];
    }

    /**
     * 订单优惠金额
     * @param  [float] $totalPrice [订单总金额]
     * @return [type]             [description]
     */
    public function orderPreference($totalPrice)
    {
        $orderPromp = $this->orderPrompRepository->getSuitablePromp($totalPrice);
        if (empty($orderPromp)) {
            return ['prom_id' => 0, 'money' => 0, 'name' => ''];
        }else{
            if ($orderPromp->type) {
                //减价
                return ['prom_id' => $orderPromp->id, 'money' => $orderPromp->value, 'name' => '购物满'.$orderPromp->base.'减'.$orderPromp->value];
            } else {
                //打折
                $final = round($totalPrice * (100 - $orderPromp->value) / 100, 2);
                return ['prom_id' => $orderPromp->id, 'money' => $final, 'name' => '购物满'.$orderPromp->base.'打'.$orderPromp->value.'折'];
            }
        }
    }

    /**
     * 用户等级优惠
     * @param [mixed] $user  [用户对象]
     * @param [float] $total [订单总金额]
     */
    public function UserLevelPreference($user, $total)
    {
        if (getSettingValueByKeyCache('user_level_switch') == '不开启') {
            return 0;
        }
        $user_level = UserLevel::where('id',$user->user_level)->first();
        if (!empty($user_level) && $user_level->discount < 100) {
            return round($total * (100 - $user_level->discount) / 100, 2);
        }else{
            return 0;
        }
    }

    /**
     * 用户积分日志
     * @param [type] $amount  [积分余额]
     * @param [type] $change  [ 积分变动，正为增加，负为支出 ]
     * @param [type] $detail  [详情]
     * @param [type] $type    [0注册赠送，1推荐好友赠送， 2购物赠送, 3消耗 4管理员操作 5积分卡充值]
     * @param [type] $user_id [用户ID]
     */
    public function addCreditLog($amount, $change, $detail, $type, $user_id)
    {
        if (empty($change)) {
            return;
        }
        
        CreditLog::create([
            'amount' => $amount,
            'change' => $change,
            'detail' => $detail,
            'type' => $type,
            'user_id' => $user_id,
        ]);  
    }

    /**
     * 用户余额日志
     * @param [type] $amount  [余额余额]
     * @param [type] $change  [ 余额变动，正为增加，负为支出 ]
     * @param [type] $detail  [详情]
     * @param [type] $type    [0注册赠送，1推荐好友赠送， 2购物赠送, 3消耗,4充值,5提现]
     * @param [type] $user_id [用户ID]
     */
    public function addMoneyLog($amount, $change, $detail, $type, $user_id)
    {
        if (empty($change)) {
            return;
        }
        MoneyLog::create([
            'amount' => $amount,
            'change' => $change,
            'detail' => $detail,
            'type' => $type,
            'user_id' => $user_id,
        ]);  
    }

    /**
     * 添加分佣记录
     * @param [type] $order            [订单信息]
     * @param [type] $get_money_id     [分佣用户ID]
     * @param [type] $distribute_level [推荐用户等级]
     * @param [type] $given_money      [分佣金额]
     */
    public function addDistributionLog($order, $get_money_id, $distribute_level, $given_money)
    {
        DistributionLog::create([
            'order_user_id' => $order->user_id,
            'user_id' => $get_money_id,
            'commission' => $given_money,
            'order_money' => $order->price,
            'user_dis_level' => $distribute_level,
            'status' => '已发放',
            'order_id' => $order->id
        ]);
    }

    /**
     * 售后日志
     * @param [type] $name            [description]
     * @param [type] $des             [description]
     * @param [type] $order_refund_id [description]
     */
    public function addRefundLog($name, $des, $order_refund_id)
    {
        RefundLog::create([
            'order_refund_id' => $order_refund_id,
            'name' => $name,
            'des' => $des,
            'time' => \Carbon\Carbon::now()
        ]);  
    }
    /**
     * 添加订单操作日志
     * @param [type] $order_status    [订单状态]
     * @param [type] $shipping_status [物流状态]
     * @param [type] $pay_status      [支付状态]
     * @param [type] $action          [操作]
     * @param [type] $status_desc     [描述]
     * @param [type] $user            [操作用户]
     * @param [type] $order_id        [订单ID]
     */
    public function addOrderLog($order_status, $shipping_status, $pay_status, $action, $status_desc, $user, $order_id)
    {
        OrderAction::create([
            'order_status' => $order_status,
            'shipping_status' => $shipping_status,
            'pay_status' => $pay_status,
            'action' => $action,
            'status_desc' => $status_desc,
            'user' => $user,
            'order_id' => $order_id,
        ]);
    }
    
    /**
     * 计算积分减免金额
     * @param [mixed] $user       [用户对象]
     * @param [float] $totalprice [订单总金额]
     * @param [integer] $credits    [积分数目]
     */
    public function CreditPreference($user, $totalprice, $credits)
    {
        $credits = $user->credits > $credits ? $credits : $user->credits;
        //积分现金兑换比例
        $creditRate = getSettingValueByKeyCache('credits_rate');
        //积分最多可抵用金额比例
        $maxTotalRate = getSettingValueByKeyCache('credits_max');
        //最多抵扣金额
        $maxCancel = round($totalprice * $maxTotalRate / 100);

        $credits = ($credits > $maxCancel * $creditRate) ? $maxCancel * $creditRate : $credits;
        return ['credits' => $credits, 'creditPreference' => round($credits / $creditRate, 2)];
    }

    /**
     * 微信授权登录,根据微信用户的授权信息，创建或更新用户信息
     * @param [mixed] $socialUser [微信用户对象]
     */
    public function CreateUserFromWechatOauth($socialUser)
    {
        $user = null;
        $unionid = null;
        //用户是否公众平台用户
        if (array_key_exists('unionid', $socialUser)) {
            $unionid = $socialUser['unionid'];
            $user = User::where('unionid', $socialUser['unionid'])->first(); 
        }
        //不是，则是否是微信用户
        if (empty($user)) {
            $user = User::where('openid', $socialUser['openid'])->first();
        }
        
        if (is_null($user)) {
            $first_level = UserLevel::orderBy('amount', 'asc')->first();
            $user_level  = empty($first_level) ? 0 : $first_level->id;

            //是否自动成为分销用户
            $is_distribute = 0;
            if (getSettingValueByKeyCache('distribution_condition') == '注册用户' && getSettingValueByKeyCache('distribution') == '是') {
                $is_distribute = 1;
            }
            // 新建用户
            $user = User::create([
                'openid' => $socialUser['openid'],
                'unionid' => $unionid,
                'name' => $socialUser['nickname'],
                'nickname' => $socialUser['nickname'],
                'head_image' => $socialUser['headimgurl'],
                'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                'province' => $socialUser['province'],
                'city' => $socialUser['city'],
                'user_level' => $user_level,
                'oauth' => '微信',
                'is_distribute' => $is_distribute
            ]);
            //新注册用户的好处发放
            
        }else{
            if (array_key_exists('unionid', $socialUser) && empty($user->unionid)) {
                $user->update([
                    'nickname' => $socialUser['nickname'],
                    'head_image' => $socialUser['headimgurl'],
                    'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                    'province' => $socialUser['province'],
                    'city' => $socialUser['city'],
                    'unionid' => $unionid,
                ]);
            } else {
                $user->update([
                    'nickname' => $socialUser['nickname'],
                    'head_image' => $socialUser['headimgurl'],
                    'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                    'province' => $socialUser['province'],
                    'city' => $socialUser['city']
                ]);
            }
            
            
        }
        return $user;
    }

    /**
     * [添加用户余额]
     * @param [type] $user_id [description]
     * @param [type] $money   [description]
     */
    public function addUserMoney($user_id,$money)
    {
         $user = User::find($user_id);
         if(!empty($user)){
            $money = (float)$money;
            $user->update(['user_money'=>$user->user_money+$money]);
            #添加余额记录
            app('commonRepo')->addMoneyLog($user->user_money,$money,'充值'.$money.'元到账户余额',4,$user->id);
         }        
    }

    /**
     * 支付成功后，处理商品订单信息
     * @param  [mixed] $order [订单信息]
     * @return [type]        [description]
     */
    public function processOrder($order, $pay_platform, $pay_no=''){
        //修改订单状态
        $order->update(['order_pay' => '已支付', 'pay_time' => Carbon::now(), 'pay_platform' => $pay_platform, 'pay_no' => $pay_no]);

        #充值订单
        // if(substr($order->out_trade_no, -2) == '_8'){
        //     $message = explode('_',$order->out_trade_no);
        //     $this->addUserMoney($message[1],$order->price);
        // }

        //加销量
        $this->orderRepository->dealOrderProductSales($order);

        //减库存
        if (getSettingValueByKey('inventory_consume') == '支付成功') {
            $this->orderRepository->deduceInventory($order->id);
        }

        //处理分销及分佣
        $this->successPayDealDis($order);
        
        //发送提醒
        // event(new OrderPay($order));

        //处理充值订单
        // $this->orderRepository->dealTopupOrder($order->out_trade_no);

        //填写支付记录
        
        //购物券
        // CouponUser::where('order_id', $order->id)->update(['status' => '已使用']);
        
        // 发送微信支付通知
        $user = $order->customer;
        if($user){
            $this->weixinText('您好,您在澳宝直邮的订单已支付完成,商家会及时处理发货,物流通知也会提示给您!',$user->openid);
        }
    }


    /**
     * 发送订单物流微信推送消息
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function sendOrderWuliuMessage($order)
    {
        $user = $order->customer;
        if($user)
        {
            if($order->delivery_company && $order->delivery_no)
            {
                $message = '您好,欧宝直邮提醒您,您的订单单号为'.$order->snumber.'的订单已发货,物流公司是'.$order->delivery_company.',单号是'.$order->delivery_no;
                $this->weixinText($message,$user->openid);

                if($user->leader1)
                {
                    $leader1 = User::find($user->leader1);
                    if($leader1)
                    {
                        $message = '亲爱的店主您好,澳宝直邮提醒您,您的朋友'.$user->nickname.'的订单单号为'.$order->snumber.'的订单已发货,物流公司是'.$order->delivery_company.',单号是'.$order->delivery_no;
                        $this->weixinText($message,$leader1->openid);
                    }
                }
            } 
        }
    }

     //发送微信消息
     public function weixinText($message, $openId)
    {
        $result = app('wechat.official_account')->customer_service->message($message)->to($openId)->send();
        //$text = new Text('您好！overtrue。');
        // app('wechat.official_account')->template_message->send([
        //     'touser' => $openId,
        //     'template_id' => '0hc11d00RWw63JtDaqjrSj0X1a-_2pDxBPCuSzSBCg4',
        //     'url' => 'http://shop.eagletags.com',
        //     'data' => [
        //         'first'=> '吉丁甲为您找到以下信息',
        //         // 'keyword1' => time(),
        //         // 'keyword2' => '杭州',
        //         // 'keyword3' => '衣服',
        //         // 'keyword4' =>  Carbon::now(),
        //         'remark' => $message
        //     ],
        // ]);
        return $result;
    }

    /**
     * 支付成功处理分销
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function successPayDealDis($order)
    {
          $user = $order->customer;
          if($user)
          {
            //是店主就不分拥 只有普通用户分佣
            if($user->code)
            {
                return;
            }
            //一级分销
            if ($user->leader1) 
            {
                $leader1 = User::where('id', $user->leader1)->first();
                //上级推荐人有分销资格 才分佣
                if (!empty($leader1) && $leader1->code) 
                {
                    $level1_given_money = $order->dis_price;
                    if ($level1_given_money) 
                    {
                        $leader1->user_money += $level1_given_money;
                        $leader1->distribut_money += $level1_given_money;
                        $leader1->save();

                        //添加金额变动记录
                        app('commonRepo')->addMoneyLog($leader1->user_money, $level1_given_money, '推荐用户'.$user->nickname.'消费提成，订单编号为:'.$order->snumber, 2, $leader1->id);
                        app('commonRepo')->addDistributionLog($order, $leader1->id, 1, $level1_given_money);
                        $this->weixinText('亲爱的店主,'.$user->nickname.'刚刚消费了'.$order->price.'AUD，您入账了'.$level1_given_money.'AUD,商家会及时处理发货,物流通知也会提示给您!',$leader1->openid);
                    }
                }
            }
          }
    }


    public function countOrderDisPrice()
    {

    }




    /**
     * 取消订单操作
     * @param  [type] $orderCancel [order对象]
     * @return [type]              [description]
     */
    public function cancelOrderOperation($orderCancel)
    {
        if ($orderCancel->auth == 0) {
            //待审核不处理
            return;
        }

        if ($orderCancel->auth == 1) {
            //通过审核
            $order = $this->orderRepository->findWithoutFail($orderCancel->order_id);
            if ($order->order_pay == '未支付') {
                return;
            }
            if ($orderCancel->refound == 0) {
                //资金原路返回
                //返还现金
                $payment = Factory::payment(Config::get('wechat.payment.default'));
                // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数
                $result = $payment->refund->byOutTradeNumber($order->snumber, $order->snumber.'refund', $order->price, $order->price, [
                    'refund_desc' => '订单取消退款',
                ]);
                //返还积分
                $user = User::find($order->user_id);
                $user->credits = $user->credits + $order->credits;
                $this->addCreditLog($user->credits, $order->credits, '订单取消，退还'.getSettingValueByKeyCache('credits_alias'), 0, $order->user_id);
                
                //返还余额
                $user->user_money = $user->user_money + $order->user_money_pay;
                $user->save();
                $this->addMoneyLog($user->user_money, $order->user_money_pay, '订单取消，退还余额', 0, $order->user_id);

                //返还优惠券
                CouponUser::where('order_id', $order->id)->update(['status' => '未使用']);
            } else {
                //资金返回到余额
                //返还积分
                $user = User::find($order->user_id);
                $user->credits = $user->credits + $order->credits;
                $this->addCreditLog($user->credits, $order->credits, '订单取消，退还'.getSettingValueByKeyCache('credits_alias'), 0, $order->user_id);
                
                //返还余额
                $user->user_money = $user->user_money + $order->user_money_pay + $order->price;
                $user->save();
                $this->addMoneyLog($user->user_money - $order->price, $order->user_money_pay, '订单取消，退还余额', 0, $order->user_id);
                $this->addMoneyLog($user->user_money, $order->price, '订单取消，将用户支付的现金退还到余额', 0, $order->user_id);

                //返还优惠券
                CouponUser::where('order_id', $order->id)->update(['status' => '未使用']);
            }
        }

        if ($orderCancel->auth == 2) {
            //审核不通过
            $order->status = '未确认';
            $order->save();
        }
        
    }

    /**
     * 给用户发放优惠券
     * @param  [mixed] $user   [用户对象]
     * @param  [mixed] $coupon [优惠券对象]
     * @param  [integer] $count  [发放数量]
     * @param  string $reason [发送理由]
     * @return [type]         [description]
     */
    public function issueCoupon($user, $coupon, $count, $reason='系统发放')
    {
        // 拥有该优惠券的数量受限
        if ($coupon->max_count) {
            $coupon_issue_count = CouponUser::where('user_id', $user->id)->where('coupon_id', $coupon->id)->count();
            if ($coupon_issue_count >= $coupon->max_count) {
                return;
            }
        }

        $time_begin = null;
        $time_end = null;
        if ($coupon->time_type == 0) {
            //固定时间有效期
            $time_begin = $coupon->time_begin;
            $time_end = $coupon->time_end;
        }else{
            //领券开始计算
            $time_begin = Carbon::today();
            $time_end = Carbon::today()->addDays($coupon->expire_days);
        }
        // 发放
        for ($i=0; $i < $count; $i++) { 
            CouponUser::create([
                'from_way' => $reason,
                'time_begin' => $time_begin,
                'time_end' => $time_end,
                'status' => 0,
                'user_id' => $user->id,
                'coupon_id' => $coupon->id
            ]);
        }
    }

    /**
     * [processGivenCoupon 给用户发放优惠券]
     * @param  [type] $users          [$user collection]
     * @param  [type] $couponIdsArray [coupon ids array]
     * @param  [type] $count          [发放数量]
     * @return [type]                 [description]
     */
    public function processGivenCoupon($users, $couponIdsArray, $count, $reason = '系统发放')
    {
        if (!is_array($couponIdsArray)) {
            return;
        }
        foreach ($users as $user) {
            foreach ($couponIdsArray as $key => $id) {
                $coupon = $this->couponRepository->findWithoutFail($id);
                if (empty($coupon)) {
                    continue;
                }
                $this->issueCoupon($user, $coupon, $count, $reason);
            }
        }
    }

    /**
     * 根据product_id和spec_price_id查找商品信息
     * @param  [type]  $idArray  [description]
     * @param  [type]  $products [description]
     * @return boolean           [description]
     */
    private function isInProducts($idArray, $products){
        foreach ($products as $product) {
            if ($idArray[0] == $product->id && $idArray[1] == $product->pivot->spec_price_id) {
                return $product;
            }
        }
        return null;
    }


    //发送短信验证码
    public function sendVerifyCode($mobile,$allocat=['access_key_id'=>'LTAIBZxu3Qvq95tQ','access_key_secret'=>'iJ4OGZ3b11sMvAG4HjsfDywHlbjta9','sign_name'=>'卡达人','template'=>'SMS_101005146'])
    {

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
                    'access_key_id' => $allocat['access_key_id'],
                    //Config::get('SMS_ID'),
                    'access_key_secret' => $allocat['access_key_secret'],
                    //Config::get('SMS_KEY'),
                    'sign_name' => $allocat['sign_name']
                    //Config::get('SMS_SIGN'),
                ]
            ],
        ];

        $easySms = new EasySms($config);

        $num = rand(1000, 9999); 

        $easySms->send($mobile, [
            'content'  => '验证码'.$num.'，您正在注册成为新用户，感谢您的支持！',
            'template' => $allocat['template'],
            //Config::get('SMS_TEMPLATE_VERIFY'),
            'data' => [
                'code' => $num
            ],
        ]);
        return $num;
    }

    /**
     * 计算购物车中商品的真实价格
     * @return [type] [description]
     */
    public function processShoppingCartItems($items){
        //$items = ShoppingCart::all();
        //如果是带规格的就要加上规格
        foreach ($items as $item) {
            $tmp = explode('_', $item['id']);
            $product = $this->productRepository->findWithoutFail($tmp[0]);
            if ($tmp[1] < 1) {
                //不带规格
                $item['type'] = 0;
                $item['product'] = $product;
                $item['realPrice'] = $this->productRepository->getSalesPrice($product, false);
                $item['jifen'] = $product->jifen;
            } else {
                $specPrice = $this->specProductPriceRepository->findWithoutFail($tmp[1]);
                $item['type'] = 1;
                $item['product'] = $product;
                $item['spec'] = $specPrice;
                $item['realPrice'] = $this->specProductPriceRepository->getSalesPrice($specPrice, false);
                $item['jifen'] = $specPrice->jifen;
            }
        }
        return $items;
    }

    /**
     * 通过items计算分佣金额
     * @param  [type] $items [description]
     * @return [type]        [description]
     */
    public function countProductDisPrice($items)
    {
        $countPrice = 0;
        foreach ($items as $key => $item) 
        {
            $rate = getSettingValueByKey('fenyong_rate') ?: 10;

            if(isset($item['product']))
            {
                $rate = $item['product']->fenyong_rate ?: $rate;
            }
            $itemDisPrice =  $item['realPrice'] * $rate / 100;
            $countPrice += $itemDisPrice;
        }
        return $countPrice;
    }

    /**
     * 商品最大可买数量
     * @param  [type] $product [description]
     * @param  [type] $qty     [description]
     * @param  [type] $spec_id [description]
     * @return [type]          [description]
     */
    public function maxCanBuy($product, $qty, $spec_id = null)
    {
        if ($product->prom_type == 1) {
            //秒杀
            $flashSale = $this->flashSaleRepository->findWithoutFail($product->prom_id);
            if (!empty($flashSale) && $flashSale->status == '进行中') {
                if ($qty > $flashSale->buy_limit) {
                    $qty = $flashSale->buy_limit;
                }
            }
        }else{
            //普通购买，检查库存
            if (empty($spec_id)) {
                if ($product->inventory != -1) {
                    $qty = $qty > $product->inventory ? $product->inventory : $qty;
                }
            }else{
                $specPrice = $this->specProductPriceRepository->findWithoutFail($spec_id);
                if ($specPrice->inventory != -1) {
                    if (empty($specPrice)) {
                        $qty = 0;
                    } else {
                        $qty = $qty > $specPrice->inventory ? $specPrice->inventory : $qty;
                    }
                }
            }
        }
        return $qty;
    }

    /**
     * [处理图片的拍照方向]
     * @param  [type] $img        [description]
     * @param  [type] $image_path [description]
     * @return [type]             [description]
     */
    public function exifDealImg($image_path)
    {   
        $img = Image::make($image_path);

        try{
        @$exif=exif_read_data($image_path);
        //判断拍照方向
        if(isset($exif['Orientation'])) {
           switch($exif['Orientation']) {
            case 8:
             $img->rotate(90);
             break;
            case 3:
             $img->rotate(180);
             break;
            case 6:
             $img->rotate(-90);
             break;
           }
        }
      }catch(Exception $e){

      }

      return $img;
    }

    //读取excel文件
    public function loadExcels($files){
       if (!file_exists($files)){
          //return zcjy_callback_data('没有找到该文件',1);
          return false;
       }
       $res = [];
       Excel::load($files, function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
       }); 
       return $res;
    }

    public function readExcelsToGenerate($files = null,$start = 1,$rows = 100)
    {
        if(empty($files))
        {
            $files = public_path('data.xls');
        }
        ##先读取excel文件
        $res= $this->loadExcels($files);
        if(count($res) > 1)
        {
             $data_arr = [];
             $input = [];
             for ($i = $start; $i < $rows; $i++) 
             { 
                ##有数据就处理
                if(isset($res[$i][0]))
                {
                    $input['name']        = $res[$i][0];
                    $input['link']        = isset($res[$i][1]) ? $res[$i][1] : 0;
                    $input['category_id'] = catIdByName($res[$i][2]);
                    if($input['link'])
                    {
                        $queryData = queryList($input['link'])[0];

                        if(isset($queryData['image']))
                        {
                            $input['image']        = $queryData['image'];
                            $input['price']        = $queryData['price'];
                            $input['intro']        = $queryData['intro'];
                            $input['market_price'] = rand(200,220);
                        }
                        
                    }
                    Product::create($input);
                    $data_arr[] = $input;
                }
             }
        }
        return $data_arr;
    }

    /**
     * [图片/文件 上传]
     * @param  [type] $file     [description]
     * @param  string $api_type [description]
     * @return [type]           [description]
     */
    public function uploadFiles($file , $api_type = 'web' , $user = null){
        if(empty($file)){
            return zcjy_callback_data('文件不能为空',1,$api_type);
        }
        #文件类型
        $file_type = 'file';
        #文件实际后缀
        $file_suffix = $file->getClientOriginalExtension();
        if(!empty($file)) {
              $img_extensions = ["png", "jpg", "gif","jpeg"];
              $sound_extensions = ["PCM","WAVE","MP3","OGG","MPC","mp3PRo","WMA","wma","RA","rm","APE","AAC","VQF","LPCM","M4A","cda","wav","mid","flac","au","aiff","ape","mod","mp3"];
              $excel_extensions = ["xls","xlsx","xlsm"];
              if ($file_suffix && !in_array($file_suffix , $img_extensions) && !in_array($file_suffix , $sound_extensions) && !in_array($file_suffix,$excel_extensions)) {
                  return zcjy_callback_data('上传文件格式不正确',1,$api_type);
              }
              if(in_array($file_suffix, $img_extensions)){
                  $file_type = 'image';
              }
              if(in_array($file_suffix, $sound_extensions)){
                $file_type = 'sound';
              }
              if(in_array($file_suffix,$excel_extensions)){
                $file_type = 'excel';
              }
        }

        #文件夹
        $destinationPath = empty($user) ? "uploads/admin/" : "uploads/user/".$user->id.'/';
        #加上类型
        $destinationPath = $destinationPath.$file_type.'/';

        if (!file_exists($destinationPath)){
            mkdir($destinationPath,0777,true);
        }
       
        $extension = $file_suffix;
        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);

        #对于图片文件处理
        if($file_type == 'image'){
          $image_path=public_path().'/'.$destinationPath.$fileName;
       
          $img = $this->exifDealImg($image_path);
          // $img->resize(640, 640);
          $img->save($image_path,70);
        }

        $host='http://'.$_SERVER["HTTP_HOST"];

        if(env('online_version') == 'https'){
             $host='https://'.$_SERVER["HTTP_HOST"];
        }

        #路径
        $path=$host.'/'.$destinationPath.$fileName;

        return zcjy_callback_data([
                'src'=>$path,
                'current_time' => Carbon::now(),
                'type' => $file_type,
                'current_src' => public_path().'/'.$destinationPath.$fileName
            ],0,$api_type);
    }

    public function productAllShelf()
    {
        $products = Product::all();
        foreach ($products as $key => $value) {
            $value->update(['shelf'=>1]);
        }
    }

    /**
     * 检查一下用户需不需要绑定推荐人
     * @return [type] [description]
     */
    public function varifyUserBindTMan()
    {
        $user = auth('web')->user();
        ##如果已经是店主 不用继续绑定推荐人
        if($user->code)
        {
            return false;
        }
        ##从推荐人链接 或者没有上级都要
        if($user->temporary_code && empty($user->leader1) || empty($user->leader1))
        {
            return true;
        }

    }

    /**
     * 查找用户的推荐人
     * @return [type] [description]
     */
    public function userLeader()
    {
       $user = auth('web')->user();

       ##如果之前有推荐人
       if(!empty($user->leader1))
       {
        $leader = User::find($user->leader1);
        if(!empty($leader))
        {
            return $leader; 
        }
       }

       $temporary_code = $user->temporary_code;

       $leader = User::where('code',$temporary_code)->where('id','<>',$user->id)->first();

       if(!empty($leader))
       {
        return $leader;
       }

       if(empty($temporary_code) || empty($leader))
       {
        $leader = User::whereNotNull('code')
        ->where('id','<>',$user->id)
        ->orderBy('created_at','asc')
        ->first();
       }

       return $leader;
    }

     /**
     * [微信授权跳转]
     * @param  [type] $targer_url [description]
     * @return [type]             [description]
     */
    public function weixinAuthRedirect($target_url='')
    {
        #默认配置项
        $options = Config::get('wechat.official_account.default');

        $options['oauth'] = [
          'scopes'   => ['snsapi_userinfo'],
          'callback' => $options['target_callback_path'].'?target_url='.$target_url,//存储客户端要跳转的链接
        ];
        $app = Factory::officialAccount($options);

        $response = $app
        ->oauth
        ->scopes(['snsapi_userinfo'])
        ->redirect();

        return $response;
    }

    /**
     * 获取微信信息
     * @return [type] [description]
     */
    public function getWeiXinInfo()
    {
        $options = Config::get('wechat.official_account.default');
        //Log::info($options);
        $app =Factory::officialAccount($options);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $userinfo = $oauth->user();
        $user = User::where('openid', $userinfo->getId())->first();
        if (is_null($user)) 
        {
            // 新建用户
            $user = User::create([
                'openid' => $userinfo->getId(),
                'nickname' => $userinfo->getNickname(),
                'head_image' => $userinfo->getAvatar(),
                ]);
        }
        else{
            $user->update([
                'nickname' => $userinfo->getNickname(),
                'head_image' => $userinfo->getAvatar()
            ]);
        }
        return $user;
    }

    /**
     * [微信callback跳转]
     * @param  [type] $target_url [description]
     * @return [type]             [description]
     */
    public function weixinAuthCallback($target_url)
    {
        $user = $this->getWeiXinInfo();
        $target_url = empty($target_url) ? Request::root() : $target_url;
        #发起登录
        $user->last_ip = Request::ip();
        $user->last_login = \Carbon\Carbon::now();
        $user->save();
        auth('web')->login($user);
        Log::info('targer_url');
        Log::info($target_url);
        return redirect($target_url);
    }

    /**
     * [根据ip获取微信用户]
     * @param  [type] $ip [description]
     * @return [type]     [description]
     */
    public function getCacheWeixinUser($ip)
    {
      return cache('weixin_user_'.$ip);
    }

    /**
     * [本地开发微信登录]
     * @param  string $openid [description]
     * @return [type]         [description]
     */
    public function localWeixinUser($openid = 'odh7zsgI75iT8FRh0fGlSojc9PWM')
    {
         $user = User::where('openid', $openid)->first();
         auth('web')->login($user);
         return $user;
    }

    /**
     * 阿里云实名认证
     * @param  [type] $name    [description]
     * @param  [type] $id_card [description]
     * @param  string $AppCode [description]
     * @return [type]          [description]
     */
    public function aliyunCert($name,$id_card,$AppCode = 'fc643f19813d461f837287c3d6ca022e')
    {
        $host = "https://api12.aliyun.venuscn.com";
        $path = "/cert/id-card";
        $method = "GET";
        ##这里标注上自己的AppCode
        $appcode = $AppCode;
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "name=".$name."&number=".$id_card."";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $returnData = json_decode(curl_exec($curl),1);
        if(isset($returnData['msg']) && isset($returnData['data']))
        {
            if($returnData['msg'] == 'success' && $returnData['data']['code'] == 0)
            {
                return ['code'=>0,'message'=>'认证成功'];
            }
            else{
                return ['code'=>1,'message'=>'认证失败'];
            }
        }
        else{
            return ['code'=>1,'message'=>'认证失败'];
        }
    }

    /**
     * 检查一下收货人地址 和认证信息一致不
     * @param  [type] $input [description]
     * @param  [type] $user  [description]
     * @return [type]        [description]
     */
    public function varifyAddressAndName($input,$user)
    {
        $address = Address::find($input['address_id']);
        if(empty($address))
        {
            return zcjy_callback_data('该地址不存在',1);
        }
        $cert = $user->cert()->first();
        if(empty($cert))
        {
            return zcjy_callback_data('请先完成实名认证',1);
        }
        if($address->name != $cert->name)
        {
            return zcjy_callback_data('实名认证姓名与收货人姓名不匹配,请重新核对下地址信息设置',1);
        }
    }

    /**
     * 发起superpay 支付宝支付
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function startAlipaySuperPay($order = null)
    {
        $requestUrl = 'https://api.superpayglobal.com/payment/bridge/merchant_request';
        $requestParam = [
            'merchant_id'        => Config::get('superpay.merchant_id'),
            'authentication_code'=> Config::get('superpay.authentication_code'),
            'product_title'      => '欧宝直邮商品购买',
            'merchant_trade_no'  => $order->out_trade_no,
            'currency'           => 'AUD',
            'total_amount'       => $order->price,
            'create_time'        => Carbon::now(),
            'notification_url'   => 'http://www.opalzy.com/super_pay/notify_wechcat_pay',
            'return_url'         => 'http://www.opalzy.com/orders'
        ];
        $requestParamMd5 = '';
        $i = 0;
        foreach ($requestParam as $key => $value) 
        {
            if(in_array($key,['merchant_id','authentication_code','merchant_trade_no','total_amount']))
            {
                $requestParamMd5 .= $key.'='.$value;
                $i++;
                if($i<4)
                {
                    $requestParamMd5 .= '&';
                }
            }
        }
        $requestParam['token'] = md5($requestParamMd5);
        $request = \Zcjy::simpleGuzzleRequest($requestUrl,'GET',$requestParam);
        $result =  json_decode($request,1);
        dd($result);
        if(isset($result['QRCodeURL']))
        {
            return zcjy_callback_data($result['QRCodeURL']);
        }
        else{
            return zcjy_callback_data('支付异常',1);
        }
    }

    /**
     * 发起superpay 微信支付
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function startWechatSuperPay($order = null)
    {
        $requestUrl = 'https://api.superpayglobal.com/payment/wxpayproxy/merchant_request';
        $requestParam = [
            'merchant_id'        => Config::get('superpay.merchant_id'),
            'authentication_code'=> Config::get('superpay.authentication_code'),
            'product_title'      => '欧宝直邮商品购买',
            'merchant_trade_no'  => $order->out_trade_no,
            'currency'           => 'AUD',
            'total_amount'       => $order->price,
            'create_time'        => Carbon::now(),
            'notification_url'   => 'http://www.opalzy.com/super_pay/notify_wechcat_pay',
            'return_url'         => 'http://www.opalzy.com/orders'
        ];
        $requestParamMd5 = '';
        $i = 0;
        foreach ($requestParam as $key => $value) 
        {
            if(in_array($key,['merchant_id','authentication_code','merchant_trade_no','total_amount']))
            {
                $requestParamMd5 .= $key.'='.$value;
                $i++;
                if($i<4)
                {
                    $requestParamMd5 .= '&';
                }
            }
        }
        $requestParam['token'] = md5($requestParamMd5);
        $request = \Zcjy::simpleGuzzleRequest($requestUrl,'GET',$requestParam);
        $result =  json_decode($request,1);
        if(isset($result['QRCodeURL']))
        {
            return zcjy_callback_data($result['QRCodeURL']);
        }
        else{
            return zcjy_callback_data('支付异常',1);
        }
    }

    //superpay 微信支付通知
    public function superPayWechatNotify($request)
    {   
        $input = $request->all();
        if(!isset($input['notice_id']) || !isset($input['merchant_trade_no']) || !isset($input['token']))
        {
            return;
        }
        $order = Order::where('out_trade_no', $input['merchant_trade_no'])->first();
        if (empty($order)) 
        { // 如果订单不存在
            return; 
        }
        // 如果订单存在
        // 检查订单是否已经更新过支付状态
        if ($order->order_pay == '已支付') 
        {
            // 已经支付成功了就不再更新了
            return; 
        }
        app('commonRepo')->processOrder($order, '微信支付');
    }

    public function superPayNotifyTokenVarify($token)
    {

    }





}
