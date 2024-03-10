<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to retrieve all items in the cart
    $result = $conn->query("SELECT * FROM Cart");
    $cartItems = array();
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    echo json_encode($cartItems); // Return cart items data as JSON
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to add a new item to the cart
    $data = json_decode(file_get_contents("php://input"), true); // Decode JSON data from the request body
    // Extract cart item data from the request
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $user_id = $data['user_id'];
    
    // Insert the new cart item into the database
    $sql = "INSERT INTO Cart (product_id, quantity, user_id) VALUES ('$product_id', '$quantity', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New item added to cart successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing item in the cart
    parse_str(file_get_contents("php://input"), $data); // Parse PUT request data
    // Extract cart item data from the request
    $id = $data['id'];
    $quantity = $data['quantity'];
    
    // Update the cart item in the database
    $sql = "UPDATE Cart SET quantity='$quantity' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Cart item updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to remove an item from the cart
    parse_str(file_get_contents("php://input"), $data); // Parse DELETE request data
    $id = $data['id']; // Extract cart item ID from the request
    
    // Delete the cart item from the database
    $sql = "DELETE FROM Cart WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Cart item deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
