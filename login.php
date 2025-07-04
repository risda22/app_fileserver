<!-- Tombol Kembali ke Portal Sekolah -->
  <div style="margin-top: 20px;">
    <a href="http://portal.smkxyz.sch.local/" class="back-button">← Kembali ke Portal Sekolah</a>
  </div>
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];

    if ($username && in_array($role, ['siswa', 'guru'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit;
    } else {
        $error = "Login gagal. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Portal Samba</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Login Portal Samba</h1>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST">
    <label>Nama:</label>
    <input type="text" name="username" required>
    <label>Role:</label>
    <select name="role" required>
      <option value="siswa">Siswa</option>
      <option value="guru">Guru</option>
    </select>
    <button type="submit">Masuk</button>
  </form>

  <!-- Tombol Kembali ke Portal Sekolah -->
  <div style="margin-top: 20px;">
    <a href="http://192.168.0.109/" class="back-button">← Kembali ke Portal Sekolah</a>
  </div>
</div>
</body>
</html>
