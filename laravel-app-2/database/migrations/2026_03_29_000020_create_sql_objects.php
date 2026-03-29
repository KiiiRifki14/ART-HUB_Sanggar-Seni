<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ═══════════════════════════════════════════════════
        // SQL FUNCTION 1: Kalkulasi Penalti Pembatalan
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_calculate_cancellation_penalty;
            CREATE FUNCTION fn_calculate_cancellation_penalty(
                p_event_date DATE,
                p_cancel_date DATE,
                p_total_price DECIMAL(15,2)
            ) RETURNS DECIMAL(15,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_days_diff INT;
                DECLARE v_penalty DECIMAL(15,2);

                SET v_days_diff = DATEDIFF(p_event_date, p_cancel_date);

                IF v_days_diff >= 14 THEN
                    SET v_penalty = p_total_price * 0.10;
                ELSEIF v_days_diff >= 7 THEN
                    SET v_penalty = p_total_price * 0.30;
                ELSEIF v_days_diff >= 3 THEN
                    SET v_penalty = p_total_price * 0.50;
                ELSE
                    SET v_penalty = p_total_price * 0.75;
                END IF;

                RETURN v_penalty;
            END
        ");

        // ═══════════════════════════════════════════════════
        // SQL FUNCTION 2: Estimasi Total Honor Personel
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_estimate_total_honor;
            CREATE FUNCTION fn_estimate_total_honor(
                p_event_id BIGINT
            ) RETURNS DECIMAL(15,2)
            READS SQL DATA
            BEGIN
                DECLARE v_total DECIMAL(15,2) DEFAULT 0;

                SELECT COALESCE(SUM(ep.fee), 0) INTO v_total
                FROM event_personnel ep
                WHERE ep.event_id = p_event_id
                AND ep.status IN ('assigned', 'confirmed');

                RETURN v_total;
            END
        ");

        // ═══════════════════════════════════════════════════
        // TRIGGER 1: Costume Rental Overdue (vendor)
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_costume_rental_overdue;
            CREATE TRIGGER trg_costume_rental_overdue
            BEFORE UPDATE ON costume_rentals
            FOR EACH ROW
            BEGIN
                IF NEW.returned_date IS NOT NULL AND NEW.returned_date > NEW.due_date THEN
                    SET NEW.status = 'overdue';
                    SET NEW.overdue_days = DATEDIFF(NEW.returned_date, NEW.due_date);
                    SET NEW.overdue_fine = NEW.overdue_days * 50000;
                ELSEIF NEW.returned_date IS NOT NULL AND NEW.returned_date <= NEW.due_date THEN
                    SET NEW.status = 'returned';
                    SET NEW.overdue_days = 0;
                    SET NEW.overdue_fine = 0;
                END IF;
            END
        ");

        // ═══════════════════════════════════════════════════
        // TRIGGER 2: Audit Trail Biaya Operasional
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_operational_cost_audit;
            CREATE TRIGGER trg_operational_cost_audit
            AFTER UPDATE ON operational_costs
            FOR EACH ROW
            BEGIN
                IF OLD.actual_amount != NEW.actual_amount THEN
                    INSERT INTO financial_audits
                        (financial_record_id, field_changed, old_value, new_value, changed_by, changed_at)
                    VALUES
                        (NEW.financial_record_id, CONCAT('operational_cost_', NEW.category),
                         OLD.actual_amount, NEW.actual_amount, NEW.updated_by, NOW());
                END IF;
            END
        ");

        // ═══════════════════════════════════════════════════
        // TRIGGER 3: Sanggar Costume Return Status
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_sanggar_costume_return;
            CREATE TRIGGER trg_sanggar_costume_return
            BEFORE UPDATE ON costume_usages
            FOR EACH ROW
            BEGIN
                IF NEW.actual_return_date IS NOT NULL
                   AND OLD.actual_return_date IS NULL THEN
                    IF NEW.status = 'damaged' OR NEW.status = 'lost' THEN
                        -- Status tetap sesuai input
                        SET NEW.status = NEW.status;
                    ELSEIF NEW.actual_return_date > NEW.expected_return_date THEN
                        SET NEW.status = 'damaged';
                    ELSE
                        SET NEW.status = 'returned';
                    END IF;
                END IF;
            END
        ");

        // ═══════════════════════════════════════════════════
        // TRIGGER 4: Sinkronisasi Condition Kostum Sanggar
        // (costume_usages → sanggar_costumes)
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_sync_costume_condition;
            CREATE TRIGGER trg_sync_costume_condition
            AFTER UPDATE ON costume_usages
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'damaged' THEN
                    UPDATE sanggar_costumes SET `condition` = 'damaged' WHERE id = NEW.costume_id;
                ELSEIF NEW.status = 'lost' THEN
                    UPDATE sanggar_costumes SET `condition` = 'maintenance' WHERE id = NEW.costume_id;
                ELSEIF NEW.status = 'returned' AND OLD.status != 'returned' THEN
                    UPDATE sanggar_costumes SET `condition` = 'good' WHERE id = NEW.costume_id;
                END IF;
            END
        ");

        // ═══════════════════════════════════════════════════
        // TRIGGER 5: Insiden → Auto Masuk Operational Costs
        // ═══════════════════════════════════════════════════
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_incident_to_cost;
            CREATE TRIGGER trg_incident_to_cost
            AFTER INSERT ON event_logs
            FOR EACH ROW
            BEGIN
                IF NEW.financial_impact IS NOT NULL AND NEW.financial_impact > 0 THEN
                    INSERT INTO operational_costs
                        (financial_record_id, category, description, estimated_amount,
                         actual_amount, updated_by, created_at, updated_at)
                    SELECT
                        fr.id, 'denda_insiden', CONCAT('[INSIDEN] ', NEW.title),
                        NEW.financial_impact, NEW.financial_impact,
                        NEW.logged_by, NOW(), NOW()
                    FROM financial_records fr
                    WHERE fr.event_id = NEW.event_id
                    LIMIT 1;

                    UPDATE financial_records fr
                    SET actual_operational_cost = (
                        SELECT COALESCE(SUM(actual_amount), 0)
                        FROM operational_costs
                        WHERE financial_record_id = fr.id
                    )
                    WHERE fr.event_id = NEW.event_id;
                END IF;
            END
        ");

        // ═══════════════════════════════════════════════════
        // STORED PROCEDURE: Collision Detection (Cursor)
        // ═══════════════════════════════════════════════════
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
                    WHERE p.is_active = TRUE;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;

                OPEN cur_personnel;

                read_loop: LOOP
                    FETCH cur_personnel INTO v_personnel_id, v_personnel_name,
                          v_specialty, v_has_day_job, v_day_job_desc, v_is_backup;
                    IF v_done THEN LEAVE read_loop; END IF;

                    SET v_conflict_type = NULL;

                    IF EXISTS (
                        SELECT 1 FROM event_personnel ep
                        JOIN events e ON ep.event_id = e.id
                        WHERE ep.personnel_id = v_personnel_id
                        AND e.event_date = p_event_date
                        AND e.event_start < p_end_time
                        AND e.event_end > p_start_time
                        AND ep.status IN ('assigned', 'confirmed')
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
        DB::unprepared('DROP FUNCTION IF EXISTS fn_calculate_cancellation_penalty');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_estimate_total_honor');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_costume_rental_overdue');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_operational_cost_audit');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sanggar_costume_return');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sync_costume_condition');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_incident_to_cost');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_personnel_availability');
    }
};
