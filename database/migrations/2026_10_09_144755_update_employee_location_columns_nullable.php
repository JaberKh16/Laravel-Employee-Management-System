<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEmployeeLocationColumnsNullable extends Migration
{
    /**
     * (column) => [referenced_table, referenced_column, nullable_on_delete]
     */
    protected array $locationColumns = [
        'country_id' => ['countries', 'id'],
        'state_id' => ['states', 'id'],
        'city_id' => ['cities', 'id'],
    ];

    public function up(): void
    {
        // 1. Drop any existing FK on the location columns (by real name)
        foreach (array_keys($this->locationColumns) as $column) {
            $this->dropForeignKeyIfExists('employees', $column);
        }

        // 2. Make the columns nullable — raw SQL, no DBAL
        DB::statement("
            ALTER TABLE `employees`
                MODIFY `country_id` BIGINT UNSIGNED NULL,
                MODIFY `state_id`   BIGINT UNSIGNED NULL,
                MODIFY `city_id`    BIGINT UNSIGNED NULL
        ");

        // 3. Re-add the FKs with ON DELETE SET NULL
        foreach ($this->locationColumns as $column => [$refTable, $refColumn]) {
            $fkName = "employees_{$column}_foreign";

            // Skip if it already exists (idempotent)
            if ($this->foreignKeyExists('employees', $fkName)) {
                continue;
            }

            DB::statement("
                ALTER TABLE `employees`
                ADD CONSTRAINT `{$fkName}`
                    FOREIGN KEY (`{$column}`)
                    REFERENCES `{$refTable}`(`{$refColumn}`)
                    ON DELETE SET NULL
            ");
        }
    }

    public function down(): void
    {
        // 1. Drop FKs
        foreach (array_keys($this->locationColumns) as $column) {
            $this->dropForeignKeyIfExists('employees', $column);
        }

        // 2. Revert columns to NOT NULL (only safe if no NULLs exist)
        DB::statement("
            ALTER TABLE `employees`
                MODIFY `country_id` BIGINT UNSIGNED NOT NULL,
                MODIFY `state_id`   BIGINT UNSIGNED NOT NULL,
                MODIFY `city_id`    BIGINT UNSIGNED NOT NULL
        ");

        // 3. Restore the FKs (no cascade)
        foreach ($this->locationColumns as $column => [$refTable, $refColumn]) {
            $fkName = "employees_{$column}_foreign";

            if ($this->foreignKeyExists('employees', $fkName)) {
                continue;
            }

            DB::statement("
                ALTER TABLE `employees`
                ADD CONSTRAINT `{$fkName}`
                    FOREIGN KEY (`{$column}`)
                    REFERENCES `{$refTable}`(`{$refColumn}`)
            ");
        }
    }

    /* ═══════════════════════════════════════════════════════════
       HELPERS
       ═══════════════════════════════════════════════════════════ */

    /**
     * Drop the FK on $column if one exists — regardless of its name.
     */
    protected function dropForeignKeyIfExists(string $table, string $column): void
    {
        $fk = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
        }
    }

    /**
     * Check if a named FK exists.
     */
    protected function foreignKeyExists(string $table, string $fkName): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $fkName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
}