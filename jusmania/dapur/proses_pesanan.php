<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// Handle perubahan status jika ada form submit
if (isset($_POST['update_status'])) {
    $id_pesanan = intval($_POST['id_pesanan']);
    $status_baru = intval($_POST['status_baru']);

    mysqli_query($conn, "UPDATE pesanan SET id_update_ad = $status_baru WHERE id = $id_pesanan");
    header("Location: proses_pesanan.php");
    exit;
}

// Ambil semua pesanan yang statusnya 'paid' atau 'diproses'
$query = mysqli_query($conn, "
    SELECT p.id_user, u.nama AS nama_user, p.id_update_ad, p.waktu_pesan, ua.status,
           p.id AS id_pesanan, mj.nama_jus, p.jumlah, p.pakai_es, p.pakai_gula
    FROM pesanan p
    JOIN users u ON p.id_user = u.id
    JOIN menujus mj ON p.id_menu = mj.id
    JOIN update_ad ua ON p.id_update_ad = ua.id
    WHERE p.id_update_ad IN (2,3) -- 2: paid, 3: diproses
    ORDER BY p.waktu_pesan DESC
");

// Kelompokkan pesanan berdasarkan waktu_pesan
$pesanan_per_group = [];

while ($row = mysqli_fetch_assoc($query)) {
    $waktu = $row['waktu_pesan'];

    if (!isset($pesanan_per_group[$waktu])) {
        $pesanan_per_group[$waktu] = [
            'waktu_pesan' => $waktu,
            'nama_user' => $row['nama_user'],
            'pesanan' => []
        ];
    }
    $pesanan_per_group[$waktu]['pesanan'][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proses Pesanan (Dapur)</title>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            background: #f1f1f1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #aaa;
            text-align: center;
        }
        .status {
            font-weight: bold;
            color: #d9534f;
        }
        .status.diproses {
            color: orange;
        }
        .status.selesai {
            color: green;
        }
        .status.paid {
            color: blue;
        }
    </style>
</head>
<body>

<h2>Pesanan Dapur - Diproses</h2>

<?php if (empty($pesanan_per_group)): ?>
    <p><em>Belum ada pesanan yang dibayar atau sedang diproses.</em></p>
<?php else: ?>
    <?php foreach ($pesanan_per_group as $group): ?>
        <div class="card">
            <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($group['nama_user']) ?></p>
            <p><strong>Waktu Pesan:</strong> <?= $group['waktu_pesan'] ?></p>
            <!-- menampilkan data pesanan yang sudah dibayar dari berbagai pelanggan-->
            <table>
                <tr>
                    <th>Nama Jus</th>
                    <th>Jumlah</th>
                    <th>Es</th>
                    <th>Gula</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($group['pesanan'] as $pesanan): ?>
                <tr>
                    <td><?= htmlspecialchars($pesanan['nama_jus']) ?></td>
                    <td><?= $pesanan['jumlah'] ?></td>
                    <td><?= $pesanan['pakai_es'] ? '✔️' : '❌' ?></td>
                    <td><?= $pesanan['pakai_gula'] ? '✔️' : '❌' ?></td>                        
                    <td class="status <?= $pesanan['status'] ?>">
                        <?= strtoupper($pesanan['status']) ?>
                    </td> <!-- mengubah status pesanan -->
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                            <select name="status_baru">
                                <option value="3" <?= $pesanan['id_update_ad'] == 3 ? 'selected' : '' ?>>Diproses</option>
                                <option value="4">Selesai</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<br>
</body>
</html>
