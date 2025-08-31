<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Employee extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','name','email','department_id'];


    protected static function booted(): void {
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) \Str::uuid();
            }
        });
    }


    public function department() {
        return $this->belongsTo(Department::class);
    }
    public function detail() {
        return $this->hasOne(EmployeeDetail::class);
    }
}
