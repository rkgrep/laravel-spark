<?php

namespace App\Events\Team;

use Illuminate\Queue\SerializesModels;

class Deleting
{
    use SerializesModels;

    /**
     * The team being deleted.
     *
     * @var mixed
     */
    public $team;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $team
     * @return void
     */
    public function __construct($team)
    {
        $this->team = $team;
    }
}
