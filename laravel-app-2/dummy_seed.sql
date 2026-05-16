-- ================================================================
-- ART-HUB Comprehensive Dummy Data - FIXED VERSION
-- ================================================================
SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- SKENARIO 1: PENDING - Baru masuk, belum bayar DP
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, client_notes, created_at, updated_at)
VALUES (16, 'Andini Pratiwi', '082211223344', 'jaipong', '2026-06-14', '10:00:00', '13:00:00',
        'Gedung Serbaguna Subang', 'Jl. RA Kartini No. 5, Subang',
        4500000.00, 2250000.00, 'pending', 'web',
        'Mohon disiapkan 6 penari untuk tasyakuran khitanan.',
        DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- ================================================================
-- SKENARIO 2: PENDING - Klien sudah upload bukti DP tapi belum diverifikasi admin
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_proof, client_notes, created_at, updated_at)
VALUES (14, 'Hendra Gunawan', '081388990011', 'sunda_dance', '2026-06-21', '19:00:00', '22:00:00',
        'Aula Kecamatan Dawuan', 'Jl. Raya Dawuan No. 12, Subang',
        6000000.00, 3000000.00, 'pending', 'web',
        'proofs/dummy_receipt_hendra.jpg',
        'Untuk peringatan HUT RI ke-80 tingkat kecamatan.',
        DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY));

-- ================================================================
-- SKENARIO 3: DP_PAID - Event PLANNING (baru dikonfirmasi DP, sedang plot personel)
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_receipt, dp_paid_at, created_at, updated_at)
VALUES (15, 'PT. Subang Makmur', '02601234567', 'gamelan', '2026-06-07', '08:00:00', '12:00:00',
        'Kantor Pusat PT Subang Makmur', 'Kawasan Industri Subang Blok D-5',
        8500000.00, 4250000.00, 'dp_paid', 'web',
        'TRANSFER: BCA 1234567890 - PT Subang Makmur',
        DATE_SUB(NOW(), INTERVAL 5 DAY),
        DATE_SUB(NOW(), INTERVAL 7 DAY), NOW());

SET @b3 = LAST_INSERT_ID();
INSERT INTO events (booking_id, event_code, status, event_date, event_start, event_end, venue, latitude, longitude, personnel_count, estimated_total_honor, created_at, updated_at)
VALUES (@b3, 'EVT-2026-010', 'planning', '2026-06-07', '08:00:00', '12:00:00',
        'Kantor Pusat PT Subang Makmur', -6.57012, 107.83291, 10, 1200000.00,
        DATE_SUB(NOW(), INTERVAL 5 DAY), NOW());

SET @e3 = LAST_INSERT_ID();
INSERT INTO financial_records (event_id, total_revenue, fixed_profit_pct, is_profit_overridden, fixed_profit, dp_received, total_personnel_honor, operational_budget, actual_operational_cost, net_profit, safety_buffer_pct, safety_buffer_amt, budget_warning, profit_locked, status, created_at, updated_at)
VALUES (@e3, 8500000.00, 23.53, 1, 2000000.00, 4250000.00, 1200000.00, 2250000.00, 0.00, 0.00, 10.00, 225000.00, 0, 1, 'locked', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW());

-- ================================================================
-- SKENARIO 4: DP_PAID - Event REHEARSAL (H-3, sedang latihan)
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_receipt, dp_paid_at, created_at, updated_at)
VALUES (16, 'Keluarga Besar Kosasih', '085612345678', 'mapag_panganten', '2026-05-24', '09:00:00', '12:00:00',
        'Rumah Keluarga Kosasih', 'Jl. Anggrek No. 22, Pagaden, Subang',
        4200000.00, 2100000.00, 'dp_paid', 'web',
        'CASH_OFFLINE: Diterima langsung di sanggar',
        DATE_SUB(NOW(), INTERVAL 8 DAY),
        DATE_SUB(NOW(), INTERVAL 10 DAY), NOW());

SET @b4 = LAST_INSERT_ID();
INSERT INTO events (booking_id, event_code, status, event_date, event_start, event_end, venue, latitude, longitude, personnel_count, estimated_total_honor, admin_notes, created_at, updated_at)
VALUES (@b4, 'EVT-2026-011', 'rehearsal', '2026-05-24', '09:00:00', '12:00:00',
        'Rumah Keluarga Kosasih', -6.55901, 107.81234, 8, 1050000.00,
        'Latihan dilaksanakan H-3 di Sanggar.',
        DATE_SUB(NOW(), INTERVAL 8 DAY), NOW());

SET @e4 = LAST_INSERT_ID();
INSERT INTO financial_records (event_id, total_revenue, fixed_profit_pct, is_profit_overridden, fixed_profit, dp_received, total_personnel_honor, operational_budget, actual_operational_cost, net_profit, safety_buffer_pct, safety_buffer_amt, budget_warning, profit_locked, status, created_at, updated_at)
VALUES (@e4, 4200000.00, 28.57, 1, 1200000.00, 2100000.00, 1050000.00, 900000.00, 0.00, 0.00, 10.00, 90000.00, 0, 1, 'locked', DATE_SUB(NOW(), INTERVAL 8 DAY), NOW());

INSERT INTO event_personnel (event_id, personnel_id, fee_reference_id, role_in_event, status, fee, attendance_status, created_at, updated_at)
VALUES
(@e4, 1, 1, 'penari_utama', 'confirmed', 200000.00, 'not_arrived', NOW(), NOW()),
(@e4, 3, 1, 'penari_utama', 'confirmed', 200000.00, 'not_arrived', NOW(), NOW()),
(@e4, 4, 1, 'penari_latar', 'confirmed', 150000.00, 'not_arrived', NOW(), NOW()),
(@e4, 5, 1, 'penari_latar', 'confirmed', 150000.00, 'not_arrived', NOW(), NOW()),
(@e4, 6, 2, 'pemusik', 'confirmed', 175000.00, 'not_arrived', NOW(), NOW()),
(@e4, 7, 2, 'pemusik', 'confirmed', 175000.00, 'not_arrived', NOW(), NOW());

INSERT INTO rehearsals (event_id, type, rehearsal_date, start_time, end_time, location, status, notes, created_at, updated_at)
VALUES (@e4, 'gabungan', '2026-05-21', '15:00:00', '17:30:00', 'Sanggar Cahaya Gumilang', 'scheduled', 'Gladi bersih tari dan gamelan H-3', NOW(), NOW());

-- ================================================================
-- SKENARIO 5: DP_PAID - Event ONGOING (hari ini, sedang berlangsung)
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_receipt, dp_paid_at, created_at, updated_at)
VALUES (17, 'Yayasan Al-Hidayah Subang', '081511223344', 'jaipong', CURDATE(), '08:00:00', '11:00:00',
        'Lapangan Masjid Al-Hidayah', 'Jl. KH. Ahmad Dahlan No. 8, Subang',
        3500000.00, 1750000.00, 'dp_paid', 'web',
        'CASH_OFFLINE: Diterima via Ust. Dani',
        DATE_SUB(NOW(), INTERVAL 14 DAY),
        DATE_SUB(NOW(), INTERVAL 16 DAY), NOW());

SET @b5 = LAST_INSERT_ID();
INSERT INTO events (booking_id, event_code, status, event_date, event_start, event_end, venue, latitude, longitude, personnel_count, estimated_total_honor, created_at, updated_at)
VALUES (@b5, 'EVT-2026-012', 'ongoing', CURDATE(), '08:00:00', '11:00:00',
        'Lapangan Masjid Al-Hidayah', -6.56112, 107.82500, 5, 900000.00,
        DATE_SUB(NOW(), INTERVAL 14 DAY), NOW());

SET @e5 = LAST_INSERT_ID();
INSERT INTO financial_records (event_id, total_revenue, fixed_profit_pct, is_profit_overridden, fixed_profit, dp_received, total_personnel_honor, operational_budget, actual_operational_cost, net_profit, safety_buffer_pct, safety_buffer_amt, budget_warning, profit_locked, status, created_at, updated_at)
VALUES (@e5, 3500000.00, 28.57, 1, 1000000.00, 1750000.00, 900000.00, 750000.00, 0.00, 0.00, 10.00, 75000.00, 0, 1, 'locked', DATE_SUB(NOW(), INTERVAL 14 DAY), NOW());

INSERT INTO event_personnel (event_id, personnel_id, fee_reference_id, role_in_event, status, fee, attendance_status, checked_in_at, created_at, updated_at)
VALUES
(@e5, 1, 1, 'penari_utama', 'confirmed', 200000.00, 'on_time', CONCAT(CURDATE(), ' 07:45:00'), NOW(), NOW()),
(@e5, 2, 1, 'penari_utama', 'confirmed', 200000.00, 'on_time', CONCAT(CURDATE(), ' 07:50:00'), NOW(), NOW()),
(@e5, 4, 1, 'penari_latar', 'confirmed', 150000.00, 'late', CONCAT(CURDATE(), ' 08:15:00'), NOW(), NOW()),
(@e5, 6, 2, 'pemusik', 'confirmed', 175000.00, 'on_time', CONCAT(CURDATE(), ' 07:40:00'), NOW(), NOW()),
(@e5, 7, 2, 'pemusik', 'confirmed', 175000.00, 'on_time', CONCAT(CURDATE(), ' 07:42:00'), NOW(), NOW());

-- ================================================================
-- SKENARIO 6: CANCELLED - Dibatalkan klien (ada penalti 25%)
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_receipt, dp_paid_at, created_at, updated_at)
VALUES (16, 'Rina Melinda', '087711223344', 'sunda_dance', '2026-06-05', '10:00:00', '13:00:00',
        'Balai RW 07 Cisalak', 'Jl. Cisalak Raya No. 45, Subang',
        3800000.00, 1900000.00, 'cancelled', 'web',
        'TRANSFER: Mandiri 1122334455',
        DATE_SUB(NOW(), INTERVAL 15 DAY),
        DATE_SUB(NOW(), INTERVAL 20 DAY), NOW());

SET @b6 = LAST_INSERT_ID();
INSERT INTO cancellations (booking_id, cancellation_date, days_before_event, penalty_percentage, penalty_amount, refund_amount, status, reason, digital_acknowledgement, created_at, updated_at)
VALUES (@b6, CURDATE(), 20, 25.00, 475000.00, 1425000.00, 'processed',
        'Klien membatalkan karena kondisi darurat keluarga. Penalti 25% diterapkan sesuai SOP.',
        1, DATE_SUB(NOW(), INTERVAL 2 DAY), NOW());

-- ================================================================
-- SKENARIO 7: COMPLETED - Selesai tapi BELUM LUNAS (ada piutang)
-- ================================================================
INSERT INTO bookings (client_id, client_name, client_phone, event_type, event_date, event_start, event_end, venue, venue_address, total_price, dp_amount, status, booking_source, payment_receipt, dp_paid_at, created_at, updated_at)
VALUES (15, 'Pernikahan Agus & Sari', '082299887766', 'mapag_panganten', DATE_SUB(CURDATE(), INTERVAL 3 DAY), '10:00:00', '14:00:00',
        'Gedung Paseban Subang', 'Jl. Otista No. 1, Subang',
        7500000.00, 3750000.00, 'completed', 'web',
        'TRANSFER: BNI 9988776655',
        DATE_SUB(NOW(), INTERVAL 20 DAY),
        DATE_SUB(NOW(), INTERVAL 25 DAY), NOW());

SET @b7 = LAST_INSERT_ID();
INSERT INTO events (booking_id, event_code, status, event_date, event_start, event_end, venue, latitude, longitude, personnel_count, estimated_total_honor, created_at, updated_at)
VALUES (@b7, 'EVT-2026-013', 'completed', DATE_SUB(CURDATE(), INTERVAL 3 DAY), '10:00:00', '14:00:00',
        'Gedung Paseban Subang', -6.56500, 107.83100, 6, 1600000.00,
        DATE_SUB(NOW(), INTERVAL 20 DAY), NOW());

SET @e7 = LAST_INSERT_ID();
INSERT INTO financial_records (event_id, total_revenue, fixed_profit_pct, is_profit_overridden, fixed_profit, dp_received, total_personnel_honor, operational_budget, actual_operational_cost, net_profit, safety_buffer_pct, safety_buffer_amt, budget_warning, profit_locked, status, created_at, updated_at)
VALUES (@e7, 7500000.00, 26.67, 1, 2000000.00, 3750000.00, 1600000.00, 1750000.00, 280000.00, 0.00, 10.00, 175000.00, 0, 1, 'locked', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW());

INSERT INTO event_personnel (event_id, personnel_id, fee_reference_id, role_in_event, status, fee, attendance_status, checked_in_at, created_at, updated_at)
VALUES
(@e7, 1, 1, 'penari_utama', 'confirmed', 300000.00, 'on_time', CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 09:30:00'), NOW(), NOW()),
(@e7, 3, 1, 'penari_utama', 'confirmed', 300000.00, 'on_time', CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 09:35:00'), NOW(), NOW()),
(@e7, 4, 1, 'penari_latar', 'confirmed', 250000.00, 'late', CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 10:20:00'), NOW(), NOW()),
(@e7, 6, 2, 'pemusik', 'confirmed', 275000.00, 'on_time', CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 09:25:00'), NOW(), NOW()),
(@e7, 7, 2, 'pemusik', 'confirmed', 275000.00, 'on_time', CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 09:28:00'), NOW(), NOW()),
(@e7, 8, 2, 'pemusik', 'confirmed', 200000.00, 'absent', NULL, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Dummy data inserted successfully!' AS status;
SELECT COUNT(*) AS total_bookings FROM bookings;
SELECT COUNT(*) AS total_events FROM events;
