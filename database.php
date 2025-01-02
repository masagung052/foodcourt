<?php
// database.php

require_once 'config.php'; // Memasukkan koneksi database dari config.php

class Database {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Mengambil data tempat wisata
    public function getTempatWisata() {
        $sql = "SELECT * FROM wisata";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Menambahkan data tempat wisata
    public function tambahTempatWisata($nama, $latitude, $longitude, $deskripsi, $gambar) {
        $sql = "INSERT INTO wisata (nama, latitude, longitude, deskripsi, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $nama, $latitude, $longitude, $deskripsi, $gambar);
        return $stmt->execute();
    }

    // Mengambil data tempat wisata berdasarkan ID
    public function getTempatWisataById($id) {
        $sql = "SELECT * FROM wisata WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mengupdate data tempat wisata
    public function updateTempatWisata($id, $nama, $latitude, $longitude, $deskripsi, $gambar) {
        $sql = "UPDATE wisata SET nama = ?, latitude = ?, longitude = ?, deskripsi = ?, gambar = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $latitude, $longitude, $deskripsi, $gambar, $id);
        return $stmt->execute();
    }

    // Menghapus data tempat wisata
    public function deleteTempatWisata($id) {
        $sql = "DELETE FROM wisata WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
