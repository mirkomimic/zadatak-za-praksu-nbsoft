<?php
require_once "../Zadatak4-PHP/db.php";

// a) Prikažite sve kororisnike koji su se prijavili u prethodna dva dana
$conn = DB::connectDB();


$query = "SELECT * FROM users WHERE dateCreate >= now() - interval 2 day";

$result = $conn->query($query);

while ($row = $result->fetch_assoc())
{
  echo json_encode($row);
};
