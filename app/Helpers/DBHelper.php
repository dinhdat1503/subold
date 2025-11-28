<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;


class DBHelper
{
    public static function bulkUpdate(string $table, array $data, string $key): void
    {
        if (empty($data) || !isset($data[0][$key])) {
            return;
        }
        $allColumns = array_values(array_unique(
            array_filter(
                array_merge(...array_map('array_keys', $data)),
                fn($col) => $col !== $key
            )
        ));
        if (empty($allColumns)) {
            return;
        }
        $colCount = count($allColumns);
        $maxPlaceholders = 60000;
        $maxRows = (int) floor($maxPlaceholders / max(1, $colCount * 2)); // 2 placeholder/row/col (WHEN + THEN)
        if (count($data) > $maxRows) {
            foreach (array_chunk($data, $maxRows) as $chunk) {
                self::bulkUpdate($table, $chunk, $key);
            }
            return;
        }
        $sqlParts = [];
        $bindings = [];
        foreach ($allColumns as $col) {
            $caseSql = "CASE";
            foreach ($data as $row) {
                $caseSql .= " WHEN `$key` = ? THEN ?";
                $bindings[] = $row[$key];
                $bindings[] = $row[$col] ?? null;
            }
            $caseSql .= " ELSE `$col` END";
            $sqlParts[] = "`$col` = $caseSql";
        }
        $wherePlaceholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "UPDATE `$table` SET " . implode(', ', $sqlParts) . " WHERE `$key` IN ($wherePlaceholders)";
        foreach ($data as $row) {
            $bindings[] = $row[$key];
        }
        DB::statement($sql, $bindings);
    }
    public static function bulkUpdateJoin(string $table, array $data, string $key)
    {
        if (empty($data))
            return;
        if (!isset($data[0]) || !is_array($data[0]) || empty($data[0]))
            return;
        $columns = array_keys($data[0]);
        if (!in_array($key, $columns)) {
            return;
        }
        $tmpName = 'tmp_update_' . uniqid();
        $colsSql = collect($columns)->map(fn($col) => "`$col` TEXT COLLATE utf8mb4_unicode_ci")->implode(', ');
        DB::statement("CREATE TEMPORARY TABLE `$tmpName` ($colsSql) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $colCount = count($columns);
        $maxPlaceholders = 60000;
        $maxRows = floor($maxPlaceholders / max(1, $colCount));
        foreach (array_chunk($data, $maxRows) as $chunk) {
            DB::table($tmpName)->insert($chunk);
        }
        $updateCols = collect($columns)
            ->reject(fn($col) => $col === $key)
            ->map(fn(string $col) => "`$table`.`$col` = `$tmpName`.`$col`")
            ->implode(', ');
        if (empty($updateCols)) {
            return;
        }
        $sql = "UPDATE `$table`
                JOIN `$tmpName` ON `$table`.`$key` = `$tmpName`.`$key`
                SET $updateCols";
        DB::statement($sql);
    }
    public static function multiInsert(string $table, array $data): int
    {
        if (empty($data)) {
            return 0;
        }
        $allColumns = array_values(array_unique(array_merge(...array_map('array_keys', $data))));
        $normalized = [];
        foreach ($data as $row) {
            $record = [];
            foreach ($allColumns as $col) {
                $record[$col] = array_key_exists($col, $row) ? $row[$col] : null;
            }
            $normalized[] = $record;
        }
        $colCount = count($allColumns);
        $maxPlaceholders = 60000;
        $maxRows = (int) floor($maxPlaceholders / max(1, $colCount));
        if ($maxRows < 1)
            $maxRows = 1;
        $inserted = 0;
        foreach (array_chunk($normalized, $maxRows) as $chunk) {
            DB::table($table)->insert($chunk);
            $inserted += count($chunk);
        }
        return $inserted;
    }
}
