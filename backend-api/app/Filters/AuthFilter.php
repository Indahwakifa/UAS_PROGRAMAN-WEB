<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;

class AuthFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        // JALUR AMAN: Jika request adalah OPTIONS (Preflight dari browser), langsung loloskan!
        // Ini sangat krusial agar browser tidak memblokir CORS saat berkomunikasi dengan VueJS
        if (strtolower($request->getMethod()) === 'options') {
            return;
        }

        // Ambil header Authorization dari request frontend
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');

        // Jika header tidak ada atau tidak diawali dengan 'Bearer '
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $response = Services::response();
            $response->setStatusCode(401);
            $response->setJSON([
                'status'  => false,
                'message' => 'Akses Ditolak. Token tidak ditemukan atau format salah.'
            ]);
            return $response;
        }

        $token = $matches[1];
        $db    = \Config\Database::connect();
        
        // Cek apakah token terdaftar di database tabel users
        $user = $db->table('users')->getWhere(['token' => $token])->getRow();

        if (!$user) {
            $response = Services::response();
            $response->setStatusCode(401);
            $response->setJSON([
                'status'  => false,
                'message' => 'Sesi Anda telah habis atau Token tidak valid.'
            ]);
            return $response;
        }

        // Lolos validasi, simpan data user di request agar bisa dipakai di controller jika butuh
        $request->user = $user;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request selesai
    }
}