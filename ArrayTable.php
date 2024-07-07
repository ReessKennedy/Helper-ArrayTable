<?php 

function toTable($data, $settings) {
    if (is_string($data)) {
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data');
        }
    }

    if (!is_array($data) || empty($data)) {
        throw new InvalidArgumentException('Data must be a non-empty array');
    }

    if (!is_array(reset($data))) {
        $data = [$data];  // Handle a single associative array case
    }

    $columns = $settings['columns'] ?? array_combine(array_keys(reset($data)), array_keys(reset($data)));
    $defaultWidth = $settings['defaultWidth'] ?? 20;  // Default column width

    // Prepare headers and separators
    $header = '| ';
    $separator = '|';
    foreach ($columns as $column => $path) {
        $effectiveWidth = $defaultWidth - 2;  // Subtract 2 for padding on both sides
        $headerText = str_pad($column, $defaultWidth, ' ', STR_PAD_RIGHT) . ' | ';
        $header .= $headerText;
        $separator .= str_repeat('-', $defaultWidth) . '|';  // Make sure the separator matches the header text
    }

    // Prepare table rows
    $rows = [];
    foreach ($data as $item) {
        $row = '| ';
        foreach ($columns as $column => $path) {
            $value = extractValue($item, $path);
            if ($settings['stripEmojis'] ?? true) {
                $value = stripEmojis($value);
            }
            $value = strlen($value) > $effectiveWidth ? substr($value, 0, $effectiveWidth - 3) . '...' : $value;
            $row .= str_pad($value, $effectiveWidth, ' ', STR_PAD_RIGHT) . ' | ';
        }
        $rows[] = $row;
    }

    // Combine row count, header, separator, and rows into the final table
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";
    $table = $rowTotal . $header . "\n" . $separator . "\n" . implode("\n", $rows) . "\n";

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

