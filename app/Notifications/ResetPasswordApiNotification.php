<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordApiNotification extends ResetPassword
{
    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        // return url(route('password.reset', [
        //     'token' => $this->token,
        //     'email' => $notifiable->getEmailForPasswordReset(),
        // ], false));

        return config('services.client.url') . '/auth/password/reset?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset() . '';
    }
}
