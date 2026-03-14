<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'user_type_id',
        'page_id',
    ];

    /**
     * Permission belongs to UserType
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    /**
     * Permission belongs to Page
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
