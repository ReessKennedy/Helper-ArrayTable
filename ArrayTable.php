<?php

function arrayToTable($data, $columns) {
    if (is_string($data)) {
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data');
        }
    }
    
    if (!is_array($data) || empty($data)) {
        throw new InvalidArgumentException('Data must be a non-empty array');
    }
    if (!is_array($columns) || empty($columns)) {
        throw new InvalidArgumentException('Columns must be a non-empty array');
    }
    
    $columnWidth = 57;  // Set fixed column width
    $contentWidth = $columnWidth - 5; // Automatically calculate content width
    
    // Count rows
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";  // Prepare row count string

    $header = '| ' . implode(' | ', array_map(function($col) use ($columnWidth) {
        return str_pad($col, $columnWidth - 2);  // -2 for padding inside the separators
    }, array_keys($columns))) . ' |';
    
    $separator = '|-' . implode('-|-', array_fill(0, count($columns), str_repeat('-', $columnWidth - 2))) . '-|';

    $rows = [];
    foreach ($data as $item) {
        $row = [];
        foreach ($columns as $column => $path) {
            $value = stripEmojis(extractValue($item, $path));
            if (strlen($value) > $contentWidth) {
                $value = substr($value, 0, $contentWidth) . '...';  // Replace ellipsis with three periods
            }
            $row[] = str_pad($value, $columnWidth - 2);
        }
        $rows[] = '| ' . implode(' | ', $row) . ' |';
    }

    // Combine row count, header, separator, and rows
    $table = $rowTotal . $header . "\n" . $separator . "\n" . implode("\n", $rows);
    
    return $table;
}

function extractValue($item, $path) {
    $keys = explode('.', $path);
    $value = $item; // Start with the entire item
    foreach ($keys as $key) {
        if (!isset($value[$key])) {
            return 'N/A'; // Default value if key doesn't exist
        }
        $value = $value[$key];
    }
    return $value;
}

function stripEmojis($text) {
    $regex = '/[\x{1F600}-\x{1F64F}|\x{1F300}-\x{1F5FF}|\x{1F680}-\x{1F6FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}]/u';
    return preg_replace($regex, '', $text);
}
