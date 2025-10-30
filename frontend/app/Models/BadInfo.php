<?php

namespace App\Models;

use App\Enums\YesNoEnum;
use Illuminate\Database\Eloquent\Model;

class BadInfo extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'family_history' => YesNoEnum::class,
        'stress'  => YesNoEnum::class,
        'anemia'  => YesNoEnum::class,
        'chronic_illness'  => YesNoEnum::class,
    ];
}
