<?php
require_once "../Zadatak4-PHP/db.php";

$conn = DB::connectDB();

// e) Prikažite ime I prezime korisnika, id porudžbine koja ima najmanje dve stavke

$query = "SELECT users.firstname, users.lastname, orders.id
          FROM users
          INNER JOIN orders ON users.id = orders.userId
          INNER JOIN order_item ON orders.id = order_item.orderId
          HAVING COUNT(order_item.id) >= 2";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
