<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: Login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $city = Ville::getById($_POST['id']);
        if ($city) {
            $city->setNom($_POST['nom']);
            $city->setType($_POST['type'] ?? '');
            $city->setDescription($_POST['description'] ?? '');
            
            if ($city->update()) {
                header('Location: admin_cities.php?country_id=' . $city->getPaysId() . '&success=1');
            } else {
                header('Location: admin_cities.php?country_id=' . $city->getPaysId() . '&error=Failed+to+update+city');
            }
        } else {
            header('Location: Admin_Dashboard.php');
        }
    } catch (Exception $e) {
        header('Location: admin_cities.php?country_id=' . $city->getPaysId() . '&error=' . urlencode($e->getMessage()));
    }
    exit();
}

header('Location: Admin_Dashboard.php');
exit();
