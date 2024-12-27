<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: Login.php');
    exit();
}

// Check if form was submitted with required fields
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['nom'], $_POST['type'], $_POST['description'], $_POST['country_id'])) {
    
    try {
        // Create new city
        $city = Ville::create(
            $_POST['nom'],
            $_POST['description'],
            $_POST['type'],
            $_POST['country_id']
        );

        // Redirect back to admin_cities page with success message
        header('Location: admin_cities.php?country_id=' . $_POST['country_id'] . '&success=1');
        exit();
    } catch (Exception $e) {
        // Redirect back with error
        header('Location: admin_cities.php?country_id=' . $_POST['country_id'] . '&error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If form wasn't submitted properly, redirect back
    header('Location: Admin_Dashboard.php');
    exit();
}
