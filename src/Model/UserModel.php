<?php

namespace App\Model;
;

class UserModel extends BaseModel
{

    protected $tableName = 'users';

    public function validate($data)
    {
        if (!isset($data['username'], $data['password'], $data['email'])) {
            throw new \InvalidArgumentException('Missing required parameters', 400);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address', 400);
        }

        $exists = $this->selectOnce(['username' => $data['username']]);

        if (!empty($exists) && (!isset($data['id']) || $exists['id'] !== $data['id'])) {
            throw new \InvalidArgumentException("User with name {$data['username']} already exists", 400);
        }

        if (!isset($data['id'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
