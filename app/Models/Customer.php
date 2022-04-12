<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes, FileTrait;

    protected $guarded = [];
    protected $appends = ['default_photo'];

    public function languages()
    {
        return $this->hasMany(CustomerDetail::class, 'customer_id', 'id');
    }
    public function language()
    {
        return $this->hasOne(CustomerDetail::class, 'customer_id', 'id')->where('language_id', Helper::getLanguageId());
    }
}
