<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old procedure first if it exists
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_personnel_availability");

        // Recreate the procedure with `deleted_at IS NULL` condition
        DB::unprepared("
            CREATE PROCEDURE sp_check_personnel_availability(
                IN p_personnel_id INT,
                IN p_event_date DATE,
                IN p_start_time TIME,
                IN p_end_time TIME
            )
            BEGIN
                DECLARE v_conflict_count INT;
                
                -- Check for conflicting events on the same day for this personnel, ignoring soft deleted events
                SELECT COUNT(*) INTO v_conflict_count
                FROM event_personnel ep
                JOIN events e ON ep.event_id = e.id
                WHERE ep.personnel_id = p_personnel_id
                  AND e.event_date = p_event_date
                  AND e.deleted_at IS NULL
                  AND (
                      (e.event_start <= p_end_time AND e.event_end >= p_start_time)
                  );
                  
                IF v_conflict_count > 0 THEN
                    SELECT 'conflict' AS status, v_conflict_count AS conflicts;
                ELSE
                    SELECT 'available' AS status, 0 AS conflicts;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_personnel_availability");

        // Optional: Revert to previous version
        DB::unprepared("
            CREATE PROCEDURE sp_check_personnel_availability(
                IN p_personnel_id INT,
                IN p_event_date DATE,
                IN p_start_time TIME,
                IN p_end_time TIME
            )
            BEGIN
                DECLARE v_conflict_count INT;
                
                SELECT COUNT(*) INTO v_conflict_count
                FROM event_personnel ep
                JOIN events e ON ep.event_id = e.id
                WHERE ep.personnel_id = p_personnel_id
                  AND e.event_date = p_event_date
                  AND (
                      (e.event_start <= p_end_time AND e.event_end >= p_start_time)
                  );
                  
                IF v_conflict_count > 0 THEN
                    SELECT 'conflict' AS status, v_conflict_count AS conflicts;
                ELSE
                    SELECT 'available' AS status, 0 AS conflicts;
                END IF;
            END;
        ");
    }
};
