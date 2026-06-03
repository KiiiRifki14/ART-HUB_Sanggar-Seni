<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\DB::unprepared("
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
            ELSE
                -- Meskipun telat (actual_return_date > expected_return_date), status tetap 'returned'
                -- Denda hanya berlaku jika damaged / lost (atau untuk rental eksternal)
                SET NEW.status = 'returned';
            END IF;
        END IF;
    END
");
echo "Trigger updated successfully.";
