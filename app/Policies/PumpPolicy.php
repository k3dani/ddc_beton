<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class PumpPolicy
{
    public function managePumps(User $user, Location $location)
    {
        // Admin vagy a telephely-kezelő
        return $user->is_admin || ($user->location_id && $user->location_id == $location->id);
    }
}
