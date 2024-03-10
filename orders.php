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
    $user_id = $data['user_id'];
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $shipping_address = $data['shipping_address'];
    
    // Insert the new order into the database
    $sql = "INSERT INTO Orders (user_id, product_id, quantity, total_price, shipping_address) VALUES ('$user_id', '$product_id', '$quantity', '$total_price', '$shipping_address')";
    if ($conn->query($sql) === TRUE) {
        echo "New order created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing order
    parse_str(file_get_contents("php://input"), $data); // Parse PUT request data
    // Extract order data from the request
    $id = $data['id'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $shipping_address = $data['shipping_address'];
    
    // Update the order in the database
    $sql = "UPDATE Orders SET quantity='$quantity', total_price='$total_price', shipping_address='$shipping_address' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to cancel or delete an order
    parse_str(file_get_contents("php://input"), $data); // Parse DELETE request data
    $id = $data['id']; // Extract order ID from the request
    
    // Delete the order from the database
    $sql = "DELETE FROM Orders WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Order canceled or deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
