<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to retrieve all comments
    $result = $conn->query("SELECT * FROM Comments");
    $comments = array();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode($comments); // Return comments data as JSON
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to add a new comment
    $data = json_decode(file_get_contents("php://input"), true); // Decode JSON data from the request body
    // Extract and sanitize comment data from the request
    $product_id = sanitize_input($data['product_id']);
    $user_id = sanitize_input($data['user_id']);
    $rating = sanitize_input($data['rating']);
    $image = sanitize_input($data['image']);
    $text = sanitize_input($data['text']);
    
    // Validate input data
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($text)) {
        echo "Error: All fields are required";
        exit;
    }
    
    // Insert the new comment into the database
    $sql = "INSERT INTO Comments (product_id, user_id, rating, image, text) VALUES ('$product_id', '$user_id', '$rating', '$image', '$text')";
    if ($conn->query($sql) === TRUE) {
        echo "New comment added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing comment
    $data = json_decode(file_get_contents("php://input"), true); // Parse PUT request data
    // Extract and sanitize comment data from the request
    $id = sanitize_input($data['id']);
    $rating = sanitize_input($data['rating']);
    $text = sanitize_input($data['text']);
    
    // Validate input data
    if (empty($id) || empty($rating) || empty($text)) {
        echo "Error: Comment ID, rating, and text are required for updating";
        exit;
    }
    
    // Update the comment in the database
    $sql = "UPDATE Comments SET rating='$rating', text='$text' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Comment updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a comment
    $data = json_decode(file_get_contents("php://input"), true); // Parse DELETE request data
    $id = sanitize_input($data['id']); // Extract and sanitize comment ID from the request
    
    // Validate input data
    if (empty($id)) {
        echo "Error: Comment ID is required for deletion";
        exit;
    }
    
    // Delete the comment from the database
    $sql = "DELETE FROM Comments WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Comment deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
