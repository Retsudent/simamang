<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
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
        try {
            $request = service('request');
            $username = $request->getPost('username');
            $password = $request->getPost('password');

            if (!$username || !$password) {
                return redirect()->back()->with('error', 'Username dan password wajib diisi')->withInput();
            }

            // Cek di semua tabel (admin, pembimbing, siswa)
            $user = $this->findUserInAllTables($username);
            
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
                'table'      => $user['table'], // tambahkan info tabel asal
                'foto_profil' => $user['foto_profil'] ?? null // tambahkan foto profil
            ]);

            return redirect()->to($this->roleRedirect($user['role']));
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')->withInput();
        }
    }

    private function findUserInAllTables($username)
    {
        try {
            // Cek di tabel users (unified system) - PRIORITAS UTAMA
            $user = $this->db->table('users')
                             ->where('username', $username)
                             ->where('status', 'aktif')
                             ->get()
                             ->getRowArray();
            
            if ($user) {
                $user['table'] = 'users';
                return $user;
            }

            // Fallback: Cek di tabel role-specific (untuk backward compatibility)
            $admin = $this->db->table('admin')
                              ->where('username', $username)
                              ->where('status', 'aktif')
                              ->get()
                              ->getRowArray();
            
            if ($admin) {
                $admin['role'] = 'admin';
                $admin['table'] = 'admin';
                return $admin;
            }

            $pembimbing = $this->db->table('pembimbing')
                                   ->where('username', $username)
                                   ->where('status', 'aktif')
                                   ->get()
                                   ->getRowArray();
            
            if ($pembimbing) {
                $pembimbing['role'] = 'pembimbing';
                $pembimbing['table'] = 'pembimbing';
                return $pembimbing;
            }

            $siswa = $this->db->table('siswa')
                              ->where('username', $username)
                              ->where('status', 'aktif')
                              ->get()
                              ->getRowArray();
            
            if ($siswa) {
                $siswa['role'] = 'siswa';
                $siswa['table'] = 'siswa';
                return $siswa;
            }

            return null;
        } catch (\Exception $e) {
            log_message('error', 'Database error in findUserInAllTables: ' . $e->getMessage());
            throw $e;
        }
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
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[3]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Cek apakah username sudah ada di semua tabel
        if ($this->isUsernameExists($request->getPost('username'))) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
        }
        
        // Cek apakah NIS sudah ada
        if ($this->isNISExists($request->getPost('nis'))) {
            return redirect()->back()->withInput()->with('error', 'NIS sudah terdaftar');
        }
        
        $passwordHash = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        
        $data = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $passwordHash,
            'nis' => $request->getPost('nis'),
            'tempat_magang' => $request->getPost('tempat_magang'),
            'alamat_magang' => $request->getPost('alamat_magang') ?: $request->getPost('tempat_magang'),
            'status' => 'aktif'
        ];

        try {
            // Insert ke tabel users (unified)
            $userData = [
                'nama' => $request->getPost('nama'),
                'username' => $request->getPost('username'),
                'password' => $passwordHash,
                'role' => 'siswa',
                'status' => 'aktif'
            ];
            
            $this->db->table('users')->insert($userData);
            $userId = $this->db->insertID();
            
            // Insert ke tabel siswa dengan foreign key
            $data['user_id'] = $userId;
            $this->db->table('siswa')->insert($data);
            
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    private function isUsernameExists($username)
    {
        // Cek di tabel users (unified) - PRIORITAS UTAMA
        $users = $this->db->table('users')->where('username', $username)->countAllResults();
        
        if ($users > 0) {
            return true;
        }
        
        // Fallback: Cek di tabel role-specific (untuk backward compatibility)
        $admin = $this->db->table('admin')->where('username', $username)->countAllResults();
        $pembimbing = $this->db->table('pembimbing')->where('username', $username)->countAllResults();
        $siswa = $this->db->table('siswa')->where('username', $username)->countAllResults();
        
        return ($admin > 0 || $pembimbing > 0 || $siswa > 0);
    }

    private function isNISExists($nis)
    {
        return $this->db->table('siswa')->where('nis', $nis)->countAllResults() > 0;
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
