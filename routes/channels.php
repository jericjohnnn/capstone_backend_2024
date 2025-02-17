<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('user.{id}', function (User $authenticatedUser, int $userToBeNotifiedId) {
    return $authenticatedUser->id === $userToBeNotifiedId;
});
