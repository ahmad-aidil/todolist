<?php
//menghubungkan file koneksi
include 'koneksi.php';

// 1. Tambah Tugas (CREATE)
if (isset($_POST['tambah'])) {
    // trim() untuk hapus spasi di awal/akhir input
    $nama_tugas = trim($_POST['nama_tugas']);

    // Cek apakah variable tidak kosong setelah ditrim
    if (!empty($nama_tugas)) {
        $query = "INSERT INTO tugas (nama_tugas) VALUES ('$nama_tugas')";
        mysqli_query($koneksi, $query);
    }
    header("Location: index.php");
    exit();
}

// 2. Ubah Status dari Tugas (UPDATE)
if (isset($_GET['set_status'])) {
    $id = $_GET['id'];
    $status_baru = $_GET['set_status'];

    $query = "UPDATE tugas SET status='$status_baru' WHERE id=$id";
    mysqli_query($koneksi, $query);
    header("Location: index.php");
    exit();
}

// 3 Hapus Tugas (DELETE)
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $query = "DELETE FROM tugas WHERE id=$id";
    mysqli_query($koneksi, $query);
    header("Location: index.php");
    exit();
}

// 4 Ubah Nama Tugas (EDIT)
if (isset($_POST['edit_tugas'])) {
    $id = $_POST['id'];
    // trim() untuk hapus spasi di awal/akhir input
    $nama_baru = trim($_POST['nama_tugas_baru']);
    
    // Cek apakah nama baru mengandung karakter nyata setelah di-trim
    if (!empty($nama_baru)) {
        $query = "UPDATE tugas SET nama_tugas='$nama_baru' WHERE id=$id";
        mysqli_query($koneksi, $query);
    }
    header("Location: index.php");
    exit();
}

// 5. Ambil Seluruh Data (READ)
$result = mysqli_query($koneksi, "SELECT * FROM tugas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi To-Do List</title>
    <!-- tautan untuk mengambil aset css dari library bootstrap yakni file bootstrap.min.css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4 fw-bold text-primary">Daftar Tugas (To-Do List)</h3>

                    <!-- Form tambah tugas -->
                    <form action="" method="POST" class="input-group mb-4">
                        <input type="text" name="nama_tugas" class="form-control" placeholder="Masukkan nama tugas baru..." required>
                        <button type="submit" name="tambah" class="btn btn-primary font-weight-bold">Tambah</button>
                    </form>

                    <!-- tabel berupa tampilan tugas tiap baris record -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">No</th>
                                    <th>Tugas</th>
                                    <th width="20%">Status</th>
                                    <th width="45%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) : 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="<?= $row['status'] == 'Selesai' ? 'text-decoration-line-through text-muted' : ''; ?>">
                                        <?= htmlspecialchars($row['nama_tugas']); ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $row['status'] == 'Selesai' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                            <?= $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- checkbox untuk mengubah status tugas apakah sudah diselesaikan -->
                                        <input type="checkbox" class="form-check-input me-2" 
                                            onchange="location.href='index.php?id=<?= $row['id']; ?>&set_status=<?= $row['status'] == 'Belum' ? 'Selesai' : 'Belum'; ?>'" 
                                            <?= $row['status'] == 'Selesai' ? 'checked' : ''; ?>>

                                        <!-- Tombol untuk memicu edit nama tugas -->
                                        <button type="button" class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">
                                            ✎ Edit
                                        </button>

                                        <!-- Tombol untuk menghapus tugas sesuai barisnya -->
                                        <a href="index.php?hapus_id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tugas ini?')">
                                            ✕ Hapus
                                        </a>
                                    </td>

                                    <!-- Bagian untuk edit nama tugas -->
                                    <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                            <h5 class="modal-title">Edit Nama Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <input type="text" name="nama_tugas_baru" class="form-control" value="<?= htmlspecialchars($row['nama_tugas']); ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="edit_tugas" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    </div>

                                </tr>
                                <?php 
                                    endwhile; 
                                } else {
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada tugas.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>