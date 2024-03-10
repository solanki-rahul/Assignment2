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
}

$conn->close();
?>
