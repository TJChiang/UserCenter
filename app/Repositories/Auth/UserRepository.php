<?php

namespace App\Repositories\Auth;

use App\Models\User;

class UserRepository
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function create($data)
    {
        return $this->userModel->create($data);
    }

    public function getUserByColumn($colName, $param)
    {
        return $this->userModel->where($colName, $param)->get();
    }

    public function getUserByLineId($lineId)
    {
        return $this->getUserByColumn('line_id', $lineId)->first();
    }

    public function getUserById($id)
    {
        return $this->userModel->find($id);
    }

    public function update($id, $data)
    {
        return $this->userModel->find($id)->update($data);
    }
}
