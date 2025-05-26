<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */

    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Email Akun Kamu')
                ->line('Klik tombol di bawah untuk verifikasi email kamu.')
                ->action('Verifikasi Sekarang', $url)
                ->line('Abaikan email ini jika kamu tidak mendaftar.');
        });
    }

}
