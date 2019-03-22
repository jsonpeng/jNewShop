<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ShopTimes
 * @package App\Models
 * @version March 22, 2019, 9:23 am CST
 *
 * @property integer user_id
 * @property integer shoper_id
 */
class ShopTimes extends Model
{
    use SoftDeletes;

    public $table = 'shop_times';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'shoper_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'shoper_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
