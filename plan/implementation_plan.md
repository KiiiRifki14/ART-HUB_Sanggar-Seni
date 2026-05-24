# Rencana Implementasi: Redesain Tampilan Admin Panel (Golden Ratio & Responsive)

Dokumen ini berisi rencana teknis untuk merombak visual antarmuka Admin Panel sistem **ART-HUB Sanggar Cahaya Gumilang** agar lebih premium, modern, dan sangat responsif di perangkat mobile.

---

## 1. Masalah Utama & Solusi Desain

### A. Sidebar Kekecilan & Kaku (Kurang Premium)
* **Masalah:** Lebar sidebar terlalu kecil (`260px` di desktop) sehingga terkesan sempit, font kecil, dan tidak ada ruang bernapas. Struktur visualnya monoton dan tombol toggle sidebar desktop hilang dari HTML, mengakibatkan error JavaScript (`TypeError: Cannot read properties of null (reading 'querySelector')`) saat memuat halaman yang menghentikan eksekusi script lain.
* **Solusi:** 
  1. Tingkatkan lebar sidebar desktop menjadi **`280px`** (mini-state **`72px`**).
  2. Kembalikan tombol toggle sidebar `#sidebarToggle` di sebelah logo brand dengan visual hover effect emas yang menawan.
  3. Desain ulang profile card admin menjadi sebuah card inset dengan latar belakang semi-transparan (*glassmorphism*) yang modern.
  4. Tambahkan animasi halus (*hover translate*, *left gold border indicator*) pada item menu untuk meningkatkan kepuasan pengguna.

### B. Tabel Meluber & Terpotong di Mobile
* **Masalah:** Tabel antrean data (seperti DP Verification, Kostum Sewa, dan Jadwal Latihan) memaksa pengguna melakukan *horizontal scrolling* di mobile.
* **Solusi:**
  1. Sembunyikan tabel di layar kecil (`hidden md:table`) dan gantikan dengan layout kartu vertikal yang fleksibel (`block md:hidden`).
  2. Setiap kartu mobile dirancang khusus untuk memadatkan data krusial dalam ruang kecil tanpa mengurangi fungsi (aksi tombol, status badge, dll.).

### C. Ukuran Card Terlalu Besar di Mobile
* **Masalah:** Padding kartu bawaan (`p-6`) terlalu besar di layar handphone, memakan banyak ruang layar.
* **Solusi:** Gunakan responsive padding (`p-4 md:p-6` atau `p-3 md:p-5`) di seluruh kartu dashboard dan kartu konten lainnya agar lebih presisi.

### D. Penerapan Tipografi Golden Ratio (1.618)
* **Masalah:** Hierarki ukuran font tidak terstruktur dengan baik.
* **Solusi:** Konfigurasikan skala tipografi Golden Ratio ke dalam utilitas Tailwind CSS / CSS kustom:
  * **Heading 1 (H1):** `42px` (Line Height: `55px`) — *Gagah dan elegan*
  * **Heading 2 (H2):** `26px` (Line Height: `42px`) — *Proporsional*
  * **Body Text:** `16px` (Line Height: `26px`) — *Nyaman dibaca*
  * **Caption / Sub-teks:** `10px` (Line Height: `16px`) — *Presisi dan rapi*

---

## 2. Rencana Perubahan Komponen

### Component: Admin Layout Wrapper
Perubahan pada [admin.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/admin.blade.php):
* **Tailwind & CSS Extend:**
  Tambahkan utilitas font Golden Ratio:
  ```javascript
  fontSize: {
      'golden-h1': ['42px', { lineHeight: '55px' }],
      'golden-h2': ['26px', { lineHeight: '42px' }],
      'golden-body': ['16px', { lineHeight: '26px' }],
      'golden-caption': ['10px', { lineHeight: '16px' }],
  }
  ```
  Dan kelas utilitas CSS kustom untuk fallback.
* **Redesain Sidebar:**
  * Lebar default: `280px`, mini: `72px`.
  * Tambahkan tombol `#sidebarToggle` kembali di header sidebar.
  * Tambahkan efek glassmorphism (`bg-white/5 border border-white/10 backdrop-blur-md`) pada profil admin.
  * Navigasi menu: Gunakan transisi halus, bayangan emas halus pada hover, dan border kiri berwarna emas pada menu yang aktif.
  * Perbaiki navigasi mobile: Hamburger menu kustom di topbar kanan yang membuka slide-out panel sidebar secara lancar.

---

### Component: Halaman DP Verification
Perubahan pada [dp-verification.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/bookings/dp-verification.blade.php):
* **Tabel Desktop:** Beri kelas `hidden md:table`.
* **Mobile Cards View (`block md:hidden`):**
  Buat layout kartu berisi:
  * Baris Atas: Kode booking (`BK-XXX` badge) & tombol aksi cepat/Detail Bukti.
  * Baris Tengah: Nama klien, tipe acara, dan total kontrak.
  * Baris Bawah: Nominal DP (tulisan hijau tebal) & grup tombol aksi (Tolak, Verifikasi).
* **Modals & Cards Spacing:**
  Perbarui padding modal-modal verifikasi di mobile agar mengecil menjadi `p-4` dengan ukuran teks ringkas.

---

### Component: Halaman Kostum & Logistik
Perubahan pada [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/costumes/index.blade.php):
* Terapkan padding responsif pada grid Aset Sanggar (`p-4 md:p-5`).
* Pada tabel **Transaksi Sewa Vendor Eksternal**:
  * Bungkus tabel desktop dalam kelas `hidden md:table`.
  * Buat tampilan mobile kustom (`block md:hidden`) yang berulang untuk setiap penyewaan:
    * Kartu mini yang memuat kode Event, nama Vendor, jenis barang, kuantitas (`Qty`), batas pengembalian, status badge (Terlambat, Dipinjam, Kembali), serta nominal denda jika ada.

---

### Component: Halaman Jadwal Latihan
Perubahan pada [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/rehearsals/index.blade.php):
* **Tabel Desktop:** `hidden md:table`.
* **Mobile Cards View (`block md:hidden`):**
  * Buat susunan kartu jadwal dengan visualisasi tanggal yang jelas (seperti kalender mini di sebelah kiri: Tanggal & Bulan).
  * Detail kanan: Kode Event, nama klien, tipe latihan, jam operasional, lokasi latihan, dan catatan singkat.
  * Tombol aksi detail tetap mudah ditekan.

---

## 3. Rencana Verifikasi

### Manual Verification (oleh User)
1. **Desktop Test:** Buka admin panel, klik tombol toggle di sidebar untuk melipat/membuka sidebar. Verifikasi transisi berjalan mulus dan status mini tersimpan di browser.
2. **Mobile Layout Test:** Lakukan *inspect element* atau buka di HP. Pastikan sidebar tersembunyi sempurna dan hanya muncul via tombol hamburger di kanan atas topbar.
3. **Table to Card Transition:** Cek halaman **DP Verification**, **Kostum & Logistik**, dan **Jadwal Latihan** di mobile. Pastikan tidak ada scroll horizontal dan data terstruktur rapi dalam kartu-kartu kecil.
4. **Golden Ratio Typography Check:** Amati proporsi judul (H1/H2) dengan teks paragraf, pastikan terlihat seimbang dan nyaman dibaca sesuai aturan visual Golden Ratio.
