<?php
    // Koneksi ke database
    $servername = "localhost";
    $username = "root"; // Ganti dengan username Anda
    $password = ""; // Ganti dengan password Anda
    $dbname = "your_database"; // Ganti dengan nama database Anda

    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Cek apakah form telah disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $number = $_POST['number'];
        $category = $_POST['category'];
        $item_name = $_POST['item_name'];
        $price = $_POST['price'];
        $update = $_POST['update'];
        $delete = $_POST['delete'];

        // Simpan file gambar
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $target_dir = "uploads/"; // Tentukan folder penyimpanan gambar
        $target_file = $target_dir . basename($image_name);

        // Pindahkan file gambar ke folder tujuan
        if (move_uploaded_file($image_tmp, $target_file)) {
            // Query untuk menyimpan data ke database
            $sql = "INSERT INTO items (number, category, item_name, image, price, update_info, delete_info) 
                    VALUES ('$number', '$category', '$item_name', '$target_file', '$price', '$update', '$delete')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Data berhasil disimpan.";
                // Redirect kembali ke halaman utama setelah berhasil
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Gagal mengunggah gambar.";
        }
    }

    // Tutup koneksi
    $conn->close();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Tambah Barang</h2>
    <form action="insert.php" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Number</th>
                <td><input type="number" name="number" required></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><input type="text" name="category" required></td>
            </tr>
            <tr>
                <th>Item Name</th>
                <td><input type="text" name="item_name" required></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><input type="number" name="price" required></td>
            </tr>
            <tr>
            <tr>
                <td colspan="2"><button type="submit">Insert</button></td>
            </tr>
        </table>
    </form>
</body>
</html>