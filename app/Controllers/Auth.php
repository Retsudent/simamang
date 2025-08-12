<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
    }

    public function login()
    {
        // jika sudah login, redirect berdasarkan role
        if ($this->session->get('isLoggedIn')) {
            $role = $this->session->get('role');
            return redirect()->to($this->roleRedirect($role));
        }
        echo view('auth/login');
    }

    public function loginProcess()
    {
        $request = service('request');
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        if (!$username || !$password) {
            return redirect()->back()->with('error', 'Username dan password wajib diisi')->withInput();
        }

        $user = $this->userModel->findByUsername($username);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan')->withInput();
        }

        // password hashing: gunakan password_hash saat menyimpan password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah')->withInput();
        }

        // set session
        $this->session->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'nama'       => $user['nama'],
            'role'       => $user['role'],
        ]);

        return redirect()->to($this->roleRedirect($user['role']));
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        // jika sudah login, redirect berdasarkan role
        if ($this->session->get('isLoggedIn')) {
            $role = $this->session->get('role');
            return redirect()->to($this->roleRedirect($role));
        }
        echo view('auth/register');
    }

    public function registerProcess()
    {
        $request = service('request');
        
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[3]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $passwordHash = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        
        $data = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $passwordHash,
            'role' => 'siswa', // SELALU siswa, tidak bisa pilih role
            'nis' => $request->getPost('nis'),
            'tempat_magang' => $request->getPost('tempat_magang'),
        ];

        try {
            $this->userModel->insert($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    private function roleRedirect($role)
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'pembimbing' => '/pembimbing/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/'
        };
    }
}
