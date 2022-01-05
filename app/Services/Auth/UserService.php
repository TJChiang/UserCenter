<?php

namespace App\Services\Auth;

use App\Repositories\Auth\UserRepository;

class UserService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function create($data)
    {
        return $this->userRepo->create($data);
    }

    public function getUserByLineId($lineId)
    {
        return $this->userRepo->getUserByLineId($lineId);
    }

    public function getUserById($id)
    {
        return $this->userRepo->getUserById($id);
    }

    public function update($id, $data)
    {
        return $this->userRepo->update($id, $data);
    }
}
