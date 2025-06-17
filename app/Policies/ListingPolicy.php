<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return bool
     */
    public function view(User $user, Listing $listing)
    {
        return $user->id === $listing->user_id;
    }
}
