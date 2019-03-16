<?php

namespace App\Http\Controllers\Front\Obzy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

use App\User;

class MainController extends Controller
{

	//申请店铺
	public function signShop(Request $request)
	{	
		//已经绑定过的直接到首页
		$user = auth('web')->user();
		if(!empty($user->mobile) && !empty($user->code))
		{
			return redirect('/');
		}
		return view(frontView('signshop'));
	}

}