<?php

namespace App\Observers;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Send a welcome email to the newly created user.
     *
     * @param User $user The newly created user instance.
     *
     * @return void
     */
    public function created(User $user): void
    {
        
        Log::info('Welcome email sent to '.  $user -> role() -> first() -> name);
        SendWelcomeEmailJob::dispatch($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
