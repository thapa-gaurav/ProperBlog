<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use Plank\Mediable\MediableInterface;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model implements MediableInterface
{
    use Mediable;
    use LogsActivity;
    protected $fillable = ['caption','text'];

//    public  function tapActivity(Activity $activity, string $eventName)
//    {
//        $activity->description =  "activity.logs.message.{$eventName}";
//    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['caption','text'])->setDescriptionForEvent(fn(string $eventName)=>"This model has been {$eventName}");
    }
}
