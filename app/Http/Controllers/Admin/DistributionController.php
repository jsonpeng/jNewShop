<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\User;

class DistributionController extends AppBaseController
{
    public function stats()
    {
    	return view('admin.distributions.stats');
    }

    public function lists(Request $request)
    {
    	$users = User::whereRaw('LENGTH(code)=5')->paginate($this->defaultPage());
    	return view('admin.distributions.lists', compact('users'));
    }

    public function settings(Request $request)
    {
    	return view('admin.distributions.settings');
    }
}
