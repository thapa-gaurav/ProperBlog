<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use Plank\Mediable\MediableInterface;

class Post extends Model implements MediableInterface
{
    use Mediable;
    protected $fillable = ['caption','text'];
}
