<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to retrieve all orders
    $result = $conn->query("SELECT * FROM Orders");
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders); // Return orders data as JSON
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to create a new order
    $data = json_decode(file_get_contents("php://input"), true); // Decode JSON data from the request body
    
    // Extract order data from the request
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $product_id = mysqli_real_escape_string($conn, $data['product_id']);
    $quantity = mysqli_real_escape_string($conn, $data['quantity']);
    $total_price = mysqli_real_escape_string($conn, $data['total_price']);
    $shipping_address = mysqli_real_escape_string($conn, $data['shipping_address']);
    
    // Validate input data
    if (empty($user_id) || empty($product_id) || empty($quantity) || empty($total_price) || empty($shipping_address)) {
        echo "Error: Missing required fields";
    } elseif (!is_numeric($user_id) || !is_numeric($product_id) || !is_numeric($quantity) || !is_numeric($total_price)) {
        echo "Error: Invalid data format";
    } else {
        // Insert the new order into the database
        $sql = "INSERT INTO Orders (user_id, product_id, quantity, total_price, shipping_address) VALUES ('$user_id', '$product_id', '$quantity', '$total_price', '$shipping_address')";
        if ($conn->query($sql) === TRUE) {
            echo "New order created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing order
    parse_str(file_get_contents("php://input"), $data); // Parse PUT request data
    
    // Extract order data from the request
    $id = mysqli_real_escape_string($conn, $data['id']);
    $quantity = mysqli_real_escape_string($conn, $data['quantity']);
    $total_price = mysqli_real_escape_string($conn, $data['total_price']);
    $shipping_address = mysqli_real_escape_string($conn, $data['shipping_address']);
    
    // Validate input data
    if (empty($id) || empty($quantity) || empty($total_price) || empty($shipping_address) || !is_numeric($id) || !is_numeric($quantity) || !is_numeric($total_price)) {
        echo "Error: Invalid or missing data";
    } else {
        // Update the order in the database
        $sql = "UPDATE Orders SET quantity='$quantity', total_price='$total_price', shipping_address='$shipping_address' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Order updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to cancel or delete an order
    parse_str(file_get_contents("php://input"), $data); // Parse DELETE request data
    $id = mysqli_real_escape_string($conn, $data['id']); // Extract order ID from the request
    
    // Validate input data
    if (empty($id) || !is_numeric($id)) {
        echo "Error: Invalid order ID";
    } else {
        // Delete the order from the database
        $sql = "DELETE FROM Orders WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Order canceled or deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>
