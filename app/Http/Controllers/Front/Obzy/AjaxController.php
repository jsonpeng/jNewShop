<?php

namespace App\Http\Controllers\Front\Obzy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

use App\User;
use App\Models\Code;

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
		if($request->has('leader'))
		{
			$leader = User::find($input['leader']);
			if(!empty($leader))
			{
				$user->update(['leader1'=>$input['leader']]);
			}
		}
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
		$code = Code::where('code',$input['code'])->first();
		if(empty($code))
		{
			return zcjy_callback_data('邀请码输入错误',1,'web');
		}
		if($code->use)
		{
			return zcjy_callback_data('该邀请码已被使用,请更换后绑定',1,'web');
		}
		$user = auth('web')->user();
		$user->update(['code'=>$input['code']]);
		$code->update(['use'=>1]);
		return zcjy_callback_data('开通店铺成功',0,'web');
	}

	//设置leader
	public function setLeader(Request $request,$leader_id)
	{
		$leader = User::find($leader_id);

		if(empty($leader))
		{
			return zcjy_callback_data('该推荐人不存在',1,'web');
		}

		$user = auth('web')->user();

		if($user->id == $leader->id)
		{
			return zcjy_callback_data('推荐人不可以设置为自己',1,'web');
		}
		
		$user->update(['leader1'=>$leader_id]);
		return zcjy_callback_data('设置推荐人成功');
	}

	//修改leader
	public function editLeader(Request $request,$leader_code)
	{
		$leader = User::where('code',$leader_code)->first();

		if(empty($leader))
		{
			return zcjy_callback_data('邀请码输入错误',1,'web');
		}

		$user = auth('web')->user();

		if($user->edit_leader_time == 0)
		{
			return zcjy_callback_data('修改推荐人次数已达到上限',1);
		}

		if($user->id == $leader->id)
		{
			return zcjy_callback_data('推荐人不可以设置为自己',1,'web');
		}

		$edit_leader_time = $user->edit_leader_time-1;
		$user->update(['leader1'=>$leader->id,$edit_leader_time=>$edit_leader_time]);

		return zcjy_callback_data('修改推荐人成功,剩余修改次数:'.$edit_leader_time.'次');

	}

}