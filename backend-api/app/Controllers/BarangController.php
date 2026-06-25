<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BarangModel;

class BarangController extends ResourceController
{
    use ResponseTrait;

    // GET /barang (Bisa diakses Public / Tanpa Login)
    public function index()
    {
        $model = new BarangModel();
        
        // Mengambil semua data barang dari database
        $data = $model->findAll();
        
        return $this->respond($data, 200);
    }

    // POST /barang (Harus Login / Menyertakan Token Valid)
    public function create()
    {
        $model = new BarangModel();
        $json  = $this->request->getJSON(); // Menerima payload JSON dari Axios VueJS

        if (!$json) {
            return $this->fail('Payload data tidak boleh kosong.', 400);
        }

        $data = [
            'id_kategori' => $json->id_kategori ?? 1, // Default ke id_kategori 1 jika tidak diisi
            'nama_barang' => $json->nama_barang ?? null,
            'stok'        => $json->stok ?? 0,
            'harga'       => $json->harga ?? 0,
            'supplier'    => $json->supplier ?? null,
        ];

        // Validasi input minimal sebelum insert
        if (empty($data['nama_barang'])) {
            return $this->fail('Nama barang wajib diisi!', 400);
        }

        if ($model->insert($data)) {
            return $this->respondCreated([
                'status'  => true,
                'message' => 'Data barang berhasil ditambahkan.'
            ]);
        }
        
        return $this->fail('Gagal menambahkan data barang ke database.');
    }

    // PUT /barang/(:id) (Harus Login / Menyertakan Token Valid)
    public function update($id = null)
    {
        $model = new BarangModel();
        $json  = $this->request->getJSON();

        if (!$id) {
            return $this->fail('ID barang tidak spesifik atau tidak ditemukan.', 400);
        }

        // Cek apakah data barang memang ada di database
        if (!$model->find($id)) {
            return $this->failNotFound('Data barang yang ingin diperbarui tidak ditemukan.');
        }

        if (!$json) {
            return $this->fail('Tidak ada perubahan data yang dikirim.', 400);
        }

        $data = [
            'id_kategori' => $json->id_kategori ?? 1,
            'nama_barang' => $json->nama_barang,
            'stok'        => $json->stok,
            'harga'       => $json->harga,
            'supplier'    => $json->supplier,
        ];

        if ($model->update($id, $data)) {
            return $this->respond([
                'status'  => true,
                'message' => 'Data barang berhasil diperbarui.'
            ], 200);
        }
        
        return $this->fail('Gagal memperbarui data barang.');
    }

    // DELETE /barang/(:id) (Harus Login / Menyertakan Token Valid)
    public function delete($id = null)
    {
        $model = new BarangModel();

        if (!$id) {
            return $this->fail('ID barang tidak valid.', 400);
        }

        // Cek eksistensi data sebelum melakukan aksi hapus
        if ($model->find($id)) {
            $model->delete($id);
            return $this->respondDeleted([
                'status'  => true,
                'message' => 'Data barang berhasil dihapus dari sistem.'
            ]);
        }
        
        return $this->failNotFound('Data barang tidak ditemukan atau sudah dihapus sebelumnya.');
    }

    // ====================================================================
    // FUNGSI TAMBAHAN DIOSES & DIPERBARUI (Bisa diakses Publik / Tanpa Login)
    // ====================================================================
    // POST /barang/beli
    public function beli()
    {
        $db    = \Config\Database::connect();
        $model = new BarangModel();
        $json  = $this->request->getJSON();

        // Ditambahkan pengecekan payload metode_pembayaran sesuai arahan checkout baru
        if (!$json || !isset($json->id_barang) || !isset($json->jumlah) || !isset($json->metode_pembayaran)) {
            return $this->fail('Data pembelian atau metode pembayaran tidak lengkap.', 400);
        }

        $id_barang  = $json->id_barang;
        $jumlahBeli = (int)$json->jumlah;
        $metode     = $json->metode_pembayaran;

        // Cari barang berdasarkan ID
        $barang = $model->find($id_barang);

        if (!$barang) {
            return $this->failNotFound('Barang tidak ditemukan atau sudah dihapus.');
        }

        $stokSekarang = (int)$barang['stok'];

        // Validasi ketersediaan stok sebelum transaksi publik
        if ($stokSekarang < $jumlahBeli) {
            return $this->fail('Gagal membeli, stok yang tersedia tidak mencukupi.', 400);
        }

        // Hitung akumulasi harga total belanja & kalkulasi sisa stok baru
        $totalHarga = (int)$barang['harga'] * $jumlahBeli;
        $stokBaru   = $stokSekarang - $jumlahBeli;

        // Menggunakan mekanisme DB Transactions demi keamanan integritas relasi data log struk
        $db->transStart();
        
        // 1. Update sisa persediaan barang
        $model->update($id_barang, ['stok' => $stokBaru]);
        
        // 2. Insert record baru ke dalam tabel log riwayat transaksi penjualan
        $db->table('transaksi')->insert([
            'id_barang'         => $id_barang,
            'nama_barang'       => $barang['nama_barang'],
            'jumlah'            => $jumlahBeli,
            'total_harga'       => $totalHarga,
            'metode_pembayaran' => $metode
        ]);
        
        $idTransaksiBaru = $db->insertID();
        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return $this->fail('Gagal memproses transaksi pembelian.', 500);
        }

        // Mengembalikan response sukses komplit beserta payload data struk belanjaan digital
        return $this->respond([
            'status'  => true,
            'message' => 'Pembelian berhasil disetujui!',
            'struk'   => [
                'id_transaksi'      => $idTransaksiBaru,
                'nama_barang'       => $barang['nama_barang'],
                'harga_satuan'      => $barang['harga'],
                'jumlah'            => $jumlahBeli,
                'total_harga'       => $totalHarga,
                'metode_pembayaran' => $metode,
                'waktu'             => date('Y-m-d H:i:s')
            ]
        ], 200);
    }

    // ====================================================================
    // FUNGSI KHUSUS BARU UNTUK DASHBOARD ADMIN (Terproteksi Filter Auth)
    // ====================================================================
    // GET /transaksi
    public function getTransaksi()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('transaksi')->orderBy('id_transaksi', 'DESC');
        
        return $this->respond($builder->get()->getResultArray(), 200);
    }
}