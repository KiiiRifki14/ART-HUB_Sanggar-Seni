<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * BUG-7 (Pesan SP) FIX:
 * Update SP agar pesan "Kemungkinan bentrok jam kerja" menggunakan
 * jam kerja/sekolah/ngampus MASING-MASING personel dari kolom
 * day_job_start dan day_job_end di tabel personnel (bukan hardcode 09:00-17:00).
 *
 * Logika: Jika event tumpang tindih dengan jam kerja personel tersebut,
 * pesan akan menyebutkan jam yang sebenarnya (misal: "08:00-16:00").
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_personnel_availability");

        DB::unprepared("
            CREATE PROCEDURE sp_check_personnel_availability(
                IN p_event_date DATE,
                IN p_start_time TIME,
                IN p_end_time TIME,
                OUT p_available_count INT,
                OUT p_collision_count INT,
                OUT p_collision_details TEXT,
                OUT p_available_details TEXT
            )
            BEGIN
                DECLARE v_done INT DEFAULT FALSE;
                DECLARE v_personnel_id BIGINT;
                DECLARE v_personnel_name VARCHAR(255);
                DECLARE v_specialty VARCHAR(50);
                DECLARE v_has_day_job BOOLEAN;
                DECLARE v_day_job_desc VARCHAR(255);
                DECLARE v_day_job_start TIME;
                DECLARE v_day_job_end TIME;
                DECLARE v_is_backup BOOLEAN;
                DECLARE v_conflict_type VARCHAR(500);

                DECLARE v_collisions TEXT DEFAULT '';
                DECLARE v_available TEXT DEFAULT '';
                DECLARE v_col_count INT DEFAULT 0;
                DECLARE v_avail_count INT DEFAULT 0;

                -- Cursor: iterasi semua personel aktif + ambil jam kerja mereka
                DECLARE cur_personnel CURSOR FOR
                    SELECT p.id, u.name, p.specialty, p.has_day_job,
                           p.day_job_desc, p.day_job_start, p.day_job_end,
                           p.is_backup
                    FROM personnel p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.is_active = TRUE
                      AND p.deleted_at IS NULL;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;

                OPEN cur_personnel;

                read_loop: LOOP
                    FETCH cur_personnel INTO v_personnel_id, v_personnel_name,
                          v_specialty, v_has_day_job, v_day_job_desc,
                          v_day_job_start, v_day_job_end, v_is_backup;
                    IF v_done THEN LEAVE read_loop; END IF;

                    SET v_conflict_type = NULL;

                    -- 1. Cek konflik dengan event lain yang sudah terjadwal
                    IF EXISTS (
                        SELECT 1 FROM event_personnel ep
                        JOIN events e ON ep.event_id = e.id
                        WHERE ep.personnel_id = v_personnel_id
                          AND e.event_date = p_event_date
                          AND e.event_start < p_end_time
                          AND e.event_end > p_start_time
                          AND ep.status IN ('assigned', 'confirmed')
                          AND e.deleted_at IS NULL
                    ) THEN
                        SET v_conflict_type = '⚠️ Sudah terjadwal di event lain pada hari/jam yang sama';
                    END IF;

                    -- 2. Cek konflik dengan jam kerja/sekolah/ngampus personel
                    --    Menggunakan jam dari database MASING-MASING personel, bukan hardcode.
                    --    Tumpang tindih terjadi jika: event_start < day_job_end DAN event_end > day_job_start
                    IF v_has_day_job = TRUE
                       AND v_day_job_start IS NOT NULL
                       AND v_day_job_end IS NOT NULL
                       AND p_start_time < v_day_job_end
                       AND p_end_time > v_day_job_start THEN

                        SET @jam_label = CONCAT(
                            TIME_FORMAT(v_day_job_start, '%H:%i'),
                            '-',
                            TIME_FORMAT(v_day_job_end, '%H:%i')
                        );
                        SET @job_note = CONCAT(
                            '⏰ Kemungkinan bentrok ',
                            IF(v_day_job_desc IS NOT NULL AND v_day_job_desc != '',
                               CONCAT('jam ', v_day_job_desc),
                               'jam kegiatan utama'),
                            ' (',
                            @jam_label,
                            ')'
                        );

                        IF v_conflict_type IS NULL THEN
                            SET v_conflict_type = @job_note;
                        ELSE
                            SET v_conflict_type = CONCAT(v_conflict_type, ' + ', @job_note);
                        END IF;

                    -- Fallback: personel punya pekerjaan tapi jam tidak diisi
                    ELSEIF v_has_day_job = TRUE
                       AND (v_day_job_start IS NULL OR v_day_job_end IS NULL)
                       AND p_start_time < '17:00:00'
                       AND p_end_time > '09:00:00' THEN
                        IF v_conflict_type IS NULL THEN
                            SET v_conflict_type = '⏰ Kemungkinan bentrok jam kegiatan utama (jam belum diisi personel)';
                        ELSE
                            SET v_conflict_type = CONCAT(v_conflict_type, ' + ⏰ bentrok kegiatan utama');
                        END IF;
                    END IF;

                    -- 3. Cek konflik dengan sesi latihan lain
                    IF EXISTS (
                        SELECT 1 FROM rehearsals r
                        JOIN event_personnel ep ON ep.event_id = r.event_id
                        WHERE ep.personnel_id = v_personnel_id
                          AND r.rehearsal_date = p_event_date
                          AND r.start_time < p_end_time
                          AND r.end_time > p_start_time
                    ) THEN
                        IF v_conflict_type IS NULL THEN
                            SET v_conflict_type = '🔁 Sudah ada sesi latihan lain di jam yang sama';
                        ELSE
                            SET v_conflict_type = CONCAT(v_conflict_type, ' + 🔁 latihan bentrok');
                        END IF;
                    END IF;

                    -- Akumulasi hasil ke output string
                    IF v_conflict_type IS NOT NULL THEN
                        SET v_col_count = v_col_count + 1;
                        SET v_collisions = CONCAT(v_collisions,
                            v_personnel_name, ' (', v_specialty, ')',
                            ' — ', v_conflict_type,
                            IF(v_is_backup, ' [Personel Cadangan]', ''),
                            '; ');
                    ELSE
                        SET v_avail_count = v_avail_count + 1;
                        SET v_available = CONCAT(v_available,
                            v_personnel_name, ' (', v_specialty, ')',
                            IF(v_is_backup, ' [Cadangan]', ''),
                            '; ');
                    END IF;
                END LOOP;

                CLOSE cur_personnel;

                -- Set OUT params
                SET p_available_count   = v_avail_count;
                SET p_collision_count   = v_col_count;
                SET p_collision_details = v_collisions;
                SET p_available_details = v_available;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_personnel_availability");
    }
};
