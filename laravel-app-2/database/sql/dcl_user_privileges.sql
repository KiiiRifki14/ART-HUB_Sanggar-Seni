-- ════════════════════════════════════════════════════════════
-- ART-HUB: SQL DCL (Data Control Language)
-- Materi: Basis Data 2 - Keamanan & Hak Akses Database
-- ════════════════════════════════════════════════════════════

-- Jalankan script ini di MySQL setelah database art_hub sudah dibuat
-- ════════════════════════════════════════════════════════════

-- 1. ADMIN (Pak Yat) — Akses PENUH ke seluruh database
CREATE USER IF NOT EXISTS 'pakyat_admin'@'localhost' IDENTIFIED BY 'ArtHub_Admin2026!';
GRANT ALL PRIVILEGES ON art_hub.* TO 'pakyat_admin'@'localhost';

-- 2. PERSONEL — Hanya bisa LIHAT jadwal & event sendiri (READ ONLY)
CREATE USER IF NOT EXISTS 'personel_user'@'localhost' IDENTIFIED BY 'ArtHub_Personel2026!';
GRANT SELECT ON art_hub.personnel_schedules TO 'personel_user'@'localhost';
GRANT SELECT ON art_hub.events TO 'personel_user'@'localhost';
GRANT SELECT ON art_hub.event_personnel TO 'personel_user'@'localhost';
GRANT SELECT ON art_hub.rehearsals TO 'personel_user'@'localhost';
GRANT SELECT ON art_hub.personnel TO 'personel_user'@'localhost';

-- 3. KLIEN — Hanya bisa LIHAT & BUAT booking (terbatas)
CREATE USER IF NOT EXISTS 'klien_user'@'localhost' IDENTIFIED BY 'ArtHub_Klien2026!';
GRANT SELECT, INSERT ON art_hub.bookings TO 'klien_user'@'localhost';
GRANT SELECT ON art_hub.cancellations TO 'klien_user'@'localhost';
GRANT SELECT, INSERT ON art_hub.client_feedbacks TO 'klien_user'@'localhost';

-- 4. AUDITOR — Read-Only untuk semua tabel keuangan (Audit)
CREATE USER IF NOT EXISTS 'auditor'@'localhost' IDENTIFIED BY 'ArtHub_Audit2026!';
GRANT SELECT ON art_hub.financial_records TO 'auditor'@'localhost';
GRANT SELECT ON art_hub.operational_costs TO 'auditor'@'localhost';
GRANT SELECT ON art_hub.financial_audits TO 'auditor'@'localhost';
GRANT SELECT ON art_hub.event_logs TO 'auditor'@'localhost';

-- Terapkan perubahan
FLUSH PRIVILEGES;

-- ════════════════════════════════════════════════════════════
-- CONTOH REVOKE (jika ingin mencabut hak akses)
-- ════════════════════════════════════════════════════════════
-- REVOKE ALL PRIVILEGES ON art_hub.* FROM 'personel_user'@'localhost';
-- REVOKE INSERT ON art_hub.bookings FROM 'klien_user'@'localhost';
-- DROP USER 'auditor'@'localhost';
-- FLUSH PRIVILEGES;
