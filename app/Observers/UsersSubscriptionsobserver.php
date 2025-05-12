<?php

namespace App\Observers;

use App\Models\UsersSubscriptions;

class UsersSubscriptionsobserver
{
    /**
     * Handle the UsersSubscriptions "created" event.
     *
     * @param  \App\Models\UsersSubscriptions  $usersSubscriptions
     * @return void
     */
    public function created(UsersSubscriptions $usersSubscriptions)
    {
        //
        $usersSubscriptions->order_id = "SUBS".$usersSubscriptions->id;
         $usersSubscriptions->save();
    }

    /**
     * Handle the UsersSubscriptions "updated" event.
     *
     * @param  \App\Models\UsersSubscriptions  $usersSubscriptions
     * @return void
     */
    public function updated(UsersSubscriptions $usersSubscriptions)
    {
        //
    }

    /**
     * Handle the UsersSubscriptions "deleted" event.
     *
     * @param  \App\Models\UsersSubscriptions  $usersSubscriptions
     * @return void
     */
    public function deleted(UsersSubscriptions $usersSubscriptions)
    {
        //
    }

    /**
     * Handle the UsersSubscriptions "restored" event.
     *
     * @param  \App\Models\UsersSubscriptions  $usersSubscriptions
     * @return void
     */
    public function restored(UsersSubscriptions $usersSubscriptions)
    {
        //
    }

    /**
     * Handle the UsersSubscriptions "force deleted" event.
     *
     * @param  \App\Models\UsersSubscriptions  $usersSubscriptions
     * @return void
     */
    public function forceDeleted(UsersSubscriptions $usersSubscriptions)
    {
        //
    }
}
