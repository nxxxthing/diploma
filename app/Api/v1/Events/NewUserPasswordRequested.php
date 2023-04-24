<?php

namespace App\Api\v1\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserPasswordRequested
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $user;

    public $token;

    /**
     * Create a new event instance.
     *
     * @param $token
     * @param $user
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
