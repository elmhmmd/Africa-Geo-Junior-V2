<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/User.php';

$errors = [];

try {
    $auth = new Auth();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $errors = $auth->validateRegistration($username, $email, $password, $confirm_password);

        if (empty($errors)) {
            $user = $auth->register($username, $email, $password);
            if ($user instanceof User) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['is_admin'] = $user->getIsAdmin();
                $_SESSION['message'] = "Inscription réussie!";
                header('Location: login.php');
                exit();
            } else {
                $errors[] = "Erreur lors de l'inscription";
            }
        }
    }
} catch (Exception $e) {
    $errors[] = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Africa Géo-Junior</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4A460] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-[#DEB887] p-8 rounded-lg shadow-xl">
            <h2 class="text-3xl font-bold text-[#3D1810] mb-6 text-center">Inscription</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-[#3D1810] text-sm font-bold mb-2">
                        Nom d'utilisateur
                    </label>
                    <input type="text" name="username" id="username" required 
                           class="w-full px-3 py-2 border border-[#8B4513] rounded-lg focus:outline-none focus:border-[#CD853F]"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

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

                <div>
                    <label for="confirm_password" class="block text-[#3D1810] text-sm font-bold mb-2">
                        Confirmer le mot de passe
                    </label>
                    <input type="password" name="confirm_password" id="confirm_password" required 
                           class="w-full px-3 py-2 border border-[#8B4513] rounded-lg focus:outline-none focus:border-[#CD853F]">
                </div>

                <button type="submit" 
                        class="w-full bg-[#8B4513] text-[#FFDAB9] py-2 px-4 rounded-lg hover:bg-[#CD853F] transition duration-300">
                    S'inscrire
                </button>
            </form>

            <p class="mt-4 text-center text-[#3D1810]">
                Déjà un compte? 
                <a href="login.php" class="text-[#8B4513] hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>