<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Offer;
use Illuminate\Auth\Access\Response;

class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Offers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Offer $offer): bool
    {
        return $user->hasPermissionTo('Read-Offers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Offer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Offer $offer): bool
    {
        return $user->hasPermissionTo('Update-Offer');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Offer $offer): bool
    {
        return $user->hasPermissionTo('Delete-Offer');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Offer $offer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Offer $offer): bool
    {
        //
    }
}
