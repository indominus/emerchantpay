<?php

namespace App\Model;

class PostModel extends BaseModel
{

    protected $tableName = 'posts';


    public function validate($data)
    {
        if (!isset($data['title'], $data['content'])) {
            throw new \InvalidArgumentException('Missing required parameters', 400);
        }

        if (!isset($data['id'])) {
            $data['user_id'] = $_SESSION['user']['id'];
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }
}
