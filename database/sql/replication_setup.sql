-- ════════════════════════════════════════════════════════════
-- ART-HUB: Database Replication Config (Simulasi Localhost)
-- Materi: Basis Data 2 - Database Terdistribusi
-- ════════════════════════════════════════════════════════════
-- 
-- ARSITEKTUR:
-- ┌─────────────────────┐         ┌─────────────────────┐
-- │   MASTER (Port 3306)│────────▶│   SLAVE (Port 3307) │
-- │   Laptop Pak Yat    │  binlog │   Backup Server     │
-- │   WRITE + READ      │  sync   │   READ ONLY         │
-- │   art_hub           │         │   art_hub_replica    │
-- └─────────────────────┘         └─────────────────────┘
--
-- ════════════════════════════════════════════════════════════

-- ═══ LANGKAH 1: Konfigurasi Master (my.ini / my.cnf) ═══
-- Tambahkan di bawah [mysqld]:
-- server-id = 1
-- log_bin = mysql-bin
-- binlog_do_db = art_hub
-- binlog_format = ROW

-- ═══ LANGKAH 2: Konfigurasi Slave (my.ini instance ke-2) ═══
-- Tambahkan di bawah [mysqld]:
-- server-id = 2
-- port = 3307
-- relay-log = relay-bin
-- read_only = 1

-- ═══ LANGKAH 3: Setup di Master ═══
-- Jalankan query berikut di Master (port 3306):

CREATE USER IF NOT EXISTS 'replication_user'@'%' IDENTIFIED BY 'ReplikaArtHub2026!';
GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';
FLUSH PRIVILEGES;

-- Catat output dari:
SHOW MASTER STATUS;
-- File: mysql-bin.000001
-- Position: 154 (contoh, sesuaikan dengan hasil aktual)

-- ═══ LANGKAH 4: Setup di Slave ═══
-- Jalankan query berikut di Slave (port 3307):

-- CHANGE MASTER TO
--     MASTER_HOST = '127.0.0.1',
--     MASTER_PORT = 3306,
--     MASTER_USER = 'replication_user',
--     MASTER_PASSWORD = 'ReplikaArtHub2026!',
--     MASTER_LOG_FILE = 'mysql-bin.000001',
--     MASTER_LOG_POS = 154;
-- 
-- START SLAVE;

-- ═══ LANGKAH 5: Verifikasi ═══
-- Di Slave, jalankan:
-- SHOW SLAVE STATUS\G
-- Pastikan:
--   Slave_IO_Running: Yes
--   Slave_SQL_Running: Yes

-- ═══ CATATAN UNTUK DEMO DI KELAS ═══
-- 1. Jalankan 2 instance MySQL: port 3306 (Master) dan 3307 (Slave)
-- 2. INSERT data di Master → data otomatis muncul di Slave
-- 3. UPDATE data di Master → perubahan otomatis di-replikasi
-- 4. Coba INSERT di Slave → akan DITOLAK (read_only = 1)
