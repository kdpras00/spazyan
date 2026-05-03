<?php
session_start();
if (!isset($_SESSION['status'])) { header("location:login.php"); exit; }
include "admin/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa - SiPakar Motor</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f0f2f8; font-family: 'Nunito', sans-serif; padding-bottom: 120px; }
        .main-container { max-width: 900px; margin: auto; }

        /* Navbar */
        .navbar { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }

        /* Category Cards */
        .category-card { cursor: pointer; transition: all 0.25s ease; border: 2px solid #e3e6f0; border-radius: 12px; background: #fff; }
        .category-card:hover { transform: translateY(-6px); border-color: #4e73df; box-shadow: 0 8px 25px rgba(78,115,223,0.2); }
        .category-card .card-body { padding: 2.5rem 1rem; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .wizard-step { display: none; animation: fadeIn 0.4s ease; }
        .wizard-step.active { display: block; }

        /* Gejala Grid */
        .gejala-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.6rem 1.5rem; }
        @media (max-width: 768px) { .gejala-grid { grid-template-columns: 1fr; } }
        .form-check-label { font-size: 0.95rem; padding-left: 0.4rem; cursor: pointer; }
        .form-check-input { width: 1.2em; height: 1.2em; margin-top: 0.15em; cursor: pointer; }
        .form-check-input:checked { background-color: #4e73df; border-color: #4e73df; }

        /* Floating button */
        .floating-button-container { position: fixed; bottom: 0; left: 0; width: 100%; background: linear-gradient(to top, rgba(255,255,255,1) 70%, rgba(255,255,255,0)); padding: 12px 0 18px; z-index: 100; }
        #submit-diagnosa { 
            font-size: 1.05rem; 
            font-weight: 700; 
            border-radius: 50px; 
            padding: 0.65rem 2rem; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        #submit-diagnosa i, #submit-diagnosa svg { margin-right: 12px !important; }
        #submit-diagnosa:disabled { opacity: 0.6 !important; cursor: not-allowed; }
        #gejala-counter { background: rgba(255,255,255,0.3); border-radius: 50px; padding: 2px 10px; font-size: 0.9rem; margin-left: 12px; }

        /* Hasil Diagnosa */
        .result-card { border: none; border-radius: 12px; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; }
        .result-card .problem-header { padding: 1rem 1.5rem; font-weight: 700; font-size: 1.05rem; }
        .result-card .solution-body { padding: 1.25rem 1.5rem; background: #fff; }

        /* Confidence Bar */
        .confidence-wrap { margin: 0.5rem 0 1rem; }
        .confidence-label { font-size: 0.85rem; color: #6c757d; margin-bottom: 4px; display: flex; justify-content: space-between; }
        .progress { height: 10px; border-radius: 50px; background: #e9ecef; }
        .progress-bar { border-radius: 50px; transition: width 1s ease; }

        /* Badge gejala */
        .gejala-badge { display: inline-block; background: #eef2ff; color: #4e73df; border: 1px solid #c7d2fe; border-radius: 6px; font-size: 0.78rem; padding: 2px 8px; margin: 2px 3px; }
        .gejala-badge.missed { background: #fff5f5; color: #e53e3e; border-color: #fed7d7; }

        /* Alert no result */
        .no-result-card { border-radius: 12px; border: 2px dashed #ffc107; background: #fffbeb; padding: 2rem; text-align: center; }

        /* Rank badge */
        .rank-1 .problem-header { background: linear-gradient(135deg, #d32f2f, #f44336); color: #fff; }
        .rank-2 .problem-header { background: linear-gradient(135deg, #e65100, #ff9800); color: #fff; }
        .rank-other .problem-header { background: linear-gradient(135deg, #1565c0, #42a5f5); color: #fff; }

        /* Section header hasil */
        .hasil-header { background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .icon-gap { margin-right: 12px !important; }
        i.icon-gap svg, svg.icon-gap { margin-right: 12px !important; }
        .logo-text {
            font-family: 'BaronNeueRegular', sans-serif;
            line-height: 0.8;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .navbar .dropdown-menu { 
            min-width: 300px !important; 
            right: -50px !important; 
            left: auto !important; 
        }
        .dropdown-header { 
            white-space: nowrap !important; 
            overflow: visible !important;
            font-size: 0.9rem !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-brand d-flex align-items-center" style="cursor: default;">
                <img src="img/iconpakarmotor.png" alt="Logo" width="70" height="70" class="mr-2">
                <div class="logo-text">
                    <div style="font-size: 1.8rem;">SIPAKAR</div>
                    <div style="font-size: 1.8rem;">MOTOR</div>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
                    <?php if(isset($_SESSION['status'])): ?>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="diagnosa.php">Diagnosa</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="riwayat.php">Riwayat</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['status'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" style="min-width: 200px;">
                            <h6 class="dropdown-header">Halo, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>!</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5 main-container">
    <?php
    // ============================================================
    // PROSES DIAGNOSA (POST)
    // ============================================================
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $gejala_dipilih = isset($_POST['gejala']) ? $_POST['gejala'] : [];

        if (count($gejala_dipilih) < 1) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Pilih minimal satu gejala untuk memulai diagnosa.',
                    confirmButtonColor: '#4e73df'
                }).then(() => {
                    window.location.href = 'diagnosa.php';
                });
            </script>";
            exit;
        } else {

            // Ambil semua aturan
            $result_aturan = mysqli_query($koneksi, "SELECT id_penyakit, id_gejala FROM tbl_basis_aturan");
            $aturan = [];
            while ($row = mysqli_fetch_assoc($result_aturan)) {
                $aturan[$row['id_penyakit']][] = $row['id_gejala'];
            }

            // Ambil nama gejala untuk tampilan
            $result_gejala_nama = mysqli_query($koneksi, "SELECT id_gejala, nama_gejala FROM tbl_gejala");
            $nama_gejala = [];
            while ($g = mysqli_fetch_assoc($result_gejala_nama)) {
                $nama_gejala[$g['id_gejala']] = $g['nama_gejala'];
            }

            // ============================================================
            // FORWARD CHAINING (MURNI)
            // Rule aktif HANYA jika SEMUA gejala dalam aturan terpenuhi.
            // IF gejala_1 AND gejala_2 AND ... AND gejala_n THEN kerusakan
            // ============================================================
            $hasil_diagnosa = []; // Terdiagnosa: rule terpenuhi 100%
            $hampir_cocok   = []; // Info saja: 50-99% (bukan diagnosis resmi)

            foreach ($aturan as $id_penyakit => $gejala_aturan) {
                $cocok      = array_intersect($gejala_aturan, $gejala_dipilih);
                $jml_cocok  = count($cocok);
                $jml_aturan = count($gejala_aturan);
                $persentase = round(($jml_cocok / $jml_aturan) * 100, 2);

                if ($jml_cocok === $jml_aturan) {
                    // RULE AKTIF: semua kondisi terpenuhi → kerusakan terdiagnosa
                    $hasil_diagnosa[] = [
                        'id_penyakit' => $id_penyakit,
                        'jml_cocok'   => $jml_cocok,
                        'jml_aturan'  => $jml_aturan,
                        'cocok'       => array_values($cocok),
                    ];
                } elseif ($persentase >= 50) {
                    // Hampir cocok: info tambahan, bukan diagnosis
                    $hampir_cocok[] = [
                        'id_penyakit' => $id_penyakit,
                        'persentase'  => $persentase,
                        'jml_cocok'   => $jml_cocok,
                        'jml_aturan'  => $jml_aturan,
                        'cocok'       => array_values($cocok),
                        'tidak_cocok' => array_values(array_diff($gejala_aturan, $gejala_dipilih)),
                    ];
                }
            }

            usort($hampir_cocok, fn($a, $b) => $b['persentase'] <=> $a['persentase']);

            // Simpan ke DB & ambil data penyakit
            $gejala_string = implode(",", $gejala_dipilih);
            $user_id       = $_SESSION['user_id'];

            // Fetch semua penyakit (diagnosa + hampir cocok)
            $all_ids = array_unique(array_merge(
                array_column($hasil_diagnosa, 'id_penyakit'),
                array_column($hampir_cocok, 'id_penyakit')
            ));
            $penyakit_data = [];
            if (!empty($all_ids)) {
                $in_str = "'" . implode("','", $all_ids) . "'";
                $res_p  = mysqli_query($koneksi, "SELECT * FROM tbl_penyakit WHERE id_penyakit IN ($in_str)");
                while ($p = mysqli_fetch_assoc($res_p)) {
                    $penyakit_data[$p['id_penyakit']] = $p;
                }
            }

            // Simpan hanya yang terdiagnosa penuh (rule aktif)
            foreach ($hasil_diagnosa as $h) {
                $id_p  = mysqli_real_escape_string($koneksi, $h['id_penyakit']);
                $g_esc = mysqli_real_escape_string($koneksi, $gejala_string);
                $chk   = mysqli_query($koneksi, "SHOW COLUMNS FROM hasil_diagnosa LIKE 'persentase'");
                if (mysqli_num_rows($chk) > 0) {
                    mysqli_query($koneksi, "INSERT INTO hasil_diagnosa (id_pas, id_penyakit, persentase, gejala_terpilih, tanggal)
                        VALUES ('$user_id', '$id_p', '100.00', '$g_esc', NOW())");
                } else {
                    mysqli_query($koneksi, "INSERT INTO hasil_diagnosa (id_pas, id_penyakit, gejala_terpilih, tanggal)
                        VALUES ('$user_id', '$id_p', '$g_esc', NOW())");
                }
            }

            // ============================================================
            // TAMPIL HASIL
            // ============================================================
            echo "<div class='hasil-header'>";
            echo "  <h4 class='mb-1 d-flex align-items-center'><i class='fa fa-motorcycle me-2'></i> Hasil Diagnosa Forward Chaining</h4>";
            echo "  <p class='mb-0 small'>Gejala dipilih: <strong>" . count($gejala_dipilih) . " gejala</strong> &nbsp;|&nbsp; Metode: <strong>Forward Chaining</strong> &nbsp;|&nbsp; Rule aktif jika <strong>semua kondisi terpenuhi</strong></p>";
            echo "</div>";

            // --- Hasil Utama: TERDIAGNOSA (Pure Forward Chaining) ---
            if (!empty($hasil_diagnosa)) {
                echo "<p class='text-muted mb-3'>Ditemukan <strong>" . count($hasil_diagnosa) . " kerusakan</strong> yang terdiagnosa. Seluruh kondisi rule Forward Chaining terpenuhi:</p>";

                foreach ($hasil_diagnosa as $h) {
                    $penyakit = $penyakit_data[$h['id_penyakit']] ?? null;
                    if (!$penyakit) continue;

                    echo "<div class='result-card rank-1'>";
                    echo "  <div class='problem-header d-flex justify-content-between align-items-center'>";
                    echo "    <span class='d-flex align-items-center'><i class='fa fa-exclamation-triangle icon-gap'></i> " . htmlspecialchars(strtoupper($penyakit['nama_penyakit'])) . "</span>";
                    echo "    <span class='badge badge-light font-weight-bold' style='font-size:0.85rem;padding:5px 12px;border-radius:50px;color:#155724;'><i class='fa fa-check-circle mr-1'></i>TERDIAGNOSA</span>";
                    echo "  </div>";
                    echo "  <div class='solution-body'>";

                    echo "  <p class='mb-1 small text-muted'><i class='fa fa-check-circle text-success mr-1'></i><strong>Gejala yang memenuhi rule (" . $h['jml_cocok'] . "/" . $h['jml_aturan'] . "):</strong></p>";
                    foreach ($h['cocok'] as $gid) {
                        echo "<span class='gejala-badge'><i class='fa fa-check' style='font-size:0.7rem;margin-right:3px;'></i>" . htmlspecialchars($nama_gejala[$gid] ?? $gid) . "</span>";
                    }

                    echo "  <hr class='my-3'>";
                    echo "  <h6 class='text-success font-weight-bold d-flex align-items-center'><i class='fa fa-tools icon-gap'></i> SOLUSI PERBAIKAN</h6>";
                    echo "  <p class='mb-0'>" . nl2br(htmlspecialchars($penyakit['solusi'])) . "</p>";
                    echo "  </div>";
                    echo "</div>";
                }

            } else {
                echo "<div class='no-result-card'>";
                echo "  <i class='fa fa-search fa-3x text-warning mb-3'></i>";
                echo "  <h5 class='font-weight-bold'>Tidak Ada Kerusakan yang Terdiagnosa</h5>";
                echo "  <p class='text-muted mb-0'>Tidak ada rule Forward Chaining yang seluruh gejalanya terpenuhi. Pastikan semua gejala yang dialami sudah dipilih.</p>";
                echo "</div>";
            }

            // --- Info Tambahan: Hampir Cocok (bukan diagnosis resmi) ---
            if (!empty($hampir_cocok)) {
                echo "<div class='card border-0 shadow-sm mt-4' style='border-radius:12px;overflow:hidden;'>";
                echo "  <div class='card-header' style='background:#f8f9fc;border-bottom:1px solid #e3e6f0;padding:1rem 1.25rem;'>";
                echo "    <h6 class='mb-0 font-weight-bold text-secondary d-flex align-items-center'><i class='fa fa-info-circle icon-gap'></i> Kemungkinan Lain (Gejala Belum Lengkap)</h6>";
                echo "    <small class='text-muted'>Kerusakan berikut <strong>belum terdiagnosa</strong> karena ada gejala dalam rule yang belum dipilih. Lengkapi gejala jika memang dialami.</small>";
                echo "  </div>";
                echo "  <div class='card-body p-0'>";

                foreach ($hampir_cocok as $h) {
                    $penyakit = $penyakit_data[$h['id_penyakit']] ?? null;
                    if (!$penyakit) continue;
                    $pct = $h['persentase'];

                    echo "<div class='p-3 border-bottom'>";
                    echo "  <div class='d-flex justify-content-between align-items-center mb-2'>";
                    echo "    <strong class='text-secondary'>" . htmlspecialchars($penyakit['nama_penyakit']) . "</strong>";
                    echo "    <span class='badge badge-secondary' style='border-radius:50px;padding:4px 10px;'>" . $h['jml_cocok'] . "/" . $h['jml_aturan'] . " gejala cocok</span>";
                    echo "  </div>";
                    echo "  <div class='progress mb-2' style='height:6px;border-radius:50px;'><div class='progress-bar bg-secondary' style='width:{$pct}%'></div></div>";

                    if (!empty($h['tidak_cocok'])) {
                        echo "  <p class='mb-1 d-flex align-items-center' style='font-size:0.8rem;color:#c0392b;'><i class='fa fa-times-circle icon-gap'></i><strong>Gejala yang belum dipilih:</strong></p>";
                        foreach ($h['tidak_cocok'] as $gid) {
                            echo "<span class='gejala-badge missed'>" . htmlspecialchars($nama_gejala[$gid] ?? $gid) . "</span>";
                        }
                    }
                    echo "</div>";
                }

                echo "  </div>";
                echo "</div>";
            }

            echo "<div class='mt-4'>";
            echo "  <a href='diagnosa.php' class='btn btn-primary btn-lg d-inline-flex align-items-center'><i class='fa fa-sync-alt icon-gap'></i> Diagnosa Ulang</a>";
            echo "  <a href='riwayat.php' class='btn btn-outline-secondary btn-lg ml-2 d-inline-flex align-items-center'><i class='fa fa-history icon-gap'></i> Lihat Riwayat</a>";
            echo "</div>";
        }

    } else {
    // ============================================================
    // TAMPILAN AWAL (WIZARD KATEGORI)
    // ============================================================
        $sql_gejala    = "SELECT * FROM tbl_gejala ORDER BY id_gejala ASC";
        $result_gejala = mysqli_query($koneksi, $sql_gejala);

        $check_kategori = mysqli_query($koneksi, "SHOW COLUMNS FROM tbl_gejala LIKE 'kategori'");
        $has_kategori   = mysqli_num_rows($check_kategori) > 0;

        $gejala_per_kategori = ['Kelistrikan' => [], 'Mesin' => [], 'Bahan Bakar' => [], 'CVT' => []];

        if (!$has_kategori) {
            $kategori_map = [
                'Kelistrikan' => ['G01','G02','G03','G04','G06','G18','G19','G29','G37'],
                'Mesin'       => ['G05','G08','G09','G10','G20','G21','G22','G23','G24','G27','G28'],
                'Bahan Bakar' => ['G07','G11','G12','G13','G14','G15','G16','G17','G25','G26','G35','G36'],
                'CVT'         => ['G30','G31','G32','G33','G34'],
            ];
            while ($row = mysqli_fetch_assoc($result_gejala)) {
                foreach ($kategori_map as $kat => $list) {
                    if (in_array($row['id_gejala'], $list)) $gejala_per_kategori[$kat][] = $row;
                }
            }
        } else {
            while ($row = mysqli_fetch_assoc($result_gejala)) {
                $kat = $row['kategori'];
                if (!isset($gejala_per_kategori[$kat])) $gejala_per_kategori[$kat] = [];
                $gejala_per_kategori[$kat][] = $row;
            }
        }

        function render_gejala_list($gejala_array) {
            echo '<div class="gejala-grid">';
            foreach ($gejala_array as $g) {
                $id  = htmlspecialchars($g['id_gejala']);
                $nm  = htmlspecialchars($g['nama_gejala']);
                echo "<div class='form-check'>";
                echo "<input class='form-check-input' type='checkbox' name='gejala[]' value='{$id}' id='{$id}'>";
                echo "<label class='form-check-label' for='{$id}'>{$nm}</label>";
                echo "</div>";
            }
            echo '</div>';
        }
    ?>

        <!-- STEP 1: Pilih Kategori -->
        <div id="step-1" class="wizard-step active">
            <div class="text-center mb-5">
                <h3><i class="fa fa-motorcycle text-primary"></i> Area Mana Yang Bermasalah?</h3>
                <p class="text-muted">Pilih area kendala pada motor Anda untuk menyaring daftar gejala agar lebih mudah.</p>
            </div>
            <div class="row">
                <?php
                $icons = ['Kelistrikan' => 'fa-bolt', 'Mesin' => 'fa-cogs', 'Bahan Bakar' => 'fa-gas-pump', 'CVT' => 'fa-sync'];
                foreach ($gejala_per_kategori as $kategori => $gejala_list):
                    $icon = $icons[$kategori] ?? 'fa-cogs';
                    $katId = str_replace(' ', '-', $kategori);
                ?>
                <div class="col-md-6 mb-4">
                    <div class="category-card h-100" onclick="showStep2('<?= $katId ?>', '<?= htmlspecialchars($kategori) ?>')">
                        <div class="card-body text-center">
                            <i class="fa <?= $icon ?> fa-3x text-primary mb-3"></i>
                            <h4 class="font-weight-bold text-dark"><?= htmlspecialchars($kategori) ?></h4>
                            <p class="text-muted mb-0">Terdapat <?= count($gejala_list) ?> kemungkinan gejala</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-2">
                <button type="button" class="btn btn-outline-primary" onclick="showAll()">Atau Tampilkan Semua Gejala Sekaligus</button>
            </div>
        </div>

        <!-- STEP 2: Pilih Gejala -->
        <div id="step-2" class="wizard-step">
            <div class="text-center mb-4">
                <h3 id="kategori-title" class="d-flex align-items-center justify-content-center"><i class="fa fa-list text-primary icon-gap"></i> Pilih Gejala Yang Dialami</h3>
                <p class="text-muted" id="kategori-subtitle">Centang semua gejala yang sesuai, lalu tekan tombol Mulai Diagnosa di bawah.</p>
            </div>
            <div class="mb-3 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" onclick="showStep1()"><i class="fa fa-arrow-left mr-1"></i> Kembali Pilih Area</button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="showAll()"><i class="fa fa-eye mr-1"></i> Tampilkan Semua</button>
            </div>

            <form action="diagnosa.php" method="post" id="diagnosa-form">
                <?php foreach ($gejala_per_kategori as $kategori => $gejala_list): ?>
                <div class="kategori-group" id="group-<?= str_replace(' ', '-', $kategori) ?>">
                    <div class="card shadow-sm mb-4" style="border-radius:12px;border:none;">
                        <div class="card-header bg-white py-3" style="border-radius:12px 12px 0 0;">
                            <h6 class="m-0 font-weight-bold text-primary d-flex align-items-center"><i class="fa fa-check-square icon-gap"></i> Gejala Area <?= htmlspecialchars($kategori) ?></h6>
                        </div>
                        <div class="card-body"><?php render_gejala_list($gejala_list); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </form>
        </div>
    <?php } ?>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] != "POST"): ?>
    <div class="floating-button-container">
        <div class="container main-container">
            <button type="button" id="submit-diagnosa" class="btn btn-success btn-lg w-100">
                <i class="fa fa-search"></i>
                <span>Mulai Diagnosa</span>
                <span id="gejala-counter">0</span>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
    function showStep2(kategoriId, kategoriName) {
        document.getElementById('step-1').classList.remove('active');
        document.getElementById('step-2').classList.add('active');
        document.querySelectorAll('.kategori-group').forEach(el => el.style.display = 'none');
        const group = document.getElementById('group-' + kategoriId);
        if (group) group.style.display = 'block';
        document.getElementById('kategori-title').innerHTML = '<i class="fa fa-list text-primary icon-gap"></i> Gejala: ' + kategoriName;
        document.getElementById('kategori-subtitle').innerText = 'Centang semua gejala yang dialami, lalu tekan Mulai Diagnosa.';
    }

    function showStep1() {
        document.getElementById('step-2').classList.remove('active');
        document.getElementById('step-1').classList.add('active');
    }

    function showAll() {
        document.getElementById('step-1').classList.remove('active');
        document.getElementById('step-2').classList.add('active');
        document.querySelectorAll('.kategori-group').forEach(el => el.style.display = 'block');
        document.getElementById('kategori-title').innerHTML = '<i class="fa fa-list text-primary icon-gap"></i> Semua Daftar Gejala';
        document.getElementById('kategori-subtitle').innerText = 'Menampilkan seluruh daftar gejala.';
    }

    const form    = document.getElementById('diagnosa-form');
    const counter = document.getElementById('gejala-counter');
    const btn     = document.getElementById('submit-diagnosa');

    if (form) {
        function updateCounter() {
            const n = document.querySelectorAll('input[name="gejala[]"]:checked').length;
            counter.textContent = n;
            btn.disabled = (n === 0);
        }
        document.querySelectorAll('input[name="gejala[]"]').forEach(cb => cb.addEventListener('change', updateCounter));
        btn.addEventListener('click', () => {
            if (document.querySelectorAll('input[name="gejala[]"]:checked').length < 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Pilih minimal 1 gejala terlebih dahulu!',
                    confirmButtonColor: '#28a745'
                });
                return;
            }
            form.submit();
        });
        updateCounter();
    }
    </script>
</body>
</html>