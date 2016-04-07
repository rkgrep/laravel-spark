<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

class Subscribed
{
    use Event, SerializesModels;
}
