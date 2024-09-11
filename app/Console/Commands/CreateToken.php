<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:create-token {email_user?}';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $email = $this->argument('email_user');
        if(is_null($email))
            $email = $this->ask('Email User ');

        $user = User::query()
            ->where('email',$email)
            ->first();
        if(is_null($user)){
            $this->error('User tidak ditemukan !');
        }else{
            $token = $user->createToken($email)->plainTextToken;
            $this->info('token : '.$token);
        }

    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat Token User';
}
