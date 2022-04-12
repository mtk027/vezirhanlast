<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;

class Menu extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected static $logName = 'Menü';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(function (string $eventName) {
                $ip = request()->ip();
                $log_name = self::$logName;
                $event = Helper::log_event($eventName);
                $user = Auth::user()->name ?? "??";

                return "<strong>{$ip}</strong> ip adresinden {$user} tarafından, <strong>{$log_name}</strong> modeline <strong>{$event}</strong> işlemi yapıldı.";
            })->useLogName('Menü');
    }

    protected static $logOnlyDirty = true;
}
