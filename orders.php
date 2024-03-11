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
    $total_amount = mysqli_real_escape_string($conn, $data['total_amount']);
    
    // Validate input data
    if (empty($user_id) || empty($total_amount)) {
        echo "Error: Missing required fields";
    } elseif (!is_numeric($user_id)) {
        echo "Error: Invalid data format";
    } else {
        // Insert the new order into the database
        $sql = "INSERT INTO Orders (user_id, total_amount) VALUES ('$user_id', '$total_amount')";
        if ($conn->query($sql) === TRUE) {
            echo "New order created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing order
    $data = json_decode(file_get_contents("php://input"), true); // Parse PUT request data
    
    // Extract order data from the request
    $id = mysqli_real_escape_string($conn, $data['id']);
    $total_amount = mysqli_real_escape_string($conn, $data['total_amount']);
    
    // Validate input data
    if (empty($id) || empty($total_amount)|| !is_numeric($id) || !is_numeric($total_amount)) {
        echo "Error: Invalid or missing data";
    } else {
        // Update the order in the database
        $sql = "UPDATE Orders SET  total_amount='$total_amount' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Order updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to cancel or delete an order
    $data = json_decode(file_get_contents("php://input"), true); // Parse DELETE request data
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
