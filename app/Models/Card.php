<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['title', 'description', 'deadline', 'status', 'assigned_to', 'created_by'];
    protected $appends = ['assignee_name']; // Добавляем виртуальное поле
    public function userAssigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAssigneeNameAttribute()
    {
        return $this->userAssigned ? $this->userAssigned->name : null;
    }
}
