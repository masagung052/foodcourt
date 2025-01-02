<?php
require_once 'database.php';

$database = new Database();
$wisata = $database->getTempatWisata();
$locations = [];

if ($wisata->num_rows > 0) {
    while ($row = $wisata->fetch_assoc()) {
        $locations[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Geografis Foodcourt</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        /* Tambahkan style lain jika diperlukan */
    </style>
</head>
<body>

<!-- Header Section -->
<div class="header">
    <img src="images/bg1.png" alt="Header Foodcourt Semarang" class="img-fluid w-100">
</div>

<div class="container">
    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari Foodcourt..." onkeyup="searchFunction()">
    </div>

    <!-- Peta -->
    <div id="map"></div>

    <h3 class="mt-5 text-center">Daftar Foodcourt</h3>

    <div class="row" id="wisataContainer">
        <?php foreach ($locations as $location): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?php echo $location['gambar']; ?>" alt="<?php echo $location['nama']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $location['nama']; ?></h5>
                        <p class="card-text"><?php echo substr($location['deskripsi'], 0, 100); ?>...</p>
                        <button class="btn btn-info" onclick="getRoute(<?php echo $location['latitude']; ?>, <?php echo $location['longitude']; ?>)">Lihat Rute</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- JavaScript -->
<script>
    var locations = <?php echo json_encode($locations); ?>;
    var map = L.map('map').setView([-7.005375, 110.435226], 12); // Titik tengah Semarang

    // Tambahkan tile layer dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker untuk setiap lokasi
    locations.forEach(function(location) {
        var marker = L.marker([location.latitude, location.longitude]).addTo(map);
        marker.bindPopup(`<h3>${location.nama}</h3><p>${location.deskripsi}</p><img src="${location.gambar}" alt="${location.nama}" style="width:100%; max-width:300px;">`);
    });

    var routingControl; // Variabel untuk menyimpan routing control

    // Fungsi untuk menampilkan rute
    function getRoute(destLat, destLng) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLocation = [position.coords.latitude, position.coords.longitude];

                // Hapus routing control sebelumnya jika ada
                if (routingControl) {
                    map.removeControl(routingControl);
                }

                // Tambahkan rute menggunakan Leaflet Routing Machine
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(userLocation),
                        L.latLng(destLat, destLng)
                    ],
                    routeWhileDragging: true
                }).addTo(map);
            }, function() {
                alert('Geolocation gagal.');
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }
    }

    // Fungsi pencarian
    function searchFunction() {
        var input = document.getElementById('searchInput').value.toUpperCase();
        var cards = document.getElementById('wisataContainer').getElementsByClassName('col-md-4');
        
        for (var i = 0; i < cards.length; i++) {
            var card = cards[i];
            var title = card.getElementsByClassName('card-title')[0];
            if (title.innerText.toUpperCase().indexOf(input) > -1) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        }
    }
</script>

</body>
</html>
