<?php
// Salin menjadi config.local.php lalu isi.
return [
    'db_host' => 'localhost',
    'db_name' => 'asabri_db',
    'db_user' => 'asabri_app',
    'db_pass' => 'GANTI_PASSWORD_DB',
    // Buat hash dengan: php -r "echo password_hash('passwordAdmin', PASSWORD_DEFAULT);"
    'admin_user' => 'admin',
    'admin_pass_hash' => '',
];
