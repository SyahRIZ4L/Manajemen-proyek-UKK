<?php
// Simple test to check if we can navigate to admin panel
echo "Testing admin panel access...\n";

// Simulate manual login
session_start();

// You can manually visit: http://127.0.0.1:8000/login
// Use credentials: admin / admin123
echo "Visit: http://127.0.0.1:8000/login\n";
echo "Credentials: admin / admin123\n";
echo "Then visit: http://127.0.0.1:8000/admin/panel\n";
?>
