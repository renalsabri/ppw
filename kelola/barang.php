<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
</head>
<body>
    <h2>Data Barang</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Number</th>
                <th>Category</th>
                <th>Item Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
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

            // Query untuk mengambil data barang
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);

            // Tampilkan data barang
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                    echo "<td>Rp " . number_format($row['price'], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No data found</td></tr>";
            }

            // Tutup koneksi
            $conn->close();
            ?>
        </tbody>
    </table>
    <br>
    <a href="insert.php"><button>Insert New Item</button></a>
</body>
</html>
