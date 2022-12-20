<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use APP\Models\SecondaryCategory;

class PrimaryCategory extends Model
{
    use HasFactory;

    public function secondary()
    {
        //primaryは複数のcategoryをもてるのでhasManyとする
        return $this->hasMany(SecondaryCategory::class);
    }
}