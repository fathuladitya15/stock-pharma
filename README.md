# Sistem Manajemen Stok dengan POQ - Laravel

Sistem ini merupakan aplikasi manajemen stok barang yang menerapkan metode **POQ (Periodic Order Quantity)** untuk membantu menentukan kapan dan berapa banyak barang yang perlu dipesan secara periodik berdasarkan data permintaan dan biaya.

---

## 🗂️ Struktur Tabel Utama

### 1. `products`
Menyimpan informasi produk.

| Kolom                | Deskripsi                                      |
|----------------------|-----------------------------------------------|
| product_code         | Kode unik produk                              |
| name                 | Nama produk                                   |
| category_id          | Kategori produk (relasi ke tabel `categories`)|
| unit                 | Satuan                                        |
| stock                | Stok saat ini                                 |
| min_stock            | Batas minimum stok                            |
| lead_time            | Waktu tunggu pengiriman (hari)                |
| average_demand       | Permintaan rata-rata bulanan                  |
| poq_quantity         | Hasil perhitungan POQ                         |
| ordering_cost        | Biaya pemesanan                               |
| holding_cost_percent | Persentase biaya penyimpanan per tahun        |
| selling_price        | Harga jual                                    |

### 2. `categories`
Menyimpan kategori produk.

| Kolom | Deskripsi     |
|-------|----------------|
| name  | Nama kategori  |

### 3. `stock_movements`
Mencatat pergerakan stok masuk/keluar.

| Kolom     | Deskripsi                       |
|-----------|----------------------------------|
| type      | Jenis (in/out)                  |
| quantity  | Jumlah pergerakan               |
| note      | Catatan (opsional)              |
| user_id   | Pengguna yang melakukan aksi    |

### 4. `purchase_orders`
Menyimpan data pemesanan ke supplier.

| Kolom     | Deskripsi                               |
|-----------|------------------------------------------|
| po_number | Nomor PO unik                            |
| supplier_id | ID Supplier                            |
| order_date | Tanggal pemesanan                       |
| status    | Status PO (`draft`, `sent`, `received`, `cancelled`) |
| note      | Catatan tambahan                         |

---

## 📈 Perhitungan POQ

### Rumus:
\[
POQ = \sqrt{\frac{2 \times S \times P}{H}}
\]

- **S** = ordering_cost
- **P** = periode per tahun (default: 12 bulan)
- **H** = holding_cost_percent × selling_price

Hasil perhitungan disimpan di kolom `poq_quantity`.

---

## ⚙️ Scheduler / Artisan Command (Opsional)

Perhitungan POQ bisa dijalankan melalui:

```bash
php artisan calculate:poq
