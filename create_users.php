<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@chathubb.test'],
    [
        'name' => 'Admin User',
        'password' => Hash::make('wenshenx'),
        'email_verified_at' => now(),
    ]
);
echo "Created/Updated: {$admin->email}\n";

// Create wegelewis7 user
$user = User::updateOrCreate(
    ['email' => 'wegelewis7@gmail.com'],
    [
        'name' => 'Lewis Wege',
        'password' => Hash::make('wenshenx'),
        'email_verified_at' => now(),
    ]
);
echo "Created/Updated: {$user->email}\n";

echo "\nTotal users: " . User::count() . "\n";
