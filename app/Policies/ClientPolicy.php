<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Client $client)
    {
        return $user->company_id === $client->company_id;
    }

    public function update(User $user, Client $client)
    {
        return $user->company_id === $client->company_id;
    }

    public function delete(User $user, Client $client)
    {
        return $user->company_id === $client->company_id;
    }

}
