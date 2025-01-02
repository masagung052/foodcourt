<?php
require_once 'database.php';

$database = new Database();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "<div class='alert alert-danger'>ID Foodcourt tidak ditemukan.</div>";
    exit();
}

// Ambil data tempat wisata berdasarkan ID
$wisata = $database->getTempatWisataById($id);

if (!$wisata) {
    echo "<div class='alert alert-danger'>Foodcourt tidak ditemukan.</div>";
    exit();
}

// Proses update data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = $wisata['gambar'];

    // Jika ada gambar baru yang diunggah
    if ($_FILES['gambar']['error'] == 0) {
        $gambar = 'images/' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }

    // Update data ke database
    if ($database->updateTempatWisata($id, $nama, $latitude, $longitude, $deskripsi, $gambar)) {
        echo "<div class='alert alert-success'>Foodcourt berhasil diperbarui.</div>";
        header("Location: admin.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui Foodcourt.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foodcourt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .form-control, .btn {
            border-radius: 0.375rem;
        }
        .container {
            margin-top: 30px;
        }
        .btn-custom {
            background-color: #136dd7;
            color: white;
        }
        .btn-custom:hover {
            background-color: #136dd7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mt-4 mb-4">Edit Foodcourt</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Foodcourt</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $wisata['nama']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $wisata['latitude']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $wisata['longitude']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo $wisata['deskripsi']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Foodcourt</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
            <img src="<?php echo $wisata['gambar']; ?>" alt="Gambar Wisata" class="img-fluid mt-2" width="200">
        </div>
        <button type="submit" class="btn btn-custom" name="update">Perbarui</button>
        <a href="admin.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
