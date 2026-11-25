<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {email}';

    protected $description = 'Send a test email and print success or the error';

    public function handle(): int
    {
        $email = (string) $this->argument('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error('Invalid email address.');

            return self::FAILURE;
        }

        try {
            Mail::raw('This is a test email from Doc2Due.', function ($message) use ($email): void {
                $message->to($email)->subject('[Doc2Due] Test email');
            });
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Test email sent to '.$email);

        return self::SUCCESS;
    }
}
