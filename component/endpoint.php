<?php

require_once __DIR__ . '/WaitlistComponent.php';

// Initialize waitlist component
$waitlist = new WaitlistComponent();

// Ensure database tables exist
$waitlist->createDatabaseTables();

// Handle the request
header("Content-Type: application/json");
$waitlist->handleRequest();

?>