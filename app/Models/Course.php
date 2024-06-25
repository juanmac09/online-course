<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];


    // Relationships

    /**
     * Get the users that belong to the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollements');
    }



    /**
     * Get the author of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the contents that belong to the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(CourseContent::class);
    }

    /**
     * Get the qualifications that belong to the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualification(): HasMany
    {
        return $this->hasMany(Qualification::class);
    }
}
