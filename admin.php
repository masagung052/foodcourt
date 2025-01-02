<?php
// admin.php
require_once 'database.php';

$database = new Database();

// Menambahkan tempat wisata baru
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = '';

    if ($_FILES['gambar']['error'] == 0) {
        $gambar = 'images/' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }

    if ($database->tambahTempatWisata($nama, $latitude, $longitude, $deskripsi, $gambar)) {
        echo "<div class='alert alert-success'>Foodcourt berhasil ditambahkan.</div>";
        header("Location: admin.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan Foodcourt.</div>";
    }
}

// Menghapus tempat wisata
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($database->deleteTempatWisata($id)) {
        echo "<div class='alert alert-success'>Foodcourt berhasil dihapus.</div>";
        header("Location: admin.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus Foodcourt.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .form-control, .btn {
            border-radius: 0.375rem;
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .table thead th {
            background-color: #136dd7;
            color: white;
        }
        .table td {
            vertical-align: middle;
        }
        .container {
            margin-top: 30px;
        }
        .nav-pills .nav-link.active {
            background-color: #136dd7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mt-4 mb-4">Admin Panel - Kelola Foodcourt</h2>

    <!-- Nav tabs for different operations -->
    <ul class="nav nav-pills mb-4" id="adminTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="addTab" data-bs-toggle="pill" href="#add" role="tab" aria-controls="add" aria-selected="true">Tambah Foodcourt</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="manageTab" data-bs-toggle="pill" href="#manage" role="tab" aria-controls="manage" aria-selected="false">Kelola Foodcourt</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Add new place -->
        <div class="tab-pane fade show active" id="add" role="tabpanel" aria-labelledby="addTab">
            <h4>Tambah Foodcourt</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Foodcourt</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Tempat Wisata" required>
                </div>
                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude" required>
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi Tempat Wisata" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Foodcourt</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-custom" name="submit">Tambah Foodcourt</button>
            </form>
        </div>

        <!-- Manage existing places -->
        <div class="tab-pane fade" id="manage" role="tabpanel" aria-labelledby="manageTab">
            <h4>Daftar Foodcourt</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $wisata = $database->getTempatWisata();
                    while ($row = $wisata->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nama']}</td>
                                <td>" . substr($row['deskripsi'], 0, 50) . "...</td>
                                <td><img src='{$row['gambar']}' alt='Gambar Wisata' width='100'></td>
                                <td>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='admin.php?delete={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
