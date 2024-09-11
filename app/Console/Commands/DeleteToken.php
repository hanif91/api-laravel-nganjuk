<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:delete-token {email_user?}';

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
            $user->tokens()->delete();
            $this->info('Token Berhasil Terhapus...');
        }

    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat Token User';
}
