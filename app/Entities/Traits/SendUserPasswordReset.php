<?php

namespace App\Entities\Traits;

use App\Notifications\UserNeedsPasswordReset;

/**
 * Class SendUserPasswordReset.
 */
trait SendUserPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserNeedsPasswordReset($token));
    }
}
