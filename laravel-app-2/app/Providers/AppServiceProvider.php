<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::define('view-financials', function (\App\Models\User $user) {
            return $user->role === 'admin';
        });

        // Set global default password rules
        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers();
        });

        // Fix SSL certificate verification error on Windows (XAMPP) for Symfony Mailer
        // The `stream` option in config/mail.php does NOT affect Symfony Mailer.
        // We must extend the transport at the Symfony level.
        Mail::extend('smtp', function (array $config) {
            $port = (int) ($config['port'] ?? 587);
            $encryption = $config['encryption'] ?? $config['scheme'] ?? null;

            // tls=true means direct SSL (port 465 / smtps)
            // tls=false means plain connection with STARTTLS upgrade (port 587)
            $useSsl = ($encryption === 'ssl' || $encryption === 'smtps' || $port === 465);

            $transport = new EsmtpTransport(
                host: $config['host'] ?? '127.0.0.1',
                port: $port,
                tls: $useSsl,
            );

            // Disable SSL peer verification – safe for local/development environment
            $stream = $transport->getStream();
            if ($stream instanceof \Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream) {
                $stream->setStreamOptions([
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true,
                    ],
                ]);
            }

            if (!empty($config['username'])) {
                $transport->setUsername($config['username']);
            }
            if (!empty($config['password'])) {
                $transport->setPassword($config['password']);
            }

            return $transport;
        });
    }
}
