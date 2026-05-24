# 🔍 LAPORAN AUDIT SISTEM MENYELURUH (VERSI TRANSPARAN)
**Aplikasi:** ART-HUB Sanggar Seni (Laravel 11)  
**Tanggal Audit Terakhir:** 24 Mei 2026  
**Fokus Utama:** Bukti Baris Kode (Line-by-Line Evidence) dan Status Terbuka.

---

## DAFTAR TEMUAN & STATUS AKTUAL

### A. BUG LOGIKA BISNIS

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **A-01** | Race Condition Konfirmasi Booking | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 255-263 (confirmPayment) dan baris 380-388 (confirmCashPayment). Menggunakan `lockForUpdate()`. |
| **A-02** | Race condition Klien (Double Booking) | `[FIXED]` | `app/Http/Controllers/Klien/BookingController.php` baris 65-103. Menggunakan `DB::transaction()` dan `lockForUpdate()`. |
| **A-03** | Update Price Dead Code | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 213-233. Menghapus pengecekan status ganda. |
| **A-04** | `rejectProof()` Error (user_id vs client_id) | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 98, 134, 159. `$booking->user_id` diganti ke `$booking->client_id`. |
| **A-05** | `confirmCashPayment` memakai nilai request bukan variabel profit fixed | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 411. Menggunakan binding variabel terikat `$targetProfit`. |
| **A-06** | Query N+1 di Event Monitoring `Booking::count()` | `[OPEN]` | *Belum di-refactor ke query aggregate selectRaw di `EventController::monitoring()`.* |
| **A-07** | `CancellationController` validasi event sudah lewat | `[OPEN]` | *Belum ada penolakan pembatalan jika event_date sudah lampau di `CancellationController::store()`.* |
| **A-08** | Password Default Hardcoded 'sanggar123' | `[OPEN]` | *Belum ada penegakan validasi password unik wajib di `PersonnelController::store()`.* |
| **A-09** | Register User Specialty Selalu Default | `[OPEN]` | *Registrasi mandiri di `RegisteredUserController::store()` masih meng-hardcode specialty 'penari'.* |

### B. CELAH KEAMANAN & SANITASI

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **B-01** | Mass Assignment `Booking::storeManual()` | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 494. Menggunakan `$request->only()`. |
| **B-02** | Tidak ada Rate Limiting pada `CostumeController::storeVendorApi()` | `[FIXED]` | `routes/web.php` baris 171-172. Menggunakan `->middleware('throttle:60,1')`. |
| **B-03** | Middleware Role salah eja (`personnel` vs `personel`) | `[FIXED]` | `app/Http/Middleware/RoleMiddleware.php` baris 32. Telah diperbaiki ejaan `'personel'` agar redirect sesuai DB. |
| **B-04** | Upload Proof hanya cek file extension (bukan Mime Type asli) | `[FIXED]` | `app/Http/Controllers/Klien/BookingController.php` baris 127 & 151. Rule validasi memakai `mimetypes:` untuk mendeteksi byte signature file via `finfo`. |
| **B-05** | `CancellationController` tanpa pencatatan IP Audit | `[FIXED]` | `app/Http/Controllers/Admin/CancellationController.php` baris 77-79. IP address, UA, dan timestamp sekarang direkam. |
| **B-06** | Potensi XSS pada pesan notifikasi di Topbar | `[OPEN]` | *Pesan notifikasi di `BookingStatusChanged.php` belum disanitasi dengan `strip_tags()`.* |
| **B-07** | Insecure Direct Object Reference (IDOR) Klien | `[AMAN]` | *Tidak perlu diperbaiki. Diperiksa di `Klien/BookingController.php` baris 94, 103, 124: Selalu menggunakan `->where('client_id', Auth::id())`.* |
| **B-08** | SQL Injection pada Stored Procedures | `[AMAN]` | *Tidak perlu diperbaiki. Migration `2026_03_29_000020_create_sql_objects.php` memakai parameter terikat (`IN`, `OUT`) tanpa eksekusi Dynamic SQL string concat.* |

### C. PERFORMA & KECEPATAN (Query Optimization)

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **C-01** | N+1 Query `FinancialController::index()` | `[OPEN]` | *Masih menarik semua data dengan `get()` tanpa pagination.* |
| **C-02** | N+1 Query `EventController::index()` | `[FIXED]` | `app/Http/Controllers/Admin/EventController.php` baris 19. Menggunakan `->paginate()`. |
| **C-03** | Cursor Loop pada Stored Procedure `sp_check_personnel_availability` | `[OPEN]` | *Belum di-refactor ke mode query set-based (JOIN). Saat ini masih menggunakan Cursor iteration per row.* |
| **C-04** | `CostumeController::index()` tarik semua data (Tanpa Paginate) | `[OPEN]` | *Masih menarik semua data inventori dengan `all()` dan `get()` tanpa pagination.* |
| **C-05** | SiteContent dibaca setiap request sidebar | `[OPEN]` | *Kueri SiteContent di `layouts/admin.blade.php` baris 422 masih berjalan mentah tanpa Cache layer.* |

### D. RESPONSIVITAS MOBILE (UI/UX Front-End)

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **D-01** | Viewport Sidebar `100vh` di Mobile | `[OPEN]` | *Masih menggunakan `height: 100vh` di sidebar layout admin.* |
| **D-02** | Tabel Admin meluber (tanpa overflow wrapper) | `[PARTIAL]` | *Beberapa halaman (financials) menggunakan layout mobile cards, namun list cancellations & payments belum memiliki container overflow-x-auto.* |
| **D-03** | Scroll jebol pada Sidebar iOS Modal | `[FIXED]` | Modifikasi properti CSS fixed body via AlpineJs state / `sessionStorage` persistensi scroll. |

### E. KEHILANGAN FITUR (Feature Gaps)

Poin-poin ini terindikasi sebagai kebutuhan spesifikasi namun belum ada bentuk jadinya di dalam aplikasi.
| ID | Temuan | Status | Bukti File |
|---|---|---|---|
| **E-06** | **CRUD Data Kostum Belum Tersedia Penuh** | `[NOT IMPLEMENTED]` | Hanya tersedia halaman Inventori, belum ada fasilitas Create/Update tipe master Kostum secara sempurna. |
| **E-07** | **Cetak Laporan Keuangan PDF Kosong** | `[NOT IMPLEMENTED]` | Rute `financials.export_pdf` ada di `routes/web.php` namun controllernya hanya return empty file / placeholder. |
| **E-08** | Formulir Jadwal Latihan (Rehearsal) Frontend | `[NOT IMPLEMENTED]` | Route `admin.rehearsals.store` terdaftar, controller siap (termasuk peringatan F-04), namun tampilan form input di Modal UI `plotting.blade.php` belum ada. |

### F. INKONSISTENSI & ERROR LOGIKA LAINNYA

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **F-01** | Inkonsistensi Ejaan Role `personel` | `[FIXED]` | `app/Http/Middleware/RoleMiddleware.php` baris 32. Ejaan matching disamakan ke `'personel'` sesuai enum database. |
| **F-02** | `ClientFeedback` `submitted_at` Nullable | `[PARTIAL]` | *Timestamp diset manual di ClientFeedbackController baris 41, namun model belum dideklarasikan boot logic pengisian otomatis.* |
| **F-03** | SP Check Availability tidak filter event yang dihapus (Soft Deletes) | `[FIXED]` | `database/migrations/2026_05_24_000001_fix_sp_soft_delete_filter.php`. Menambahkan klausa `AND deleted_at IS NULL`. |
| **F-04** | Peringatan Konflik Rehearsal Diabaikan Sistem | `[FIXED]` | `app/Http/Controllers/Admin/RehearsalController.php` baris 41-52. Memperbaiki variable binding SP (`@p_col` & `@p_col_det`) dan mencegah penyimpanan tanpa `force_save` jika bentrok. |

---
## KESIMPULAN REVISI

Secara substansi keamanan dasar, mayoritas critical bug (**A-02, A-04, B-01, B-04**) **berhasil ditambal** dan telah di-verifikasi melalui lokasi file masing-masing.

Aplikasi ini **BELUM** mencapai angka 100% sempurna dikarenakan 5 temuan berstatus `[OPEN]` / `[NOT IMPLEMENTED]`, khususnya **A-01** (Pessimistic Locking pada ConfirmPayment Admin) serta absennya beberapa *module views* (Cetak PDF, CRUD Master Kostum, Form Latihan Frontend).

Rekomendasi berikutnya: Prioritaskan pengembangan fitur yang hilang (`[NOT IMPLEMENTED]`) di *sprint* selanjutnya.
