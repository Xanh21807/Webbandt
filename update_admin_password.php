<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@example.com')->first();

if ($user) {
    $user->password = Hash::make('admin123');
    $user->save();
    echo "✓ Password updated to 'admin123' for {$user->email}\n";
    echo "User ID: {$user->id}\n";
    echo "Role: {$user->role}\n";
    echo "Status: {$user->status}\n";
} else {
    echo "✗ User not found!\n";
}
