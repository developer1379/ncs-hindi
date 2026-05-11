<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to fix database: " . config('database.connections.mysql.database') . "\n";

    DB::statement("ALTER TABLE stem_interactions MODIFY COLUMN user_id CHAR(36) NULL");
    echo "Success: user_id is now nullable.\n";

    DB::statement("ALTER TABLE stem_interactions MODIFY COLUMN type ENUM('like', 'download', 'view') NOT NULL");
    echo "Success: type enum updated.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
