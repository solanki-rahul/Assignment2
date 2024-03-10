<?php
$conn = new mysqli("localhost", "root", "", "assignment2");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM Orders");
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $shipping_address = $data['shipping_address'];
    
    $sql = "INSERT INTO Orders (user_id, product_id, quantity, total_price, shipping_address) VALUES ('$user_id', '$product_id', '$quantity', '$total_price', '$shipping_address')";
    if ($conn->query($sql) === TRUE) {
        echo "New order created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $shipping_address = $data['shipping_address'];
    
    $sql = "UPDATE Orders SET quantity='$quantity', total_price='$total_price', shipping_address='$shipping_address' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    
    $sql = "DELETE FROM Orders WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Order canceled or deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
