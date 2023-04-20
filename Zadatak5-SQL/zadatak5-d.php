<?php
require_once "../Zadatak4-PHP/db.php";

$conn = DB::connectDB();
// d) Prikažite ime I prezime korisnika, id porudžbine I broj stavki za svaku porudžbinu

$query = "SELECT users.firstname, users.lastname, orders.id, COUNT(order_item.id) AS count_products
          FROM users
          INNER JOIN orders ON users.id = orders.userId
          INNER JOIN order_item ON orders.id = order_item.orderId";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
