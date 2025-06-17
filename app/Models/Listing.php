<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Listing - job model.
 * The name is "Listing" because the table named "jobs" already exists in the database.
 */
class Listing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'roles',
        'job_type',
        'address',
        'salary',
        'application_close_date',
        'feature_image',
        'slug'
    ];

    /**
     * Get the users (job seekers) who applied for a job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'listing_user', 'listing_id', 'user_id')
            ->withPivot('shortlisted')
            ->withTimestamps();
    }

    /**
     * Get the user (employer) that posted the job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
