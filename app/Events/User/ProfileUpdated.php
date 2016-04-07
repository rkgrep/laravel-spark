<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Event, SerializesModels;
}
