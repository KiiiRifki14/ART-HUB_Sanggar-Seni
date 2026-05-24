# 🔍 LAPORAN AUDIT SISTEM MENYELURUH (VERSI TRANSPARAN)
**Aplikasi:** ART-HUB Sanggar Seni (Laravel 11)  
**Tanggal Audit Terakhir:** 24 Mei 2026  
**Fokus Utama:** Bukti Baris Kode (Line-by-Line Evidence) dan Status Terbuka.

---

## DAFTAR TEMUAN & STATUS AKTUAL

### A. BUG LOGIKA BISNIS

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **A-01** | Race Condition Konfirmasi Booking | `[OPEN]` | *Belum diimplementasi. (Penyatuan dengan DB::transaction + lockForUpdate diperlukan di masa depan pada `BookingController::confirmPayment()`)* |
| **A-02** | Race condition Klien (Double Booking) | `[FIXED]` | `app/Http/Controllers/Klien/BookingController.php` baris 65-103. Menggunakan `DB::transaction()` dan `lockForUpdate()`. |
| **A-03** | Update Price Dead Code | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 213-233. Menghapus pengecekan status ganda. |
| **A-04** | `rejectProof()` Error (user_id vs client_id) | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 98, 134, 159. `$booking->user_id` diganti ke `$booking->client_id`. |
| **A-05** | `confirmCashPayment` memakai nilai request bukan variabel profit fixed | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 429. Menggunakan binding variabel `use (&$targetProfit)`. |
| **A-06** | Query N+1 di Event Monitoring `Booking::count()` | `[FIXED]` | `app/Http/Controllers/Admin/EventController.php` baris 41-51. Diganti menjadi aggregate query `selectRaw` dengan `SUM(CASE WHEN...)`. |
| **A-07** | `CancellationController` validasi event sudah lewat | `[FIXED]` | `app/Http/Controllers/Admin/CancellationController.php` baris 53. Pengecekan `if ($event->event_date->isPast())`. |
| **A-08** | Password Default Hardcoded 'sanggar123' | `[FIXED]` | `app/Http/Controllers/Admin/PersonnelController.php` baris 45. Wajib mengirimkan password di validation request. |
| **A-09** | Register User Specialty Selalu Default | `[FIXED]` | `app/Http/Controllers/Auth/RegisteredUserController.php` baris 57. Form pendaftaran mengirim `primary_skill`. |

### B. CELAH KEAMANAN & SANITASI

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **B-01** | Mass Assignment `Booking::storeManual()` | `[FIXED]` | `app/Http/Controllers/Admin/BookingController.php` baris 494. Menggunakan `$request->only()`. |
| **B-02** | Tidak ada Rate Limiting pada `CostumeController::storeVendorApi()` | `[FIXED]` | `routes/web.php` baris 171-172. Menggunakan `->middleware('throttle:60,1')`. |
| **B-03** | Middleware Role salah eja (`personnel` vs `personel`) | `[FIXED]` | `app/Http/Middleware/RoleMiddleware.php` baris 32. Menggunakan fallback routing ejaan yang benar. |
| **B-04** | Upload Proof hanya cek file extension (bukan Mime Type asli) | `[FIXED]` | `app/Http/Controllers/Klien/BookingController.php` baris 127 & 151. Rule validasi memakai `mimetypes:` untuk mendeteksi byte signature file via `finfo`. |
| **B-05** | `CancellationController` tanpa pencatatan IP Audit | `[FIXED]` | `database/migrations/2026_05_24_000002_add_audit_to_cancellations.php` (Kolom IP/UA), dan disisipkan di `CancellationController.php` baris 81-82. |
| **B-06** | Potensi XSS pada pesan notifikasi di Topbar | `[FIXED]` | `app/Notifications/BookingStatusChanged.php` baris 48. Pesan diproses menggunakan fungsi `strip_tags()` sebelum masuk database. |
| **B-07** | Insecure Direct Object Reference (IDOR) Klien | `[AMAN]` | *Tidak perlu diperbaiki. Diperiksa di `Klien/BookingController.php` baris 94, 103, 124: Selalu menggunakan `->where('client_id', Auth::id())`.* |
| **B-08** | SQL Injection pada Stored Procedures | `[AMAN]` | *Tidak perlu diperbaiki. Migration `2026_03_29_000020_create_sql_objects.php` memakai parameter terikat (`IN`, `OUT`) tanpa eksekusi Dynamic SQL string concat.* |

### C. PERFORMA & KECEPATAN (Query Optimization)

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **C-01** | N+1 Query `FinancialController::index()` | `[FIXED]` | `app/Http/Controllers/Admin/FinancialController.php` baris 20. Diubah ke `->paginate(15)`. |
| **C-02** | N+1 Query `EventController::index()` | `[FIXED]` | `app/Http/Controllers/Admin/EventController.php` baris 19. Menggunakan `->paginate()`. |
| **C-03** | Cursor Loop pada Stored Procedure `sp_check_personnel_availability` | `[OPEN]` | *Belum di-refactor ke mode query set-based (JOIN). Saat ini masih menggunakan Cursor iteration per row.* |
| **C-04** | `CostumeController::index()` tarik semua data (Tanpa Paginate) | `[FIXED]` | `app/Http/Controllers/Admin/CostumeController.php` baris 18-21. Data Vendor Rentals & Inventory menggunakan pagination. |
| **C-05** | SiteContent dibaca setiap request sidebar | `[FIXED]` | `app/Http/Controllers/Admin/CmsController.php` terintegrasi dengan Cache layer (sebelumnya `admin.blade.php`). |

### D. RESPONSIVITAS MOBILE (UI/UX Front-End)

| ID | Temuan | Status | Bukti Perbaikan (File & Baris) |
|---|---|---|---|
| **D-01** | Potong viewport di iOS (`100vh`) | `[FIXED]` | `resources/views/layouts/admin.blade.php` baris 91. CSS diubah ke `height: 100dvh`. |
| **D-02** | Tabel Admin meluber (tanpa overflow wrapper) | `[FIXED]` | `resources/views/admin/financials/index.blade.php`, `payments/index.blade.php`, `cancellations/index.blade.php`. Penambahan utility class `overflow-x-auto` pada div luar tabel. |
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
| **F-02** | `ClientFeedback` `submitted_at` Nullable | `[FIXED]` | `app/Models/ClientFeedback.php` diatur auto fill date/time menggunakan Laravel Booting logic. |
| **F-03** | SP Check Availability tidak filter event yang dihapus (Soft Deletes) | `[FIXED]` | `database/migrations/2026_05_24_000001_fix_sp_soft_delete_filter.php`. Menambahkan klausa `AND deleted_at IS NULL`. |
| **F-04** | Peringatan Konflik Rehearsal Diabaikan Sistem | `[FIXED]` | `app/Http/Controllers/Admin/RehearsalController.php` baris 46-51. Memberikan session `conflict_warning` ke return flash apabila tidak menggunakan param `force_save`. |

---
## KESIMPULAN REVISI

Secara substansi keamanan dasar, mayoritas critical bug (**A-02, A-04, B-01, B-04**) **berhasil ditambal** dan telah di-verifikasi melalui lokasi file masing-masing.

Aplikasi ini **BELUM** mencapai angka 100% sempurna dikarenakan 5 temuan berstatus `[OPEN]` / `[NOT IMPLEMENTED]`, khususnya **A-01** (Pessimistic Locking pada ConfirmPayment Admin) serta absennya beberapa *module views* (Cetak PDF, CRUD Master Kostum, Form Latihan Frontend).

Rekomendasi berikutnya: Prioritaskan pengembangan fitur yang hilang (`[NOT IMPLEMENTED]`) di *sprint* selanjutnya.
