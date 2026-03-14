<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'page',
        'route_name',
    ];

    /**
     * Page has many permissions
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'page_id');
    }
}
