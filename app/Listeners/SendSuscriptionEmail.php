<?php

namespace App\Listeners;

use App\Events\SuscriptionToCourse;
use App\Jobs\SendSubscriptionEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSuscriptionEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SuscriptionToCourse $event): void
    {
        SendSubscriptionEmailJob::dispatch($event -> user, $event -> course); 
    }
}
