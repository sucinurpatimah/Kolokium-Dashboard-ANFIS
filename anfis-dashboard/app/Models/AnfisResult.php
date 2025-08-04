<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnfisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'y_aktual',
        'y_pred',
        'error_abs',
        'error_rel',
        'rmse',
        'mad',
        'aare',
        'akurasi'
    ];
}
