<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        if ($session->get('isLoggedIn')) {
            $role = $session->get('role');
            return redirect()->to(match ($role) {
                'admin' => '/admin/dashboard',
                'pembimbing' => '/pembimbing/dashboard',
                'siswa' => '/siswa/dashboard',
                default => '/login',
            });
        }

        return redirect()->to('/login');
    }
}
