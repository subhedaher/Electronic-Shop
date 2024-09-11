<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Coupon;
use Illuminate\Auth\Access\Response;

class CouponPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Coupons');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Coupon $coupon): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Coupon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Coupon $coupon): bool
    {
        return $user->hasPermissionTo('Update-Coupon');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Coupon $coupon): bool
    {
        return $user->hasPermissionTo('Delete-Coupon');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Coupon $coupon): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Coupon $coupon): bool
    {
        //
    }
}
