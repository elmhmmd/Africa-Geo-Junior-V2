<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/User.php';

try {
    $auth = new Auth();
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        $errors = $auth->validateLogin($email, $password);
        
        if (empty($errors)) {
            header('Location: ./User_Page.php');
            exit();
        }
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Africa Géo-Junior</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4A460] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-[#DEB887] p-8 rounded-lg shadow-xl">
            <h2 class="text-3xl font-bold text-[#3D1810] mb-6 text-center">Connexion</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-[#3D1810] text-sm font-bold mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-3 py-2 border border-[#8B4513] rounded-lg focus:outline-none focus:border-[#CD853F]"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div>
                    <label for="password" class="block text-[#3D1810] text-sm font-bold mb-2">
                        Mot de passe
                    </label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-3 py-2 border border-[#8B4513] rounded-lg focus:outline-none focus:border-[#CD853F]">
                </div>

                <button type="submit" 
                        class="w-full bg-[#8B4513] text-[#FFDAB9] py-2 px-4 rounded-lg hover:bg-[#CD853F] transition duration-300">
                    Se connecter
                </button>
            </form>

            <p class="mt-4 text-center text-[#3D1810]">
                Pas encore de compte? 
                <a href="signup.php" class="text-[#8B4513] hover:underline">S'inscrire</a>
            </p>
        </div>
    </div>
</body>
</html>
