<?php 

function toTable($data, $settings) {
	echo gettoTable($data, $settings);
} 	

function gettoTable($data, $settings) {
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

    // Add counter column if the setting is enabled
    $columns = $settings['columns'];
    if (isset($settings['countCol']) && $settings['countCol'] === true) {
        $columns = array_merge(['#' => 'count'], $columns);
    }

    $defaultWidth = $settings['defaultWidth'] ?? 20;  // Default column width
    $contentWidth = $defaultWidth - 6;  // Width for content, adjusting for padding

    // Count rows and prepare row count string
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";

    // Prepare table header
    $header = '| ';
    $separator = '|';
    foreach ($columns as $column => $path) {
        list($colWidth, $cleanColumn) = getWidth($column);
        $effectiveWidth = $colWidth ?? $defaultWidth;

        // Ensure $cleanColumn is a string before calling strlen
        if (is_string($cleanColumn) && strlen($cleanColumn) > $effectiveWidth - 4) {
            $cleanColumn = substr($cleanColumn, 0, $effectiveWidth - 7) . '...';
        }

        $headerText = str_pad($cleanColumn, $effectiveWidth - 4, ' ', STR_PAD_RIGHT) . ' | ';
        $header .= $headerText;
        $separator .= str_repeat('-', $effectiveWidth - 4) . '--|';  // Match the header length
    }

    // Prepare table rows
    $rows = [];
    $count = 1;
    foreach ($data as $item) {
        $row = '| ';
        foreach ($columns as $column => $path) {
            list($colWidth, $cleanColumn) = getWidth($column);
            $effectiveWidth = $colWidth ?? $defaultWidth;
            $value = ($cleanColumn === '#') ? $count : extractValue($item, $path);
            
            // Check if the path is a function call
            if (preg_match('/^(\w+)\("(.+)"\)$/', $path, $matches)) {
                $functionName = $matches[1];
                $argumentPath = $matches[2];
                $argumentValue = extractValue($item, $argumentPath);
                
                if (function_exists($functionName)) {
                    $value = $functionName($argumentValue);
                } else {
                    $value = 'Invalid function';
                }
            }

            if ($settings['stripEmojis'] ?? true) {
                $value = stripEmojis($value);  // Assume true by default
            }

            // Ensure $value is a string before calling strlen
            if (is_string($value) && strlen($value) > $effectiveWidth - 4) {
                $value = substr($value, 0, $effectiveWidth - 7) . '...';  // Truncate data
            }

            $row .= str_pad($value, $effectiveWidth - 4, ' ', STR_PAD_RIGHT) . ' | ';
        }
        $rows[] = $row;
        $count++;
    }

    // Combine row count, header, separator, and rows into the final table
    $table = $rowTotal . $header . "\n" . $separator . "\n" . implode("\n", $rows) . "\n";

    return $table;
}

// Helper functions
function getWidth($column) {
    $parts = explode('|', $column);
    if (count($parts) == 2 && is_numeric($parts[1])) {
        return [(int)$parts[1], $parts[0]];
    }
    return [null, $column];  // Default to null width
}

function stripEmojis($text) {
    // Improved regex to strip emojis
    return preg_replace('/[[:^print:]]|[\x{1F600}-\x{1F64F}]/u', '', $text);
}

function extractValue($item, $path) {
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

// Example transformation function
function timeAgo($time) {
    $time = strtotime($time);
    $diff = time() - $time;
    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } else {
        return floor($diff / 86400) . ' days ago';
    }
}
