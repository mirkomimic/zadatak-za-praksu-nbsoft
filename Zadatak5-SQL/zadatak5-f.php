<?php
require_once "../Zadatak4-PHP/db.php";

$conn = DB::connectDB();

// f) Prikažite sve korisnike koji su kupili najmanje tri različita proizvoda u svim porudžbinama 

$query = "SELECT DISTINCT users.*
          FROM users
          JOIN orders ON orders.userId = users.id
          JOIN order_item ON orders.id = order_item.orderId
          JOIN (
            SELECT id, COUNT(DISTINCT productId) AS distinct_items
            FROM order_item
            HAVING COUNT(DISTINCT productId) >= 3
          ) AS order_counts
          ON order_item.orderId = orders.id";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
