<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Code
 * @package App\Models
 * @version March 17, 2019, 7:37 pm CST
 *
 * @property string code
 */
class Code extends Model
{
    use SoftDeletes;

    public $table = 'codes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'code',
        'use'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|max:5|unique:codes'
    ];

    
}
