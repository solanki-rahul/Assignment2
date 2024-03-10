<?php
$conn = new mysqli("localhost", "root", "", "assignment2");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM Comments");
    $comments = array();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode($comments);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];
    $rating = $data['rating'];
    $image = $data['image'];
    $text = $data['text'];
    $sql = "INSERT INTO Comments (product_id, user_id, rating, image, text) VALUES ('$product_id', '$user_id', '$rating', '$image', '$text')";
    if ($conn->query($sql) === TRUE) {
        echo "New comment added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $rating = $data['rating'];
    $text = $data['text'];
    
    $sql = "UPDATE Comments SET rating='$rating', text='$text' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Comment updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
