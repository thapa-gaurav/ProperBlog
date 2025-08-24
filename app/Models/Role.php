<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Permission\Models\Permission;

class Role extends \Spatie\Permission\Models\Role
{
    protected $fillable =[
        'name','guard_name','created_at','updated_at',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
