<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

function listFiles($dir) {
  $files = [];
  if (is_dir($dir)) {
    foreach (scandir($dir) as $file) {
      if ($file != '.' && $file != '..') {
        $files[] = $file;
      }
    }
  }
  return $files;
}

function handleUpload($target_dir) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Pastikan target_dir ada dan dapat ditulis
    $upload_dir = realpath($target_dir);
    if (!$upload_dir || !is_writable($upload_dir)) {
      echo "<p style='color:red;'>Direktori tujuan tidak tersedia atau tidak bisa ditulis.</p>";
      return;
    }

    // Bonus: Amankan nama file
    $originalName = basename($file['name']);
    $safeName = preg_replace('/[^a-zA-Z0-9_\.\-]/', '_', $originalName);
    
    // Hindari overwrite: Tambahkan timestamp jika nama sudah ada
    $target_file = $upload_dir . '/' . $safeName;
    if (file_exists($target_file)) {
      $ext = pathinfo($safeName, PATHINFO_EXTENSION);
      $name = pathinfo($safeName, PATHINFO_FILENAME);
      $safeName = $name . '_' . time() . '.' . $ext;
      $target_file = $upload_dir . '/' . $safeName;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
      echo "<p style='color:green;'>File <strong>$safeName</strong> berhasil diunggah.</p>";
    } else {
      echo "<p style='color:red;'>Gagal mengunggah file ke <code>$target_file</code>.</p>";
    }
  }
}



//function handleUpload($target_dir) {
  //if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    //$file = $_FILES['file'];

    // Pastikan target_dir ada dan dapat ditulis
    //$upload_dir = realpath($target_dir);
    //if (!$upload_dir || !is_writable($upload_dir)) {
      //echo "<p style='color:red;'>Direktori tujuan tidak tersedia atau tidak bisa ditulis.</p>";
      //return;
    //}

    //$target_file = $upload_dir . '/' . basename($file['name']);

    //if (move_uploaded_file($file['tmp_name'], $target_file)) {
      //echo "<p style='color:green;'>File berhasil diunggah.</p>";
    //} else {
      //echo "<p style='color:red;'>Gagal mengunggah file ke $target_file.</p>";
    //}
  //}
//}


?>

<!DOCTYPE html>
<html>
<head>
  <title>Portal Samba</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Selamat datang, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h1>
  <p><a href="logout.php">Logout</a></p>

  <?php if ($role === 'siswa'): ?>
    <h2>Unduh Materi</h2>
    <ul>
      <?php foreach (listFiles('materi') as $file): ?>
        <li><a href="materi/<?= urlencode($file) ?>" target="_blank"><?= htmlspecialchars($file) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <h2>Upload Tugas</h2>
    <?php handleUpload('tugas'); ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <button type="submit">Upload</button>
    </form>

  <?php elseif ($role === 'guru'): ?>
    <h2>Upload Materi</h2>
    <?php handleUpload('materi'); ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <button type="submit">Upload</button>
    </form>

    <h2>Download Tugas</h2>
    <ul>
      <?php foreach (listFiles('tugas') as $file): ?>
        <li><a href="tugas/<?= urlencode($file) ?>" target="_blank"><?= htmlspecialchars($file) ?></a></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
</body>
</html>
