<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

class Registered
{
    use Event, SerializesModels;
}
