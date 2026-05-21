<?php
include 'session.php';
include 'koneksi.php';

header('Content-Type: application/json');

// Cek login untuk semua request kecuali login itu sendiri
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action !== 'login' && !isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// Ambil JSON body jika ada
$body = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($action) {

    // ==================== AUTH ====================
    case 'login':
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $result = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin']    = $admin['id_admin'];
            $_SESSION['nama']     = $admin['nama'];
            $_SESSION['username'] = $admin['username'];
            echo json_encode(['success' => true, 'nama' => $admin['nama'], 'id' => $admin['id_admin']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
        }
        break;

    case 'logout':
        destroy_session();
        echo json_encode(['success' => true]);
        break;

    case 'check_session':
        echo json_encode([
            'loggedIn' => true,
            'nama'     => $_SESSION['nama'],
            'id'       => $_SESSION['admin']
        ]);
        break;

    // ==================== DASHBOARD STATS ====================
    case 'get_stats':
        $total_produk    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
        $total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan"))['total'];
        $total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];
        $total_pembelian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembelian"))['total'];

        $laris = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT nama_produk FROM produk WHERE id_produk = (
                SELECT id_produk FROM detail_transaksi
                GROUP BY id_produk ORDER BY SUM(jumlah) DESC LIMIT 1
            )"
        ));

        echo json_encode([
            'total_produk'    => $total_produk,
            'total_pelanggan' => $total_pelanggan,
            'total_transaksi' => $total_transaksi,
            'total_pembelian' => $total_pembelian,
            'produk_laris'    => $laris ? $laris['nama_produk'] : '-'
        ]);
        break;

    // ==================== PELANGGAN ====================
    case 'get_pelanggan':
        $result = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan");
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    case 'add_pelanggan':
        $nama    = mysqli_real_escape_string($conn, $body['nama'] ?? '');
        $no_hp   = mysqli_real_escape_string($conn, $body['no_hp'] ?? '');
        $alamat  = mysqli_real_escape_string($conn, $body['alamat'] ?? '');
        if (!$nama || !$no_hp || !$alamat) { echo json_encode(['success'=>false,'message'=>'Semua field wajib diisi']); break; }
        $q = mysqli_query($conn, "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('$nama','$no_hp','$alamat')");
        echo json_encode(['success' => (bool)$q, 'id' => mysqli_insert_id($conn)]);
        break;

    case 'update_pelanggan':
        $id     = (int)($body['id_pelanggan'] ?? 0);
        $nama   = mysqli_real_escape_string($conn, $body['nama'] ?? '');
        $no_hp  = mysqli_real_escape_string($conn, $body['no_hp'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $body['alamat'] ?? '');
        $q = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id_pelanggan=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    case 'delete_pelanggan':
        $id = (int)($body['id'] ?? 0);
        $q  = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    // ==================== PRODUK ====================
    case 'get_produk':
        $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk");
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    case 'add_produk':
        $nama  = mysqli_real_escape_string($conn, $body['nama_produk'] ?? '');
        $harga = (float)($body['harga'] ?? 0);
        $stok  = (int)($body['stok'] ?? 0);
        $jenis = mysqli_real_escape_string($conn, $body['jenis_produk'] ?? '');
        if (!$nama || !$jenis) { echo json_encode(['success'=>false,'message'=>'Semua field wajib diisi']); break; }
        $q = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, jenis_produk) VALUES ('$nama',$harga,$stok,'$jenis')");
        echo json_encode(['success' => (bool)$q, 'id' => mysqli_insert_id($conn)]);
        break;

    case 'update_produk':
        $id    = (int)($body['id_produk'] ?? 0);
        $nama  = mysqli_real_escape_string($conn, $body['nama_produk'] ?? '');
        $harga = (float)($body['harga'] ?? 0);
        $stok  = (int)($body['stok'] ?? 0);
        $jenis = mysqli_real_escape_string($conn, $body['jenis_produk'] ?? '');
        $q = mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga=$harga, stok=$stok, jenis_produk='$jenis' WHERE id_produk=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    case 'delete_produk':
        $id = (int)($body['id'] ?? 0);
        $q  = mysqli_query($conn, "DELETE FROM produk WHERE id_produk=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    // ==================== PEMBELIAN ====================
    case 'get_pembelian':
        $result = mysqli_query($conn,
            "SELECT pb.*, pr.nama_produk, a.nama AS nama_admin
             FROM pembelian pb
             JOIN produk pr ON pb.id_produk = pr.id_produk
             JOIN admin a ON pb.id_admin = a.id_admin
             ORDER BY pb.id_pembelian DESC"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    case 'add_pembelian':
        $id_produk  = (int)($body['id_produk'] ?? 0);
        $tanggal    = mysqli_real_escape_string($conn, $body['tanggal'] ?? date('Y-m-d'));
        $jumlah     = (int)($body['jumlah'] ?? 0);
        $harga_beli = (float)($body['harga_beli'] ?? 0);
        $status     = mysqli_real_escape_string($conn, $body['status'] ?? 'pending');
        $id_admin   = (int)$_SESSION['admin'];
        if (!$id_produk || !$jumlah) { echo json_encode(['success'=>false,'message'=>'Data tidak lengkap']); break; }
        $q = mysqli_query($conn, "INSERT INTO pembelian (id_produk, tanggal, jumlah, harga_beli, status, id_admin) VALUES ($id_produk,'$tanggal',$jumlah,$harga_beli,'$status',$id_admin)");
        // Update stok jika status diterima
        if ($q && strtolower($status) === 'diterima') {
            mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah WHERE id_produk = $id_produk");
        }
        echo json_encode(['success' => (bool)$q, 'id' => mysqli_insert_id($conn)]);
        break;

    case 'update_status_pembelian':
        $id         = (int)($body['id'] ?? 0);
        $status_baru = mysqli_real_escape_string($conn, $body['status'] ?? '');
        // Ambil data lama dulu
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pembelian WHERE id_pembelian=$id"));
        $q   = mysqli_query($conn, "UPDATE pembelian SET status='$status_baru' WHERE id_pembelian=$id");
        // Kalau baru jadi diterima, tambah stok
        if ($q && strtolower($status_baru) === 'diterima' && strtolower($old['status']) !== 'diterima') {
            mysqli_query($conn, "UPDATE produk SET stok = stok + {$old['jumlah']} WHERE id_produk = {$old['id_produk']}");
        }
        echo json_encode(['success' => (bool)$q]);
        break;

    case 'delete_pembelian':
        $id = (int)($body['id'] ?? 0);
        $q  = mysqli_query($conn, "DELETE FROM pembelian WHERE id_pembelian=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    // ==================== TRANSAKSI ====================
    case 'get_transaksi':
        $result = mysqli_query($conn,
            "SELECT t.id_transaksi, t.tanggal, p.nama AS nama_pelanggan, a.nama AS nama_admin
             FROM transaksi t
             JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
             JOIN admin a ON t.id_admin = a.id_admin
             ORDER BY t.id_transaksi DESC"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Hitung total
            $id = $row['id_transaksi'];
            $total = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT SUM(jumlah * harga) as total FROM detail_transaksi WHERE id_transaksi=$id"
            ))['total'] ?? 0;
            $row['total'] = $total;
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'add_transaksi':
        $id_pelanggan = (int)($body['id_pelanggan'] ?? 0);
        $tanggal      = date('Y-m-d');
        $id_admin     = (int)$_SESSION['admin'];
        $items        = $body['items'] ?? [];

        if (!$id_pelanggan || empty($items)) {
            echo json_encode(['success'=>false,'message'=>'Data tidak lengkap']);
            break;
        }

        // Insert transaksi
        mysqli_query($conn, "INSERT INTO transaksi (tanggal, id_pelanggan, id_admin) VALUES ('$tanggal',$id_pelanggan,$id_admin)");
        $id_transaksi = mysqli_insert_id($conn);

        // Insert detail + kurangi stok
        foreach ($items as $item) {
            $id_produk = (int)$item['id_produk'];
            $jumlah    = (int)$item['jumlah'];
            $harga     = (float)$item['harga'];
            mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga) VALUES ($id_transaksi,$id_produk,$jumlah,$harga)");
            mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id_produk=$id_produk");
        }

        echo json_encode(['success' => true, 'id_transaksi' => $id_transaksi]);
        break;

    case 'get_detail_transaksi':
        $id = (int)($_GET['id'] ?? 0);
        $result = mysqli_query($conn,
            "SELECT dt.*, pr.nama_produk
             FROM detail_transaksi dt
             JOIN produk pr ON dt.id_produk = pr.id_produk
             WHERE dt.id_transaksi = $id"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    case 'delete_transaksi':
        $id = (int)($body['id'] ?? 0);
        // Kembalikan stok
        $details = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi=$id");
        while ($d = mysqli_fetch_assoc($details)) {
            mysqli_query($conn, "UPDATE produk SET stok = stok + {$d['jumlah']} WHERE id_produk={$d['id_produk']}");
        }
        mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi=$id");
        $q = mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi=$id");
        echo json_encode(['success' => (bool)$q]);
        break;

    // ==================== STOK ====================
    case 'get_stok':
        $result = mysqli_query($conn, "SELECT id_produk, nama_produk, stok FROM produk ORDER BY stok ASC");
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
        break;
}
?>