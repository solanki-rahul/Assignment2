<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to retrieve all users
    $result = $conn->query("SELECT * FROM User");
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users); // Return users data as JSON
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to create a new user
    $data = json_decode(file_get_contents("php://input"), true); // Decode JSON data from the request body
    // Extract user data from the request and sanitize
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
    $purchase_history = filter_var($data['purchase_history'], FILTER_SANITIZE_STRING);
    $shipping_address = filter_var($data['shipping_address'], FILTER_SANITIZE_STRING);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }
    
    // Insert the new user into the database
    $sql = "INSERT INTO User (email, password, username, purchase_history, shipping_address) VALUES ('$email', '$password', '$username', '$purchase_history', '$shipping_address')";
    if ($conn->query($sql) === TRUE) {
        echo "New user added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing user
    $data = json_decode(file_get_contents("php://input"), true); // Parse PUT request data
    // Extract user data from the request and sanitize
    $id = $data['id'];
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
    $purchase_history = filter_var($data['purchase_history'], FILTER_SANITIZE_STRING);
    $shipping_address = filter_var($data['shipping_address'], FILTER_SANITIZE_STRING);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }
    
    // Update the user in the database
    $sql = "UPDATE User SET email='$email', password='$password', username='$username', purchase_history='$purchase_history', shipping_address='$shipping_address' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a user
    $data = json_decode(file_get_contents("php://input"), true); // Parse DELETE request data
    $id = $data['id']; // Extract user ID from the request
    
    // Delete the user from the database
    $sql = "DELETE FROM User WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
