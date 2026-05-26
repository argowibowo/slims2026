<?php
// ================= KONEKSI DATABASE =================
$koneksi = new mysqli("localhost", "root", "", "rumah_sakit_silk","3306");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// ================= TAMBAH PASIEN =================
if (isset($_POST['tambah_pasien'])) {
    $nik = $_POST['nik'];
    $nama = $_POST['nama_pasien'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $telp = $_POST['no_telp'];
    $status = $_POST['status_pasien'];

    $koneksi->query("INSERT INTO pasien (nik, nama_pasien, jenis_kelamin, tanggal_lahir, alamat, no_telp, status_pasien)
                     VALUES ('$nik', '$nama', '$jk', '$tgl', '$alamat', '$telp', '$status')");
}

// ================= TAMBAH ANTRIAN =================
if (isset($_POST['tambah_antrian'])) {
    $id_rm = $_POST['id_rm'];
    $id_staff = $_POST['id_staff'];
    $id_poli = $_POST['id_poli'];
    $jenis_antrian = $_POST['jenis_antrian'];
    $status_harian = "Menunggu";

    // Nomor antrian otomatis per hari
    $result = $koneksi->query("SELECT MAX(nomor_antrian) AS max_nomor FROM antrian");
    $row = $result->fetch_assoc();
    $nomor_antrian = $row['max_nomor'] ? $row['max_nomor'] + 1 : 1;

    $koneksi->query("INSERT INTO antrian (id_rm, id_staff, id_poli, jenis_antrian, nomor_antrian, status_harian)
                     VALUES ('$id_rm', '$id_staff', '$id_poli', '$jenis_antrian', '$nomor_antrian', '$status_harian')");
}

// ================= UPDATE ANTRIAN =================
if (isset($_POST['update_antrian'])) {
    $id_antrian = $_POST['id_antrian'];
    $status = $_POST['status_harian'];

    $koneksi->query("UPDATE antrian SET status_harian = '$status' WHERE id_antrian = '$id_antrian'");

    echo "<script>alert('Status antrian berhasil diperbarui!'); location='?page=create_antrian';</script>";
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Rumah Sakit</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; background: #f4f6f8; }
        .sidebar { width: 230px; background: #0077b6; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 8px; border-radius: 5px; margin-bottom: 10px; }
        .sidebar a:hover, .sidebar a.active { background: #0096c7; }
        .content { margin-left: 250px; padding: 30px; flex: 1; }
        h2 { color: #0077b6; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #0077b6; color: white; }
        form { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        input, select, textarea { padding: 8px; margin: 5px 0; width: 100%; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #0077b6; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0096c7; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>SILK</h2>
    <a href="?page=dashboard" class="<?= !isset($_GET['page']) || $_GET['page']=='dashboard'?'active':'' ?>">Dashboard</a>
    <a href="?page=pasien" class="<?= isset($_GET['page']) && $_GET['page']=='pasien'?'active':'' ?>">Data Pasien</a>
    <a href="?page=create_antrian&edit" class="<?= isset($_GET['page']) && $_GET['page']=='create_antrian'?'active':'' ?>">Data Antrian</a>
    <a href="?page=antrian" class="<?= isset($_GET['page']) && $_GET['page']=='antrian'?'active':'' ?>">Antrian</a>

</div>

<div class="content">
<?php
$page = $_GET['page'] ?? 'dashboard';

// ================= DASHBOARD =================
if ($page == 'dashboard') {
    $total_pasien = $koneksi->query("SELECT COUNT(*) as jml FROM pasien")->fetch_assoc()['jml'];
    $total_antrian = $koneksi->query("SELECT COUNT(*) as jml FROM antrian")->fetch_assoc()['jml'];
    echo "<h1>Selamat Datang di Sistem Informasi Rumah Sakit</h1>
          <p>Gunakan menu di sebelah kiri untuk mengelola data pasien dan antrian.</p>
          <div style='display:flex;gap:20px;margin-top:30px;'>
            <div style='background:white;padding:20px;border-radius:10px;'>
                <h2>Total Pasien</h2><p style='font-size:28px;color:#0077b6;'>$total_pasien</p>
            </div>
            <div style='background:white;padding:20px;border-radius:10px;'>
                <h2>Total Antrian</h2><p style='font-size:28px;color:#0077b6;'>$total_antrian</p>
            </div>
          </div>";
}

// ================= DATA PASIEN =================
elseif ($page == 'pasien') {
    echo "<h2>📋 Data Pasien</h2>"; ?>
    <form method="post">
        <h3>Tambah Pasien Baru</h3>
        <input type="text" name="nik" placeholder="NIK" required>
        <input type="text" name="nama_pasien" placeholder="Nama Pasien" required>
        <select name="jenis_kelamin" required>
            <option value="">-- Jenis Kelamin --</option>
            <option value="L">Laki-Laki</option>
            <option value="P">Perempuan</option>
        </select>
        <input type="date" name="tanggal_lahir" required>
        <textarea name="alamat" placeholder="Alamat"></textarea>
        <input type="text" name="no_telp" placeholder="No. Telepon">
        <select name="status_pasien" required>
            <option value="Umum">Umum</option>
            <option value="BPJS">BPJS</option>
        </select>
        <button type="submit" name="tambah_pasien">Tambah Pasien</button>
    </form>

    <table>
        <tr>
            <th>ID RM</th><th>NIK</th><th>Nama</th><th>JK</th><th>Tgl Lahir</th><th>Alamat</th><th>No. Telp</th><th>Status</th>
        </tr>
        <?php
        $data = $koneksi->query("SELECT * FROM pasien ORDER BY id_rm DESC");
        while ($row = $data->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id_rm']}</td>
                <td>{$row['nik']}</td>
                <td>{$row['nama_pasien']}</td>
                <td>{$row['jenis_kelamin']}</td>
                <td>{$row['tanggal_lahir']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['no_telp']}</td>
                <td>{$row['status_pasien']}</td>
            </tr>";
        }
        ?>
    </table>
<?php }

elseif ($page == 'create_antrian') {

    echo "<h2>➕ Tambah Antrian Pasien</h2>";

    // ==== Cek jika mode edit ====
    if (isset($_GET['edit'])) {
        $id_edit = $_GET['edit'];
        $edit_data = $koneksi->query("SELECT * FROM antrian WHERE id_antrian = '$id_edit'")->fetch_assoc();
        ?>

        <!-- <form method="post">
            <h3>✏️ Edit Status Antrian</h3>

            <input type="hidden" name="id_antrian" value="<?= $edit_data['id_antrian'] ?>">

            <label>Status Antrian</label>
            <select name="status_antrian" required>
                <option value="Menunggu" <?= $edit_data['status_antrian']=="Menunggu"?"selected":"" ?>>Menunggu</option>
                <option value="Dipanggil" <?= $edit_data['status_antrian']=="Dipanggil"?"selected":"" ?>>Dipanggil</option>
                <option value="Selesai" <?= $edit_data['status_antrian']=="Selesai"?"selected":"" ?>>Selesai</option>
            </select>

            <button type="submit" name="update_antrian">Simpan Perubahan</button>
            <a href="?page=create_antrian" style="color:red;margin-left:10px;">Batal</a>
        </form> -->

        <?php

    } else {
        // ==== Form Tambah Antrian Biasa ====
        ?>
        <form method="post">
            <select name="id_rm" required>
                <option value="">-- Pilih Pasien --</option>
                <?php
                $pasien = $koneksi->query("SELECT * FROM pasien");
                while ($p = $pasien->fetch_assoc()) {
                    echo "<option value='{$p['id_rm']}'>{$p['nama_pasien']} - {$p['nik']}</option>";
                }
                ?>
            </select>

            <select name="id_staff" required>
                <option value="">-- Pilih Staff --</option>
                <?php
                $staff = $koneksi->query("SELECT * FROM staff");
                while ($s = $staff->fetch_assoc()) {
                    echo "<option value='{$s['id_staff']}'>{$s['nama_staff']}</option>";
                }
                ?>
            </select>

            <select name="id_poli" required>
                <option value="">-- Pilih Poli --</option>
                <?php
                $poli = $koneksi->query("SELECT * FROM poli");
                while ($pl = $poli->fetch_assoc()) {
                    echo "<option value='{$pl['id_poli']}'>{$pl['nama_poli']}</option>";
                }
                ?>
            </select>

            <select name="jenis_antrian" required>
                <option value="Umum">Umum</option>
                <option value="BPJS">BPJS</option>
            </select>

            <button type="submit" name="tambah_antrian">Tambah Antrian</button>
        </form>
        <?php
    }
    ?>

    <h3>📋 Daftar Antrian</h3>
    <table>
        <tr>
            <th>No</th><th>Pasien</th><th>Staff</th><th>Poli</th><th>Jenis</th><th>No Antrian</th><th>Status</th><th>Edit</th>
        </tr>
        <?php
        $data = $koneksi->query("
            SELECT a.*, p.nama_pasien, pl.nama_poli
            FROM antrian a
            JOIN pasien p ON a.id_rm = p.id_rm
            JOIN staff s ON a.id_staff = s.id_staff
            JOIN poli pl ON a.id_poli = pl.id_poli
            ORDER BY a.id_antrian DESC
        ");
        $no = 1;
        while ($row = $data->fetch_assoc()) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['id_rm']}</td>
                <td>{$row['id_staff']}</td>
                <td>{$row['id_poli']}</td>
                <td>{$row['jenis_antrian']}</td>
                <td>{$row['nomor_antrian']}</td>
                <td>{$row['status_antrian']}</td>
                <td><a href='?page=create_antrian&edit={$row['id_antrian']}' style='color:green;font-weight:bold;'>Edit</a></td>
            </tr>";
            $no++;
        }
        ?>
    </table>

<?php }


// ================= CREATE ANTRIAN =================
elseif ($page == 'create_antrian') {
    echo "<h2>➕ Tambah Antrian Pasien</h2>"; ?>

    <form method="post">
        <select name="id_rm" required>
            <option value="">-- Pilih Pasien --</option>
            <?php
            $pasien = $koneksi->query("SELECT * FROM pasien");
            while ($p = $pasien->fetch_assoc()) {
                echo "<option value='{$p['id_rm']}'>{$p['nama_pasien']} - {$p['nik']}</option>";
            }
            ?>
        </select>

        <select name="id_staff" required>
            <option value="">-- Pilih Staff --</option>
            <?php
            $staff = $koneksi->query("SELECT * FROM staff");
            while ($s = $staff->fetch_assoc()) {
                echo "<option value='{$s['id_staff']}'>{$s['nama_staff']}</option>";
            }
            ?>
        </select>

        <select name="id_poli" required>
            <option value="">-- Pilih Poli --</option>
            <?php
            $poli = $koneksi->query("SELECT * FROM poli");
            while ($pl = $poli->fetch_assoc()) {
                echo "<option value='{$pl['id_poli']}'>{$pl['nama_poli']}</option>";
            }
            ?>
        </select>

        <select name="jenis_antrian" required>
            <option value="Umum">Umum</option>
            <option value="BPJS">BPJS</option>
        </select>

        <button type="submit" name="tambah_antrian">Tambah Antrian</button>
    </form>

<?php }

// ================= DATA ANTRIAN (READ ONLY) =================
elseif ($page == 'antrian') {
?>
    <h2>Antrian Rumah Sakit</h2>

    <?php
    // Ambil antrian yang sedang dipanggil (jika ada)
    $antrian_aktif = $koneksi->query("
        SELECT * FROM antrian 
        WHERE status_antrian = 'Dipanggil' 
        ORDER BY waktu_dilayani DESC LIMIT 1
    ")->fetch_assoc();

    // Ambil daftar antrian per poli yang belum selesai
    $loket = $koneksi->query("
        SELECT id_poli, MAX(nomor_antrian) AS nomor_antrian
        FROM antrian
        WHERE status_antrian != 'Selesai'
        GROUP BY id_poli
    ");

    if (!$loket) {
        die("Query error: " . $koneksi->error);
    }
    ?>

    <style>
        /* ===== Tampilan seperti monitor ===== */
        .antrian-container {
            background: #111;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            border-radius: 20px;
            margin-top: 40px;
        }
        .header-rs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #004d40;
            padding: 10px 20px;
            border-radius: 10px;
        }
        .header-rs h2 { margin: 0; color: #fff; }
        .antrian-aktif {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #e53935, #b71c1c);
            padding: 25px;
            border-radius: 15px;
            margin-top: 20px;
            text-align: center;
        }
        .antrian-aktif .nomor { font-size: 70px; font-weight: bold; }
        .antrian-aktif .loket { font-size: 50px; font-weight: bold; }
        .daftar-loket {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .kotak-loket {
            background: #1b5e20;
            color: #fff;
            border-radius: 15px;
            text-align: center;
            flex: 1;
            margin: 5px;
            padding: 20px;
        }
        .kotak-loket h3 { font-size: 40px; margin: 0; }
        .kotak-loket p { font-size: 20px; margin: 5px 0 0 0; }
    </style>

    <div class="antrian-container" id="antrian-section">
        <div class="header-rs">
            <h2> SILK LHUT </h2>
            <div><span id="tanggal"></span> | <span id="jam"></span></div>
        </div>

        <div class="antrian-aktif">
            <div>
                <h3>MEMANGGIL ANTRIAN</h3>
                <div class="nomor"><?= $antrian_aktif ? $antrian_aktif['nomor_antrian'] : '-' ?></div>
            </div>
            <div><span style="font-size: 50px;">➡️</span></div>
            <div>
                <h3>LOKET</h3>
                <div class="loket"><?= $antrian_aktif ? $antrian_aktif['id_poli'] : '-' ?></div>
            </div>
        </div>

        <div class="daftar-loket">
            <?php while ($row = $loket->fetch_assoc()) { ?>
                <div class="kotak-loket">
                    <h3><?= $row['nomor_antrian'] ?></h3>
                    <p>LOKET <?= $row['id_poli'] ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // ====== Jam real-time ======
        function updateWaktu() {
            const now = new Date();
            const tgl = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            const jam = now.toLocaleTimeString('id-ID');
            document.getElementById('tanggal').innerText = tgl;
            document.getElementById('jam').innerText = jam;
        }
        setInterval(updateWaktu, 120);
        updateWaktu();

        // ====== Auto refresh data antrian tiap 5 detik ======
        setInterval(() => {
            fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newSection = doc.querySelector('#antrian-section');
                    document.querySelector('#antrian-section').innerHTML = newSection.innerHTML;
                });
        }, 5000);
    </script>
<?php
}


