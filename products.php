<?php
$conn = new mysqli("localhost", "root", "", "assignment2");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM Product");
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $description = $data['description'];
    $image = $data['image'];
    $pricing = $data['pricing'];
    $shipping_cost = $data['shipping_cost'];
    
    $sql = "INSERT INTO Product (description, image, pricing, shipping_cost) VALUES ('$description', '$image', '$pricing', '$shipping_cost')";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $description = $data['description'];
    $image = $data['image'];
    $pricing = $data['pricing'];
    $shipping_cost = $data['shipping_cost'];
    
    $sql = "UPDATE Product SET description='$description', image='$image', pricing='$pricing', shipping_cost='$shipping_cost' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    
    $sql = "DELETE FROM Product WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
