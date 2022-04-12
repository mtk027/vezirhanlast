<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use SoftDeletes,FileTrait,LogsActivity;

    protected $guarded = [];

    protected $appends = ['default_photo'];

    public function languages(){
        return $this->hasMany(PropertyDetail::class,'property_id','id');
    }
    public function language(){
        return $this->hasOne(PropertyDetail::class,'property_id','id')->where('language_id',Helper::getLanguageId());
    }
    protected static $logName = 'Özellikler';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(function (string $eventName) {
                $ip = request()->ip();
                $log_name = self::$logName;
                $event = Helper::log_event($eventName);
                $user = Auth::user()->name ?? "??";

                return "<strong>{$ip}</strong> ip adresinden {$user} tarafından, <strong>{$log_name}</strong> modeline <strong>{$event}</strong> işlemi yapıldı.";
            })->useLogName('Özellikler');
    }

    protected static $logOnlyDirty = true;
}
