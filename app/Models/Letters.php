<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Letters extends Model
{
    use SoftDeletes;

    protected $table = "letters";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];
}
