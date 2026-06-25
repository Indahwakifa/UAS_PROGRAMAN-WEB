<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        // 1. Tangkap input JSON dari Axios frontend
        $json = $this->request->getJSON();
        $username = $json->username ?? '';
        $password = $json->password ?? '';

        // 2. Hubungkan langsung ke database db_inventory tanpa perlu membuat file Model baru (Query Builder manual)
        $db   = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        // 3. Jika user tidak ditemukan
        if (!$user) {
            return $this->failUnauthorized('Username tidak terdaftar di sistem.');
        }

        // 4. Cocokkan password hash Bcrypt dari phpMyAdmin (Mendukung hash di image_0a2daa.jpg)
        $verifyPassword = password_verify($password, $user['password']);
        
        // Catatan Darurat UAS: Jika password di DB berupa teks biasa 'admin123' (seperti di image_0a2a23.jpg)
        if (!$verifyPassword && $password === $user['password']) {
            $verifyPassword = true;
        }

        if (!$verifyPassword) {
            return $this->failUnauthorized('Password yang Anda masukkan salah.');
        }

        // 5. Generate Token acak tiruan yang sah untuk memenuhi spesifikasi pengujian localStorage Frontend
        $rawToken = bin2hex(random_bytes(32));

        // Simpan token ke database agar AuthFilter bisa memvalidasinya nanti
        $db->table('users')->where('id', $user['id'])->update(['token' => $rawToken]);

        // 6. Kirim respon sukses balik ke Axios VueJS
        return $this->respond([
            'status'   => true,
            'message'  => 'Login Berhasil!',
            'token'    => $rawToken,
            'username' => $user['username']
        ], 200);
    }
}