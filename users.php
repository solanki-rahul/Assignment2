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
    // Extract user data from the request
    $email = $data['email'];
    $password = $data['password'];
    $username = $data['username'];
    $purchase_history = $data['purchase_history'];
    $shipping_address = $data['shipping_address'];
    
    // Insert the new user into the database
    $sql = "INSERT INTO User (email, password, username, purchase_history, shipping_address) VALUES ('$email', '$password', '$username', '$purchase_history', '$shipping_address')";
    if ($conn->query($sql) === TRUE) {
        echo "New user added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing user
    parse_str(file_get_contents("php://input"), $data); // Parse PUT request data
    // Extract user data from the request
    $id = $data['id'];
    $email = $data['email'];
    $password = $data['password'];
    $username = $data['username'];
    $purchase_history = $data['purchase_history'];
    $shipping_address = $data['shipping_address'];
    
    // Update the user in the database
    $sql = "UPDATE User SET email='$email', password='$password', username='$username', purchase_history='$purchase_history', shipping_address='$shipping_address' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a user
    parse_str(file_get_contents("php://input"), $data); // Parse DELETE request data
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
