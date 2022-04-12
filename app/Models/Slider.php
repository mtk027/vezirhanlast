<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Slider extends Model
{
    use SoftDeletes, FileTrait, LogsActivity;

    protected $guarded = [];

    protected $appends = ['default_photo', 'video'];
    protected static $logName = 'Slider';

    public function languages()
    {
        return $this->hasMany(SliderDetail::class, 'slider_id', 'id');
    }
    public function language()
    {
        return $this->hasOne(SliderDetail::class, 'slider_id', 'id')->where('language_id', Helper::getLanguageId());
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(function (string $eventName) {
                $ip = request()->ip();
                $log_name = self::$logName;
                $event = Helper::log_event($eventName);
                $user = Auth::user()->name ?? "??";

                return "<strong>{$ip}</strong> ip adresinden {$user} tarafından, <strong>{$log_name}</strong> modeline <strong>{$event}</strong> işlemi yapıldı.";
            })->useLogName('Slider');
    }

    protected static $logOnlyDirty = true;
}
