<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

class SubscriptionPlanChanged
{
    use Event, SerializesModels;
}
