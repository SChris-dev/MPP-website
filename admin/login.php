<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM admin WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "Entered password: $password<br>";
        echo "Hashed in DB: " . $admin['password'] . "<br>";

        if (password_verify($password, $admin['password'])) {
            echo "✅ Password match!";
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: pegawai.php');
            exit;
        } else {
            echo "❌ Password mismatch!";
            $error = 'Invalid username or password.';
        }
    } else {
        echo "❌ Admin not found!";
        $error = 'Invalid username or password.';
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Glow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            customBg: '#171717',
            customText: '#B91C1C',
          },
          boxShadow: {
            glow: '0 0 30px #B91C1C',
          },
          keyframes: {
            colorGlow: {
              '0%':   { backgroundColor: 'rgba(0, 0, 0, 0.2)' },   // merah redup
              '20%':  { backgroundColor: 'rgba(255, 0, 51, 0.9)' },   // merah terang
              '40%': { backgroundColor: 'rgba(0, 0, 0, 0.57)' },   // merah redup
              '50%':  { backgroundColor: 'rgb(255, 0, 0)' }, // putih terang
              '60%':  { backgroundColor: 'rgb(0, 0, 0)' }, // putih terang
              '80%': { backgroundColor: 'rgba(255, 0, 0, 0.8)' },   // merah redup
              '100%':  { backgroundColor: 'rgba(0, 0, 0, 0.9)' },   // merah terang
            }
          },
          animation: {
            colorGlow: 'colorGlow 6s ease-in-out infinite',
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen flex items-center justify-center">
  <img src="../public/assets/bg_login_fix.png" alt="" 
     class="fixed top-0 left-0 w-full h-full object-cover -z-10" />


  <!-- Wrapper dengan efek glow berwarna -->
  <div class="relative w-full max-w-sm">
    <!-- Efek Glow animasi warna -->
    <div class="absolute -inset-1 rounded-2xl blur-md animate-colorGlow z-0"></div>

    <!-- Kotak Form Login -->
    <div class="relative bg-white p-8 rounded-2xl shadow-glow w-full z-10">
      <h2 class="text-2xl font-semibold text-customText text-center mb-6">Login</h2>
      <form method="POST" class="space-y-5">
        <?php if ($error): ?>
          <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Username -->
        <div class="relative">
          <label for="username" class="sr-only">Username</label>
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-customText" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z"/>
            </svg>
          </div>
          <input type="text" id="username" name="username" required
            placeholder="Masukkan username"
            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customText text-customText placeholder-gray-400"/>
        </div>

        <!-- Password -->
        <div class="relative">
          <label for="password" class="sr-only">Password</label>
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-customText" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            </svg>
          </div>
          <input type="password" id="password" name="password" required
            placeholder="Masukkan password"
            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customText text-customText placeholder-gray-400"/>
        </div>

        <!-- Tombol Login -->
        <button type="submit"
          class="group relative w-full bg-customText text-white py-2 rounded-lg border-2 border-transparent transition-all duration-300 transform hover:scale-105 hover:border-customText hover:shadow-glow overflow-hidden">
          <span class="absolute inset-0 group-hover:bg-white transition-colors duration-300 ease-in-out"></span>
          <span class="relative z-10 group-hover:text-customText transition-colors duration-300">
            Login
          </span>
        </button>
      </form>
    </div>
  </div>
</body>
</html>