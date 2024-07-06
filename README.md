<?php


function arrayToTable($data, $columns) {
    // Convert JSON string to associative array if needed
    if (is_string($data)) {
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data');
        }
    }
    
    // Validate data and columns
    if (!is_array($data) || empty($data)) {
        throw new InvalidArgumentException('Data must be a non-empty array');
    }
    if (!is_array($columns) || empty($columns)) {
        throw new InvalidArgumentException('Columns must be a non-empty array');
    }
    
    // Define the column width
    $columnWidth = 57;  // Approximate 400px width
    $contentWidth = 55; // Leave room for padding
    
    // Create the table header
    $header = '| ' . implode(' | ', array_map(function($col) use ($columnWidth) {
        return str_pad($col, $columnWidth - 2);  // -2 for padding
    }, array_keys($columns))) . ' |';
    
    $separator = '|-' . implode('-|-', array_fill(0, count($columns), str_repeat('-', $columnWidth - 2))) . '-|';

    // Create the table rows
    $rows = [];
    foreach ($data as $item) {
        $row = [];
        foreach ($columns as $column => $path) {
            $value = extractValue($item, $path);
            if (strlen($value) > $contentWidth) {
                $value = substr($value, 0, $contentWidth) . '…'; // Truncate and add an ellipsis
            }
            $row[] = str_pad($value, $columnWidth - 2);
        }
        $rows[] = '| ' . implode(' | ', $row) . ' |';
    }

    // Combine header, separator, and rows
    $table = $header . "\n" . $separator . "\n" . implode("\n", $rows);
    
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
