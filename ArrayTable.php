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
    
    $defaultColumnWidth = 20;  // Default fixed column width

    // Count rows
    $rowCount = count($data);
    $rowTotal = "Row total: $rowCount\n\n";  // Prepare row count string

    $header = '| ' . implode(' | ', array_map(function($col) use ($columns, $defaultColumnWidth) {
        $width = is_array($col) ? $col['width'] - 2 : $defaultColumnWidth - 2;
        return str_pad(isset($col['name']) ? $col['name'] : $col, $width);
    }, array_keys($columns))) . ' |';
    
    $separator = '|-' . implode('-|-', array_map(function($col) use ($columns, $defaultColumnWidth) {
        $width = is_array($col) ? $col['width'] - 2 : $defaultColumnWidth - 2;
        return str_repeat('-', $width);
    }, array_keys($columns))) . '-|';

    $rows = [];
    foreach ($data as $item) {
        $row = [];
        foreach ($columns as $column => $details) {
            $path = is_array($details) ? $details['path'] : $details;
            $width = is_array($details) ? $details['width'] - 4 : $defaultColumnWidth - 4;
            $value = stripEmojis(extractValue($item, $path));
            if (strlen($value) > $width) {
                $value = substr($value, 0, $width) . '...';
            }
            $row[] = str_pad($value, $width);
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