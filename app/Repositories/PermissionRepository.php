<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseRepository;

/**
 * Class PermissionRepository
 * @package App\Repositories
 * @version April 27, 2020, 7:38 pm UTC
*/

class PermissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name',
        'option_menu_id',
        'blocked'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Permission::class;
    }
}
