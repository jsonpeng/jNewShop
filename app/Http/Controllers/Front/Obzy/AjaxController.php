<?php

namespace App\Http\Controllers\Front\Obzy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

use App\User;

class AjaxController extends Controller
{

	//发送验证码
	public function sendCode(Request $request)
	{
		$input = $request->all();
        $varify = app('commonRepo')->varifyInputParam($input,'mobile');
        if($varify)
        {
            return zcjy_callback_data($varify,1,'web');
        }
        $user = auth('web')->user();
        //短信模板
        $allocat = ['access_key_id'=>'LTAI0tsCvAtCgDXd','access_key_secret'=>'vPqyRPIShQXoJyQfAX4L2RqK2K4yYu','sign_name'=>'澳宝直邮','template'=>'SMS_160570739'];
        //存储session
        $request->session()->put('zcjy_code_'.$input['mobile'],app('commonRepo')->sendVerifyCode($input['mobile'],$allocat));
        return zcjy_callback_data('发送验证码成功',0,'web');
	}

	//绑定手机号
	public function bindMobile(Request $request)
	{
		$input = $request->all();
        $varify = app('commonRepo')->varifyInputParam($input,'mobile,code');
        if($varify)
        {
            return zcjy_callback_data($varify,1,'web');
        }
		$user = auth('web')->user();
		if(session('zcjy_code_'.$input['mobile']) != $input['code'])
		{
			return zcjy_callback_data('验证码错误',1,'web');
		}
		$user->update(['mobile'=>$input['mobile']]);
		return zcjy_callback_data('绑定手机号成功',0,'web');
	}

	//绑定邀请码
	public function bindCode(Request $request)
	{
		$input = $request->all();
        $varify = app('commonRepo')->varifyInputParam($input,'code');
        if($varify)
        {
            return zcjy_callback_data($varify,1,'web');
        }
        $user = auth('web')->user();
		if(empty($user->mobile))
		{
			return zcjy_callback_data('请先绑定手机号',1,'web');
		}
		if(User::where('code',$input['code'])->count())
		{
			return zcjy_callback_data('该邀请码已被使用,请更换后绑定',1,'web');
		}
		$user = auth('web')->user();
		$user->update(['code'=>$input['code']]);
		return zcjy_callback_data('开通店铺成功',0,'web');
	}

}