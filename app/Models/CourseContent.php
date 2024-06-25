<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseContent extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationships

    /**
     * Get the course that owns the course content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the comments that belong to the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }


    /**
     * Booted method is automatically called when the model is being used.
     * It allows us to perform some operations before the model is saved to the database.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($courseContent) {
            $maxOrder = self::where('course_id', $courseContent->course_id)->max('order');
            $courseContent->order = is_null($maxOrder) ? 1 : $maxOrder + 1;
        });
    }
}
