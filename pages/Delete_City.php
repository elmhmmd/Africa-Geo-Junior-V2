<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: Login.php');
    exit();
}

if (isset($_GET['city_id'])) {
    try {
        $city = Ville::getById($_GET['city_id']);
        if ($city) {
            $country_id = $city->getPaysId(); // Get country_id before deletion
            if ($city->delete()) {
                header('Location: admin_cities.php?country_id=' . $country_id . '&success=1');
            } else {
                header('Location: admin_cities.php?country_id=' . $country_id . '&error=Failed+to+delete+city');
            }
        } else {
            header('Location: Admin_Dashboard.php');
        }
    } catch (Exception $e) {
        header('Location: admin_cities.php?country_id=' . $country_id . '&error=' . urlencode($e->getMessage()));
    }
    exit();
}

header('Location: Admin_Dashboard.php');
exit();
