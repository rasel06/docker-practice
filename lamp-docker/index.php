<?php
// $connect = mysqli_connect(
//     'db',
//     'lamp_docker',
//     'password',
//     'lamp_docker'
// );

$mysqli = new mysqli(
    'db',
    'lamp_docker',
    'password',
    'lamp_docker'
);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$query = "SELECT * FROM blogs";
$result =  mysqli_query($connect, $query);

echo ("<h1>MySQL Data</h1>");
echo ('<table>');
while ($item = mysqli_fetch_assoc($result)) {

?>


    <tr>
        <td><?= $item['id'] ?></td>
        <td><?= $item['title'] ?></td>
        <td><?= $item['details'] ?></td>
        <td><?= $item['date'] ?></td>

        <td></td>
    </tr>
<?php
}
echo ('</table>');
