<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    // Nama tabel di database db_inventory (sesuai image_0a2daa.jpg)
    protected $table            = 'barang';
    
    // Primary key tabel barang
    protected $primaryKey       = 'id_barang';
    
    // Pastikan menggunakan auto increment jika id_barang bertipe INT AI
    protected $useAutoIncrement = true;
    
    // Format return data saat menggunakan fungsi seperti findAll() atau find()
    protected $returnType       = 'array';
    
    // Proteksi kolom: Hanya kolom di bawah ini yang diizinkan untuk di-insert/update
    protected $allowedFields    = [
        'id_kategori', 
        'nama_barang', 
        'stok', 
        'harga', 
        'supplier'
    ];

    // Mengaktifkan fitur pencatatan waktu otomatis (opsional, sesuaikan jika tabel memiliki kolom ini)
    protected $useTimestamps    = false;
}