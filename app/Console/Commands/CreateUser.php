<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:create-user {--token}';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $name = $this->ask('Username ');
        $email = $this->ask('Email User ');
        $password = $this->ask('Password ');
        $user = User::query()
            ->where('email',$email)
            ->first();

        if(!is_null($user)){
            $this->error('Email user sudah terdaftar !');
        }else{
            $user = User::query()->create([
                    "name" => $name,
                    "email" => $email,
                    "password" => bcrypt($password),
                ]);

            if($this->option('token')){
                $token = $user->createToken($email)->plainTextToken;
                $this->info('token : '.$token);
            }else{
                $this->info('User Created successfully');
            }

        }

    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User';
}
