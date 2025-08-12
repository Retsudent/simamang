<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $useTimestamps = false; // created_at handled by DB default
    protected $allowedFields = [
        'nama', 'username', 'password', 'role', 'nis', 'tempat_magang'
    ];

    // Jangan kembalikan password saat fetch data publik
    protected $returnType = 'array';

    public function findByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
