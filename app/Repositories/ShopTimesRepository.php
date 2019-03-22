<?php

namespace App\Repositories;

use App\Models\ShopTimes;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;

/**
 * Class ShopTimesRepository
 * @package App\Repositories
 * @version March 22, 2019, 9:23 am CST
 *
 * @method ShopTimes findWithoutFail($id, $columns = ['*'])
 * @method ShopTimes find($id, $columns = ['*'])
 * @method ShopTimes first($columns = ['*'])
*/
class ShopTimesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'shoper_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ShopTimes::class;
    }

    /**
     * 添加进店次数
     * @param [type] $user [description]
     */
    public function addManTimes($user)
    {
        //他有上线
        if($user->leader1){
            ShopTimes::create(['user_id'=>$user->id,'shoper_id'=>$user->leader1]);
        }
    }

    //统计单个下线的所有进店次数
    public function countAllTimes($user_id,$shoper_id)
    {
        return ShopTimes::where('user_id',$user_id)->where('shoper_id',$shoper_id)->count();
    }

    //统计单个下线的当天进店次数
    public function countDayTimes($user_id,$shoper_id)
    {
        return ShopTimes::where('user_id',$user_id)
        ->where('shoper_id',$shoper_id)
        ->whereBetween('created_at', array(Carbon::today(), Carbon::tomorrow()))
        ->count();
    }
}

