<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
?>
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
    <p class="text-center"><a href="login.php?logout=1">Logout</a></p>
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
                $rows = db()->query('SELECT * FROM data_contact ORDER BY created_at DESC')->fetchAll();
                if ($rows) {
                    $no = 1;
                    foreach ($rows as $row) {
                        $wa = preg_replace('/^0/', '', preg_replace('/\D/', '', $row['nomor']));
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . e($row['nama']) . "</td>";
                        echo "<td>" . e($row['nomor']) . "</td>";
                        echo "<td>" . e($row['subject']) . "</td>";
                        echo "<td style='white-space:pre-line;text-align:left'>" . e($row['message']) . "</td>";
                        echo "<td>" . e($row['created_at']) . "</td>";
                        echo "<td class='btn-group'>";
                        echo "<a href='edit_data.php?id=" . (int) $row['id'] . "' class='btn btn-primary'>Edit</a>";
                        echo "<form action='hapus_data.php' method='post' onsubmit=\"return confirm('Hapus data ini?')\">";
                        echo "<input type='hidden' name='id' value='" . (int) $row['id'] . "'>";
                        echo "<input type='hidden' name='csrf' value='" . e(csrf_token()) . "'>";
                        echo "<button type='submit' class='btn btn-danger'>Hapus</button>";
                        echo "</form>";
                        echo "<a href='https://api.whatsapp.com/send?phone=62" . e($wa) . "' class='btn btn-success' target='_blank' rel='noopener'>Whatsapp <i class='fab fa-whatsapp'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data contact.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
