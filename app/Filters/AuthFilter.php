<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek apakah user sudah login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek role jika diperlukan (opsional: gunakan di routes ['filter' => 'auth:admin'])
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            if (!in_array($userRole, (array) $arguments, true)) {
                return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
