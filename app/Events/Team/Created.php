<?php

namespace App\Events\Team;

use Illuminate\Queue\SerializesModels;

class Created
{
    use SerializesModels;

    /**
     * The team being created.
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
