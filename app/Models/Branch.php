<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use DateTimeInterface;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $user = request()->user();
    //         $model->created_by = $user->profile ? $user->profile->id : 0;
    //         $model->created_by_name = $user->profile ? $user->profile->name : 'System';
    //     });

    //     static::updating(function ($model) {
    //         $user = request()->user();
    //         $model->updated_by = $user->profile ? $user->profile->id : 0;
    //         $model->updated_by_name = $user->profile ? $user->profile->name : 'System';
    //     });

    //     static::deleting(function ($model) {
    //         if (!$model->isForceDeleting()) {
    //             $user = request()->user();
    //             $model->deleted_by = $user->profile ? $user->profile->id : 0;
    //             $model->deleted_by_name = $user->profile ? $user->profile->name : 'System';
    //             $model->deleted_status = null;
    //             $model->save();
    //         }
    //     });
    // }
}
