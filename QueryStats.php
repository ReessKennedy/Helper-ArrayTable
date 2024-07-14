<?php

function extractTotalResults(array $responseData, string $totalResultsPath): int {
    $totalResults = $responseData;
    foreach (explode('.', $totalResultsPath) as $key) {
        if (isset($totalResults[$key])) {
            $totalResults = $totalResults[$key];
        } else {
            // If the specified path is invalid, fall back to 'total' if it exists
            return $responseData['total'] ?? 0;
        }
    }
    
    // Ensure totalResults is an integer
    return is_int($totalResults) ? $totalResults : 0;
}

function getQueryStats(array $queryData, array $responseData): array {
    // Extract the necessary information from the query data
    $limit = $queryData['limit'] ?? 0;
    $offset = $queryData['offset'] ?? 0;
    $sort = $queryData['sort'] ?? '';
    $direction = $queryData['direction'] ?? '';
    $totalResultsPath = $queryData['totalResultsPath'] ?? 'total';

    // Extract total results using the separate function
    $totalResults = extractTotalResults($responseData, $totalResultsPath);

    // Calculate current page and total pages based on limit and offset
    $currentPage = ($limit > 0) ? intval(($offset / $limit) + 1) : 1;
    $totalPages = ($limit > 0) ? intval(ceil($totalResults / $limit)) : 1;

    // Create an array with the key stats
    return [
        'Total results' => $totalResults,
        'Current page' => $currentPage,
        'Total pages' => $totalPages,
        'Sorted by' => $sort,
        'Sorted by direction' => $direction
    ];
}