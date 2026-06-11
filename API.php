<?php
session_start();
include 'koneksi.php';

header('Content-Type: application/json');

// Cek login untuk semua request kecuali login itu sendiri
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action !== 'login' && !isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
//kalau ga login ga bisa ngeakses data, jadi distop dulu 


$method = $_SERVER['REQUEST_METHOD'];

// Ambil JSON body 
$body = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($action) {

    //nyocokin data 
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
        session_destroy();
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

        // Produk paling laris
        $laris = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT p.nama_produk, SUM(dt.jumlah) as total_jual FROM detail_transaksi dt
             JOIN produk p ON dt.id_produk = p.id_produk
             GROUP BY dt.id_produk ORDER BY total_jual DESC LIMIT 1"
        ));

        // Produk paling tidak laris
        $tidak_laris = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT p.nama_produk, COALESCE(SUM(dt.jumlah), 0) as total_jual FROM produk p
             LEFT JOIN detail_transaksi dt ON p.id_produk = dt.id_produk
             GROUP BY p.id_produk ORDER BY total_jual ASC LIMIT 1"
        ));

        // Pelanggan paling banyak transaksi
        $top_pelanggan = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT pl.nama, COUNT(t.id_transaksi) as total_transaksi FROM transaksi t
             JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
             GROUP BY t.id_pelanggan ORDER BY total_transaksi DESC LIMIT 1"
        ));

        echo json_encode([
            'total_produk'      => $total_produk,
            'total_pelanggan'   => $total_pelanggan,
            'total_transaksi'   => $total_transaksi,
            'total_pembelian'   => $total_pembelian,
            'produk_laris'      => $laris ? $laris['nama_produk'] : '-',
            'produk_laris_qty'  => $laris ? (int)$laris['total_jual'] : 0,
            'produk_tidak_laris'     => $tidak_laris ? $tidak_laris['nama_produk'] : '-',
            'produk_tidak_laris_qty' => $tidak_laris ? (int)$tidak_laris['total_jual'] : 0,
            'top_pelanggan'          => $top_pelanggan ? $top_pelanggan['nama'] : '-',
            'top_pelanggan_transaksi'=> $top_pelanggan ? (int)$top_pelanggan['total_transaksi'] : 0
        ]);
        break;

    // ==================== PELANGGAN ====================
    case 'get_pelanggan':
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $_GET['alamat'] ?? '');
        $sort   = $_GET['sort'] ?? 'default';

        $where = [];
        if ($search) $where[] = "(nama LIKE '%$search%' OR no_hp LIKE '%$search%')";
        if ($alamat) $where[] = "alamat LIKE '%$alamat%'";

        $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        switch ($sort) {
            case 'az': $order = 'ORDER BY nama ASC';          break;
            case 'za': $order = 'ORDER BY nama DESC';         break;
            default:   $order = 'ORDER BY id_pelanggan ASC';  break;
        }

        $result = mysqli_query($conn, "SELECT * FROM pelanggan $where_clause $order");
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
        // Ambil parameter filter dari URL
        $search      = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $category    = mysqli_real_escape_string($conn, $_GET['category'] ?? 'all');
        $stock_filter = $_GET['stock_filter'] ?? 'all';
        $sort        = $_GET['sort'] ?? 'default';

        // Bangun kondisi WHERE secara dinamis
        $where = [];
        if ($search) {
            $where[] = "(nama_produk LIKE '%$search%' OR jenis_produk LIKE '%$search%')";
        }
        if ($category !== 'all') {
            $where[] = "jenis_produk = '$category'";
        }
        if ($stock_filter === 'in_stock')  $where[] = "stok > 10";
        if ($stock_filter === 'low_stock') $where[] = "stok > 0 AND stok <= 10";
        if ($stock_filter === 'out_stock') $where[] = "stok = 0";

        $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Tentukan ORDER BY
        switch ($sort) {
            case 'az':         $order = 'ORDER BY nama_produk ASC';  break;
            case 'za':         $order = 'ORDER BY nama_produk DESC'; break;
            case 'price_low':  $order = 'ORDER BY harga ASC';        break;
            case 'price_high': $order = 'ORDER BY harga DESC';       break;
            case 'stock_low':  $order = 'ORDER BY stok ASC';         break;
            case 'stock_high': $order = 'ORDER BY stok DESC';        break;
            default:           $order = 'ORDER BY id_produk ASC';    break;
        }

        $result = mysqli_query($conn, "SELECT * FROM produk $where_clause $order");
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
        echo json_encode($data);
        break;

    // Ambil daftar kategori unik (untuk dropdown filter)
    case 'get_produk_categories':
        $result = mysqli_query($conn,
            "SELECT DISTINCT jenis_produk FROM produk
             WHERE jenis_produk IS NOT NULL AND jenis_produk != ''
             ORDER BY jenis_produk ASC"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row['jenis_produk'];
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
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $status = mysqli_real_escape_string($conn, $_GET['status'] ?? 'all');

        $where = [];
        if ($search) $where[] = "(pr.nama_produk LIKE '%$search%' OR pb.tanggal LIKE '%$search%')";
        if ($status !== 'all') $where[] = "pb.status = '$status'";

        $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $result = mysqli_query($conn,
            "SELECT pb.*, pr.nama_produk, a.nama AS nama_admin, a2.nama AS nama_admin_penerima
             FROM pembelian pb
             JOIN produk pr ON pb.id_produk = pr.id_produk
             JOIN admin a ON pb.id_admin = a.id_admin
             LEFT JOIN admin a2 ON pb.id_admin_penerima = a2.id_admin
             $where_clause
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
        $status     = mysqli_real_escape_string($conn, $body['status'] ?? 'diproses');
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
        
        $set_clause = "status='$status_baru'";
        if (strtolower($status_baru) === 'diterima' && strtolower($old['status']) !== 'diterima') {
            $id_admin_penerima = (int)$_SESSION['admin'];
            $tanggal_diterima = date('Y-m-d H:i:s');
            $set_clause .= ", id_admin_penerima=$id_admin_penerima, tanggal_diterima='$tanggal_diterima'";
        }

        $q   = mysqli_query($conn, "UPDATE pembelian SET $set_clause WHERE id_pembelian=$id");

        // Kalau baru jadi diterima, tambah stok (jangan hapus data)
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
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $sort   = $_GET['sort'] ?? 'default';

        $where = [];
        if ($search) $where[] = "(p.nama LIKE '%$search%' OR a.nama LIKE '%$search%' OR t.tanggal LIKE '%$search%')";

        $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        switch ($sort) {
            case 'oldest':     $order = 'ORDER BY t.id_transaksi ASC';  break;
            case 'total_high': $order = 'ORDER BY total DESC';          break;
            case 'total_low':  $order = 'ORDER BY total ASC';           break;
            default:           $order = 'ORDER BY t.id_transaksi DESC'; break;
        }

        // Total dihitung langsung via subquery di SELECT agar bisa di-ORDER BY
        $result = mysqli_query($conn,
            "SELECT t.id_transaksi, t.tanggal,
                    p.nama AS nama_pelanggan,
                    a.nama AS nama_admin,
                    (SELECT SUM(jumlah * harga) FROM detail_transaksi WHERE id_transaksi = t.id_transaksi) AS total
             FROM transaksi t
             JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
             JOIN admin a ON t.id_admin = a.id_admin
             $where_clause
             $order"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) $data[] = $row;
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