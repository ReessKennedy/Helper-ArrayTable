<?php

// Function to parse command-line arguments
function parseArguments($argv) {
    $args = [];
    foreach ($argv as $arg) {
        if (preg_match('/^--([^=]+)=(.*)$/', $arg, $matches)) {
            $args[$matches[1]] = $matches[2];
        }
    }
    return $args;
}

// Merge default data with dynamic data
function mergeDefaults($defaults, $dynamic) {
    return array_merge($defaults, $dynamic);
}


// Parse command-line arguments
$dynamicData = parseArguments($argv);