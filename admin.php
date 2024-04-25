<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        .container {
            padding-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn-group {
            display: flex;
        }
        .btn {
            margin: 0 5px;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .thead-dark {
            background-color: #343a40;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Data Laporan Konsumen</h2>
    <div class="container">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Nomor HP</th>
                    <th>Subject</th>
                    <th>Messages</th>
                    <th>Created at</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Melakukan koneksi ke database
                $koneksi = mysqli_connect("localhost", "root", "", "asabri_db");

                // Mengecek koneksi
                if (mysqli_connect_errno()) {
                    echo "Koneksi database gagal: " . mysqli_connect_error();
                    exit();
                }

                // Menyiapkan query untuk mendapatkan data dari tabel data_contact
                $query = "SELECT * FROM data_contact";

                // Menjalankan query
                $result = mysqli_query($koneksi, $query);

                // Menampilkan data ke dalam tabel
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['nomor'] . "</td>";
                        echo "<td>" . $row['subject'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td class='btn-group'>";
                        echo "<form action='edit_data.php' method='post'>";
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' class='btn btn-primary'>Edit</button>";
                        echo "</form>";
                        echo "<form action='hapus_data.php' method='post'>";
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' class='btn btn-danger'>Hapus</button>";
                        echo "</form>";
                        echo "<a href='https://api.whatsapp.com/send?phone=+62" . $row['nomor'] . "' class='btn btn-success' target='_blank'>Whatsapp <i class='fab fa-whatsapp'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    // Jika tidak ada data, tampilkan "N/A" untuk setiap kolom
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data contact.</td></tr>";
                }

                // Menutup koneksi
                mysqli_close($koneksi);
                ?>
            </tbody>
        </table>
    </div>
    
    <script>
        function editData(id) {
            var formData = new FormData();
            formData.append('id', id);

            fetch('edit_data.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert("Data berhasil diupdate");
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function hapusData(id) {
            if(confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                var formData = new FormData();
                formData.append('id', id);

                fetch('hapus_data.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert("Data berhasil dihapus");
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<!-- nrp/nip pensiun -->