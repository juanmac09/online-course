<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permissions extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship

    /**
     * Get the roles for the permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions','permission_id');
    }
}
