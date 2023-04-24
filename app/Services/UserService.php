<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\v1\DTO\UserData;
use App\Api\v1\Exceptions\EmptyUserException;
use App\Models\Role;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    private User $user;
    private $token;

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function setToken($token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function register(UserData $data): static
    {
        $data->password = bcrypt($data->password);

        $this->setUser(User::create($data->toArray()));

        $this->setToken(JWTAuth::fromUser($this->user));

        return $this;
    }

    /**
     * @throws EmptyUserException
     */
    public function attachRole(?Role $role): static
    {
        $this->checkUser();
        if ($role) {
            $this->user->roles()->sync($role->id);
        }

        return $this;
    }

    /**
     * @throws EmptyUserException
     */
    public function setParent(?User $user)
    {
        $this->checkUser();

        $this->user->update([
            'parent_id' => $user?->id,
        ]);

        return $this;
    }

    /**
     * @throws EmptyUserException
     */
    public function updateUser(UserData|array $data)
    {
        $this->checkUser();

        if (!is_array($data)) {
            $data = $data->toArray();
        }

        unset($data['password']);

        $this->user->update($data);

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * @throws EmptyUserException
     */
    private function checkUser(): void
    {
        if (!isset($this->user)) {
            throw new EmptyUserException('User not found. Use setUser() method to define user object');
        }
    }
}
