<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashPasswords extends Command
{
    protected $signature = 'passwords:hash';
    protected $description = 'Update all user passwords to Bcrypt';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Проверяем, хеширован ли пароль
            if (!Hash::needsRehash($user->password)) {
                // Если пароль не хеширован, хешируем его и сохраняем
                $user->password = Hash::make($user->password);
                $user->save();
                $this->info("Password for user {$user->id} has been updated.");
            }
        }

        $this->info('All passwords have been updated.');
    }
}
