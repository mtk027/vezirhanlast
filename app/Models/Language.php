<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Language extends Model
{
    use LogsActivity;
    protected $guarded = [];
    protected static $logName = 'Dil';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(function (string $eventName) {
                $ip = request()->ip();
                $log_name = self::$logName;
                $event = Helper::log_event($eventName);
                $user = Auth::user()->name ?? "??";

                return "<strong>{$ip}</strong> ip adresinden {$user} tarafından, <strong>{$log_name}</strong> modeline <strong>{$event}</strong> işlemi yapıldı.";
            })->useLogName('Dil');
    }

    protected static $logOnlyDirty = true;
}
