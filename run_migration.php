<?php
include "admin/koneksi.php";

echo "Memulai migrasi database...<br>";

// 1. Buat tabel solusi
$sql1 = "CREATE TABLE IF NOT EXISTS `tbl_solusi` (
  `id_solusi` int(11) NOT NULL AUTO_INCREMENT,
  `id_penyakit` varchar(5) NOT NULL,
  `solusi` text NOT NULL,
  PRIMARY KEY (`id_solusi`),
  KEY `id_penyakit` (`id_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($koneksi, $sql1)) {
    echo "Tabel tbl_solusi berhasil dibuat/sudah ada.<br>";
} else {
    echo "Gagal buat tabel tbl_solusi: " . mysqli_error($koneksi) . "<br>";
}

// Tambah foreign key terpisah untuk menghindari error jika sudah ada
try {
    $sql_fk = "ALTER TABLE `tbl_solusi` ADD CONSTRAINT `fk_penyakit_solusi` FOREIGN KEY (`id_penyakit`) REFERENCES `tbl_penyakit` (`id_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE;";
    mysqli_query($koneksi, $sql_fk);
} catch (mysqli_sql_exception $e) {
    // Ignore duplicate foreign key error
}

// 2. Cek apakah kolom solusi ada di tbl_penyakit
$check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM `tbl_penyakit` LIKE 'solusi'");
if (mysqli_num_rows($check_col) > 0) {
    // Pindahkan data
    $sql2 = "INSERT INTO `tbl_solusi` (`id_penyakit`, `solusi`)
             SELECT `id_penyakit`, `solusi` FROM `tbl_penyakit`
             WHERE `id_penyakit` NOT IN (SELECT `id_penyakit` FROM `tbl_solusi`);";
    if (mysqli_query($koneksi, $sql2)) {
        echo "Data solusi berhasil dipindahkan.<br>";
    } else {
        echo "Gagal pindah data solusi: " . mysqli_error($koneksi) . "<br>";
    }

    // 3. Hapus kolom solusi lama
    $sql3 = "ALTER TABLE `tbl_penyakit` DROP COLUMN `solusi`;";
    if (mysqli_query($koneksi, $sql3)) {
        echo "Kolom solusi lama berhasil dihapus.<br>";
    } else {
        echo "Gagal hapus kolom solusi: " . mysqli_error($koneksi) . "<br>";
    }
} else {
    echo "Kolom solusi sudah tidak ada di tbl_penyakit (migrasi mungkin sudah pernah berjalan).<br>";
}

echo "Migrasi selesai!";
?>
