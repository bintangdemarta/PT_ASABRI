<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "asabri_db");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data pencarian dari input
$term = $_GET['term'];

// Query untuk mencari data berdasarkan nama
$sql = "SELECT * FROM data_pencarian WHERE nama LIKE '%".$term."%'";
$result = $koneksi->query($sql);

// Jika hasil ditemukan, tampilkan hasil
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nama = $row["nama"];
        // Highlight nama yang sesuai dengan kata pencarian
        $highlighted_nama = preg_replace('/(' . $term . ')/i', '<span class="highlight">$1</span>', $nama);
        echo "<p class='result-item'>Nama: " . $highlighted_nama. " - Alamat: " . $row["alamat"]. " - Telepon: " . $row["telepon"]. "</p>";
    }
} else {
    echo "Tidak ada hasil ditemukan";
}

// Tutup koneksi
$koneksi->close();
?>
