<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "assignment2");

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
    // Extract comment data from the request
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];
    $rating = $data['rating'];
    $image = $data['image'];
    $text = $data['text'];
    
    // Insert the new comment into the database
    $sql = "INSERT INTO Comments (product_id, user_id, rating, image, text) VALUES ('$product_id', '$user_id', '$rating', '$image', '$text')";
    if ($conn->query($sql) === TRUE) {
        echo "New comment added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update an existing comment
    parse_str(file_get_contents("php://input"), $data); // Parse PUT request data
    // Extract comment data from the request
    $id = $data['id'];
    $rating = $data['rating'];
    $text = $data['text'];
    
    // Update the comment in the database
    $sql = "UPDATE Comments SET rating='$rating', text='$text' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Comment updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a comment
    parse_str(file_get_contents("php://input"), $data); // Parse DELETE request data
    $id = $data['id']; // Extract comment ID from the request
    
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
