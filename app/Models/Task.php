<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name_task',
        'description_task',
        'status',
        'available'
    ];


    /**
     * Relacion uno a muchos inversa
     */
    public function user(){
        return $this->belongsToMany(User::class);
    }
}
