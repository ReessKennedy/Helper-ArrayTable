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

    // Use columns from settings or dynamically generate if not provided
    $columns = $settings['columns'] ?? array_combine(array_keys(reset($data)), array_keys(reset($data)));
    $defaultWidth = $settings['defaultWidth'] ?? 20;  // Default column width
    $contentWidth = $defaultWidth - 6;  // Width for content, adjusting for padding

    // Count rows and prepare row count string
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";

    // Prepare table header
    $header = '| ';
    $separator = '|-';
    foreach ($columns as $column => $path) {
        $headerText = str_pad($column, $defaultWidth - 4, ' ', STR_PAD_RIGHT) . ' | ';
        $header .= $headerText;
        $separator .= str_repeat('-', $defaultWidth - 4) . '-|';  // Match the header length
    }

    // Prepare table rows
    $rows = [];
    foreach ($data as $item) {
        $row = '| ';
        foreach ($columns as $column => $path) {
            $value = extractValue($item, $path);
            if ($settings['stripEmojis'] ?? true) {
                $value = stripEmojis($value);  // Assume true by default
            }
            if (strlen($value) > $contentWidth) {
                $value = substr($value, 0, $contentWidth - 3) . '...';  // Truncate data
            }
            $row .= str_pad($value, $defaultWidth - 4, ' ', STR_PAD_RIGHT) . ' | ';
        }
        $rows[] = $row;
    }

    // Combine row count, header, separator, and rows into the final table
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

