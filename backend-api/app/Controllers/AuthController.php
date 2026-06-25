<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    // POST /auth/login
    public function login()
    {
        $db = \Config\Database::connect();
        
        // Mengambil kiriman data JSON dari Axios Frontend VueJS
        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->username) || !isset($json->password)) {
            return $this->fail('Username dan Password wajib diisi!', 400);
        }

        $username = $json->username;
        $password = $json->password;

        // Cari record data admin di tabel users
        $user = $db->table('users')->getWhere(['username' => $username])->getRow();

        if ($user) {
            // 1. Coba verifikasi jika password di database menggunakan enkripsi Bcrypt hash
            $verifyPassword = password_verify($password, $user->password);
            
            // 2. Jika gagal (karena berupa teks biasa seperti 'admin123'), lakukan pencocokan langsung
            if (!$verifyPassword && $password === $user->password) {
                $verifyPassword = true;
            }

            // Jika salah satu metode di atas cocok, berikan akses masuk
            if ($verifyPassword) {
                
                // Membuat Token acak baru (Bearer Token)
                $token = bin2hex(random_bytes(32));

                // Update data token baru ke dalam database tabel users
                $db->table('users')->where('id', $user->id)->update(['token' => $token]);

                // Kirim respons sukses terstruktur ke frontend SPA
                return $this->respond([
                    'status'     => true,
                    'message'    => 'Login berhasil!',
                    'isLoggedIn' => true,
                    'token'      => $token,
                    'username'   => $user->username
                ], 200);
            }
        }

        // Jika salah username atau ketikan password tidak cocok di kedua metode
        return $this->failUnauthorized('Username atau Password salah.');
    }

    // POST /auth/logout
    public function logout()
    {
        $db = \Config\Database::connect();
        
        // Mengambil Authorization Bearer Token dari request header
        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            
            // Hapus eksistensi token dengan mengubah nilainya menjadi NULL kembali
            $db->table('users')->where('token', $token)->update(['token' => null]);
            
            return $this->respond([
                'status'  => true,
                'message' => 'Logout berhasil, token telah dihapus.'
            ], 200);
        }

        return $this->fail('Token otentikasi tidak ditemukan.', 400);
    }
}