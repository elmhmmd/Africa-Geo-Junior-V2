<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Country.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_country':
        if (isset($_POST['nom'], $_POST['population'], $_POST['langue'])) {
            try {
                $country = Country::create(
                    $_POST['nom'],
                    $_POST['population'],
                    $_POST['langue'],
                    1 
                );
                echo json_encode(['success' => true, 'id' => $country->getId()]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'update_country':
        if (isset($_POST['id'], $_POST['nom'], $_POST['population'], $_POST['langue'])) {
            try {
                $country = Country::getById($_POST['id']);
                if ($country) {
                    $country->setNom($_POST['nom']);
                    $country->setPopulation($_POST['population']);
                    $country->setLangue($_POST['langue']);
                    $success = $country->update();
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Country not found']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'delete_country':
        if (isset($_GET['id'])) {
            try {
                $country = Country::getById($_GET['id']);
                if ($country) {
                    // First delete all cities associated with this country
                    $cities = Ville::getByPaysId($_GET['id']);
                    foreach ($cities as $city) {
                        $city->delete();
                    }
                    // Then delete the country
                    $success = $country->delete();
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Country not found']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'add_city':
        if (isset($_POST['nom'], $_POST['type'], $_POST['description'], $_POST['country_id'])) {
            try {
                $city = Ville::create(
                    $_POST['nom'],
                    $_POST['description'],
                    $_POST['type'],
                    $_POST['country_id']
                );
                echo json_encode(['success' => true, 'id' => $city->getId()]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'update_city':
        if (isset($_POST['id'], $_POST['nom'], $_POST['type'], $_POST['description'])) {
            try {
                $city = Ville::getById($_POST['id']);
                if ($city) {
                    $city->setNom($_POST['nom']);
                    $city->setType($_POST['type']);
                    $city->setDescription($_POST['description']);
                    $success = $city->update();
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'City not found']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'delete_city':
        if (isset($_GET['id'])) {
            try {
                $city = Ville::getById($_GET['id']);
                if ($city) {
                    $success = $city->delete();
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'City not found']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
