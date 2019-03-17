<?php

namespace App\Repositories;

use App\Models\Code;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CodeRepository
 * @package App\Repositories
 * @version March 17, 2019, 7:37 pm CST
 *
 * @method Code findWithoutFail($id, $columns = ['*'])
 * @method Code find($id, $columns = ['*'])
 * @method Code first($columns = ['*'])
*/
class CodeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Code::class;
    }
}
