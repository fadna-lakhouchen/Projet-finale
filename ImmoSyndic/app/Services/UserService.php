<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Override create to hash password.
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return parent::create($data);
    }

    /**
     * Override update to hash password if provided.
     */
    public function update(int $id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return parent::update($id, $data);
    }
}
