
<?php
$host = "localhost";
$user = "root";  // Change this if you have a different MySQL user
$pass = "";      // Add your MySQL password if needed
$dbname = "product_db1";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $telephone = $_POST["telephone"];
    $city = $_POST["city"];

    $stmt = $conn->prepare("INSERT INTO products1 (product_id, product_name, telephone, city) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $product_id, $product_name, $telephone, $city);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Product added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Search product by ID
$product_result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_product'])) {
    $search_id = $_POST["search_id"];
    $query = $conn->prepare("SELECT * FROM products1 WHERE product_id = ?");
    $query->bind_param("s", $search_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $product_result = "<h3>Search Results:</h3>";
        while ($row = $result->fetch_assoc()) {
            $product_result .= "<p><strong>Product ID:</strong> " . $row["product_id"] . "<br>
                                <strong>Name:</strong> " . $row["product_name"] . "<br>
                                <strong>Telephone:</strong> " . $row["telephone"] . "<br>
                                <strong>City:</strong> " . $row["city"] . "</p>";
        }
    } else {
        $product_result = "<p style='color: red;'>No product found with ID: $search_id</p>";
    }
    $query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST">
        <label>Product ID:</label>
        <input type="text" name="product_id" required><br>
        <label>Product Name:</label>
        <input type="text" name="product_name" required><br>
        <label>Telephone:</label>
        <input type="text" name="telephone" required><br>
        <label>City:</label>
        <input type="text" name="city" required><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Search Product by ID</h2>
    <form method="POST">
        <label>Enter Product ID:</label>
        <input type="text" name="search_id" required>
        <button type="submit" name="search_product">Search</button>
    </form>

    <?php echo $product_result; ?>
</body>
</html>