<?php
namespace App\Repositories;

use App\Models\User;


class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getByUsername(string $username)
    {
        return $this->user->where('username', $username)->first();
    }

    public function getById(int $id)
    {
        return $this->user->where('user_id', $id)->first();
    }

    public function getUsers()
    {
        return $this->user->all();
    }

    public function create()
    {
        return $this->user->create(['username' => bin2hex(random_bytes(5))]);
    }

    public function editUser(array $data, int $id)
    {
        return $this->user->where('user_id', $id)
            ->update($data);
    }

}