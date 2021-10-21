<?php

namespace App\Model;

class MediaModel extends BaseModel
{

    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    const ALLOWED_FILE_TYPE = ["image/jpeg", "image/gif", "image/png"];

    const ERROR_MESSAGES = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    protected $tableName = 'media';

    public function validate($data)
    {
        if (empty($data)) {
            return null;
        }

        if ($data['error'] !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException(self::ERROR_MESSAGES[$data['error']]);
        }

        if ($data['size'] > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException('File size exceeded');
        }

        if (!in_array($data['type'], self::ALLOWED_FILE_TYPE)) {
            throw new \InvalidArgumentException('Invalid media type');
        }

        $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
        $filename = md5($data['name'] . time()) . '.' . $extension;
        $target = __DIR__ . '/../../public/uploads/' . $filename;
        if (!move_uploaded_file($data['tmp_name'], $target)) {
            throw new \InvalidArgumentException('Error while uploading file');
        }

        return [
            'file_path' => $filename,
            'file_name' => $data['name'],
            'file_type' => $data['type'],
            'file_size' => $data['size'],
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}
