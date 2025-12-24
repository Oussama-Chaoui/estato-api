<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel(
  'App.Models.User.{id}',
  function ($user, $id) {
    return (int) $user->id === (int) $id;
  }
);

Broadcast::channel(
  'admin-notifications',
  function ($user) {
    // Only allow admin users to listen to admin notifications
    $hasRole = $user->hasRole(\App\Enums\ROLE::ADMIN);
    
    \Log::info('🔔 Channel Authorization: admin-notifications', [
      'user_id' => $user->id,
      'user_email' => $user->email,
      'has_admin_role' => $hasRole,
      'authorized' => $hasRole
    ]);
    
    return $hasRole;
  }
);
