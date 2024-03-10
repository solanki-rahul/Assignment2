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
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $quantity = $data['quantity'];
    
    $sql = "UPDATE Cart SET quantity='$quantity' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Cart item updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    
    $sql = "DELETE FROM Cart WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Cart item deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
