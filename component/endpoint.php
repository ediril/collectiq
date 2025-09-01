<?php

require_once __DIR__ . '/WaitlistComponent.php';

// Check if banalytiq exists and include it
$banalytiqPath = __DIR__ . '/../../banalytiq/banalytiq.php';
if (file_exists($banalytiqPath)) {
    require_once $banalytiqPath;
    record_visit();
}

// Initialize waitlist component
$waitlist = new WaitlistComponent();

// Ensure database tables exist
$waitlist->createDatabaseTables();

// Handle the request
header("Content-Type: application/json");
$waitlist->handleRequest();

?>