<?php
require_once "../Zadatak4-PHP/db.php";

$conn = DB::connectDB();

// c) Prikažite sve korisnike koji su imali najmanje dve porudžbine
$query = "SELECT users.*
          FROM users
          JOIN orders ON users.id = orders.userId
          GROUP BY users.id
          HAVING COUNT(orders.id) >= 2";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
