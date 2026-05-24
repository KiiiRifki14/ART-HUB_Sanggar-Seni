<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * FIX F-03: Stored Procedure sp_check_personnel_availability
 * tidak mem-filter soft-deleted events (deleted_at IS NULL).
 * 
 * Model Event menggunakan SoftDeletes, tapi SP query langsung ke tabel
 * tanpa memeriksa deleted_at, menyebabkan event yang sudah dihapus
 * masih terhitung dalam pengecekan konflik jadwal.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_check_personnel_availability;
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
                DECLARE v_is_backup BOOLEAN;
                DECLARE v_conflict_type VARCHAR(100);

                DECLARE v_collisions TEXT DEFAULT '';
                DECLARE v_available TEXT DEFAULT '';
                DECLARE v_col_count INT DEFAULT 0;
                DECLARE v_avail_count INT DEFAULT 0;

                DECLARE cur_personnel CURSOR FOR
                    SELECT p.id, u.name, p.specialty, p.has_day_job, p.day_job_desc, p.is_backup
                    FROM personnel p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.is_active = TRUE
                      AND p.deleted_at IS NULL; -- FIX F-03: Exclude soft-deleted personnel

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;

                OPEN cur_personnel;

                read_loop: LOOP
                    FETCH cur_personnel INTO v_personnel_id, v_personnel_name,
                          v_specialty, v_has_day_job, v_day_job_desc, v_is_backup;
                    IF v_done THEN LEAVE read_loop; END IF;

                    SET v_conflict_type = NULL;

                    -- FIX F-03: Tambahkan filter e.deleted_at IS NULL
                    IF EXISTS (
                        SELECT 1 FROM event_personnel ep
                        JOIN events e ON ep.event_id = e.id
                        WHERE ep.personnel_id = v_personnel_id
                        AND e.event_date = p_event_date
                        AND e.event_start < p_end_time
                        AND e.event_end > p_start_time
                        AND ep.status IN ('assigned', 'confirmed')
                        AND e.deleted_at IS NULL  -- FIX F-03: Hanya event yang belum di-soft-delete
                    ) THEN
                        SET v_conflict_type = 'EVENT_COLLISION';
                    END IF;

                    IF v_has_day_job = TRUE
                       AND p_start_time < '17:00:00'
                       AND p_end_time > '09:00:00' THEN
                        IF v_conflict_type IS NULL THEN
                            SET v_conflict_type = 'DAY_JOB_CONFLICT';
                        ELSE
                            SET v_conflict_type = CONCAT(v_conflict_type, ',DAY_JOB_CONFLICT');
                        END IF;
                    END IF;

                    IF EXISTS (
                        SELECT 1 FROM rehearsals r
                        JOIN event_personnel ep ON ep.event_id = r.event_id
                        WHERE ep.personnel_id = v_personnel_id
                        AND r.rehearsal_date = p_event_date
                        AND r.start_time < p_end_time
                        AND r.end_time > p_start_time
                        AND r.status = 'scheduled'
                    ) THEN
                        IF v_conflict_type IS NULL THEN
                            SET v_conflict_type = 'REHEARSAL_CONFLICT';
                        ELSE
                            SET v_conflict_type = CONCAT(v_conflict_type, ',REHEARSAL_CONFLICT');
                        END IF;
                    END IF;

                    IF v_conflict_type IS NOT NULL THEN
                        SET v_col_count = v_col_count + 1;
                        SET v_collisions = CONCAT(v_collisions,
                            v_personnel_name, ' (', v_specialty, ')',
                            ' [', v_conflict_type, ']',
                            IF(v_has_day_job, CONCAT(' Kerja: ', COALESCE(v_day_job_desc, '-')), ''),
                            IF(v_is_backup, ' [CADANGAN]', ''),
                            '; ');
                    ELSE
                        SET v_avail_count = v_avail_count + 1;
                        SET v_available = CONCAT(v_available,
                            v_personnel_name, ' (', v_specialty, ')',
                            IF(v_is_backup, ' [CADANGAN]', ''),
                            '; ');
                    END IF;
                END LOOP;

                CLOSE cur_personnel;

                SET p_available_count = v_avail_count;
                SET p_collision_count = v_col_count;
                SET p_collision_details = v_collisions;
                SET p_available_details = v_available;
            END
        ");
    }

    public function down(): void
    {
        // Kembalikan ke versi sebelumnya (tanpa filter deleted_at)
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_personnel_availability");
    }
};
