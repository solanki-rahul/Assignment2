<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all products
    $result = $conn->query("SELECT * FROM Product");
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request (insert new product)
    $data = json_decode(file_get_contents("php://input"), true);
    // Validate incoming data
    if (!isset($data['description']) || !isset($data['image']) || !isset($data['pricing']) || !isset($data['shipping_cost'])) {
        echo "Error: Incomplete data provided";
        exit; // Stop further execution
    }
    // Extract data
    $description = $conn->real_escape_string($data['description']);
    $image = $conn->real_escape_string($data['image']);
    $pricing = floatval($data['pricing']); // Convert to float
    $shipping_cost = floatval($data['shipping_cost']); // Convert to float
    // Validate pricing and shipping cost
    if ($pricing <= 0 || $shipping_cost <= 0) {
        echo "Error: Pricing and shipping cost must be greater than zero";
        exit; // Stop further execution
    }
    // Perform database operation
    $sql = "INSERT INTO Product (description, image, pricing, shipping_cost) VALUES ('$description', '$image', $pricing, $shipping_cost)";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request (update product)
    $data = json_decode(file_get_contents("php://input"), true);
    // Validate incoming data
    if (!isset($data['id']) || !isset($data['description']) || !isset($data['image']) || !isset($data['pricing']) || !isset($data['shipping_cost'])) {
        echo "Error: Incomplete data provided";
        exit; // Stop further execution
    }
    // Extract data
    $id = intval($data['id']); // Convert to integer
    $description = $conn->real_escape_string($data['description']);
    $image = $conn->real_escape_string($data['image']);
    $pricing = floatval($data['pricing']); // Convert to float
    $shipping_cost = floatval($data['shipping_cost']); // Convert to float
    // Validate pricing and shipping cost
    if ($pricing <= 0 || $shipping_cost <= 0) {
        echo "Error: Pricing and shipping cost must be greater than zero";
        exit; // Stop further execution
    }
    // Perform update operation
    $sql = "UPDATE Product SET description='$description', image='$image', pricing=$pricing, shipping_cost=$shipping_cost WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request (delete product)
    $data = json_decode(file_get_contents("php://input"), true);
    // Validate incoming data
    if (!isset($data['id'])) {
        echo "Error: Incomplete data provided";
        exit; // Stop further execution
    }
    // Extract data
    $id = intval($data['id']); // Convert to integer
    // Perform delete operation
    $sql = "DELETE FROM Product WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>