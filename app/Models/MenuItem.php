<?php

namespace App\Models;

use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use FileTrait;

    protected $guarded = [];

    public function sub_menu()
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id');
    }
}
