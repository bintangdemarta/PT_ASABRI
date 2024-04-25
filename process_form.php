<?php
// Mendapatkan nilai-nilai dari form
$nama = $_POST['nama'];
$nomor = $_POST['nomor'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Melakukan koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "asabri_db");

// Mengecek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

// Menyiapkan query untuk menyimpan data ke database
$query = "INSERT INTO data_contact (nama, nomor, subject, message) VALUES ('$nama', '$nomor', '$subject', '$message')";

// Menjalankan query
if (mysqli_query($koneksi, $query)) {
    // Data berhasil disimpan ke database, arahkan kembali ke halaman utama
    header("Location: asabri.html");
    exit();
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
}

// Menutup koneksi
mysqli_close($koneksi);
?>
