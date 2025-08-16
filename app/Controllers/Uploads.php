<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Uploads extends BaseController
{
    public function profile($filename)
    {
        $filepath = WRITEPATH . 'uploads/profile/' . $filename;
        
        if (file_exists($filepath)) {
            $mime = mime_content_type($filepath);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            readfile($filepath);
            exit;
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan: ' . $filename);
        }
    }
    
    public function bukti($filename)
    {
        $filepath = WRITEPATH . 'uploads/bukti/' . $filename;
        
        if (file_exists($filepath)) {
            $mime = mime_content_type($filepath);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            readfile($filepath);
            exit;
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan: ' . $filename);
        }
    }
}
