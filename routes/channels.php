<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('pm-notifications', function ($user) {
    return $user->isProjectManager();
});