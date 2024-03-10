<?php
$conn = new mysqli("localhost", "root", "", "assignment2");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM Cart");
    $cartItems = array();
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    echo json_encode($cartItems);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $user_id = $data['user_id'];
    
    $sql = "INSERT INTO Cart (product_id, quantity, user_id) VALUES ('$product_id', '$quantity', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New item added to cart successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
