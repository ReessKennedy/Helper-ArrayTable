<?php

function arrayToTable($data, $columns) {
    // Decode JSON string to an array if necessary
    if (is_string($data)) {
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data');
        }
    }

    // Validate input data
    if (!is_array($data) || empty($data)) {
        throw new InvalidArgumentException('Data must be a non-empty array');
    }
    if (!is_array($columns) || empty($columns)) {
        throw new InvalidArgumentException('Columns must be a non-empty array');
    }

    $columnWidth = 20;  // Set fixed column width
    $contentWidth = $columnWidth - 5; // Automatically calculate content width

    // Count rows and prepare row count string
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";

    // Prepare table header
    $header = '| ' . implode(' | ', array_map(function($col) use ($columnWidth) {
        return str_pad($col, $columnWidth - 2);  // -2 for padding inside the separators
    }, array_keys($columns))) . ' |';

    // Prepare table separator
    $separator = '|-' . implode('-|-', array_fill(0, count($columns), str_repeat('-', $columnWidth - 2))) . '-|';

    // Prepare table rows
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

    // Combine row count, header, separator, and rows into final table
    $table = $rowTotal . $header . "\n" . $separator . "\n" . implode("\n", $rows);

    return $table;
}

// Helper functions (assuming these exist in your code)
function stripEmojis($text) {
    // Implement the logic to remove emojis from the text
    return preg_replace('/[[:^print:]]/', '', $text);
}

function extractValue($item, $path) {
    // Implement the logic to extract value from item using the given path
    $keys = explode('.', $path);
    foreach ($keys as $key) {
        if (isset($item[$key])) {
            $item = $item[$key];
        } else {
            return ''; // Return empty string if path does not exist
        }
    }
    return $item;
}
