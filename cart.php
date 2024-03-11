<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

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
    // Extract cart item data from the request and sanitize
    $product_id = sanitizeInput($data['product_id']);
    $quantity = sanitizeInput($data['quantity']);
    $user_id = sanitizeInput($data['user_id']);
    
    // Validate input data
    if (empty($product_id) || empty($quantity) || empty($user_id)) {
        echo "Error: All fields are required";
        exit;
    }
    
    // Insert the new cart item into the database
    $sql = "INSERT INTO Cart (product_id, quantity, user_id) VALUES ('$product_id', '$quantity', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New item added to cart successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing item in the cart
    $data = json_decode(file_get_contents("php://input"), true); // Parse PUT request data
    // Extract cart item data from the request and sanitize
    $id = sanitizeInput($data['id']);
    $quantity = sanitizeInput($data['quantity']);
    
    // Validate input data
    if (empty($id) || empty($quantity)) {
        echo "Error: Cart item ID and quantity are required for update";
        exit;
    }
    
    // Update the cart item in the database
    $sql = "UPDATE Cart SET quantity='$quantity' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Cart item updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to remove an item from the cart
    $data = json_decode(file_get_contents("php://input"), true); // Parse DELETE request data
    // Extract cart item ID from the request and sanitize
    $id = sanitizeInput($data['id']);
    
    // Validate input data
    if (empty($id)) {
        echo "Error: Cart item ID is required for deletion";
        exit;
    }
    
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
