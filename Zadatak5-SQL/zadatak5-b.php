<?php
require_once "../Zadatak4-PHP/db.php";

$conn = DB::connectDB();

// b) Prikažite ime I prezime korisnika, id porudžbine I ukupnu vrednost svih porudžbinama

$query = "SELECT users.firstname, users.lastname, orders.id, SUM(orders.value) as total_value
FROM users
INNER JOIN orders
ON users.id = orders.userId";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
