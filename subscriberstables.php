<?php
  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $users_added = '';
  $users_deleted = '';
  $users_edited = '';
  $users_added_deleted = '';
  $users_exist = '';

  try {
    // Polaczenie z baza danych
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania dla tabeli glownej
    $users_added = $pdo->query('SELECT * FROM users_added');

    $users_deleted = $pdo->query('SELECT * FROM users_deleted');

    $users_edited = $pdo->query('SELECT * FROM users_edited');

    $users_added_deleted = $pdo->query('SELECT * FROM users_added_deleted');

    $users_exist = $pdo->query('SELECT * FROM users_exist');

    echo 'Połączenie nawiązane.';

  } catch (PDOException $e) {
      echo 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Internetowe bazy danych</title>
</head>
<body>
  <p class="table-title">Użytkownicy dodani</p>
  <table>
  <tr>
    <th>Subscriber name</th>
    <th>Date added</th>
  </tr>
  <?php foreach($users_added as $row) { ?>
      <tr>
          <td><?php echo $row['subscriber_name'] ?></td>
          <td><?php echo $row['date_added'] ?></td>
      </tr>
  <?php } // foreach ?>
  </table>

  <p class="table-title">Użytkownicy usunięci</p>
  <table>
    <tr>
      <th>Subscriber name</th>
      <th>Date deleted</th>
    </tr>
    <?php foreach($users_deleted as $row) { ?>
        <tr>
            <td><?php echo $row['subscriber_name'] ?></td>
            <td><?php echo $row['date_deleted'] ?></td>
        </tr>
    <?php } // foreach ?>
  </table>

  <p class="table-title">Użytkownicy edytowani</p>
  <table>
    <tr>
      <th>Subscriber name</th>
      <th>Date edited</th>
    </tr>
    <?php foreach($users_edited as $row) { ?>
        <tr>
            <td><?php echo $row['subscriber_name'] ?></td>
            <td><?php echo $row['date_edited'] ?></td>
        </tr>
    <?php } // foreach ?>
  </table>

  <p class="table-title">Użytkownicy dodani i usunięci</p>
  <table>
    <tr>
      <th>Subscriber name</th>
      <th>Date added</th>
      <th>Date deleted</th>

    </tr>
    <?php foreach($users_added_deleted as $row) { ?>
        <tr>
            <td><?php echo $row['subscriber_name'] ?></td>
            <td><?php echo $row['date_added'] ?></td>
            <td><?php echo $row['date_deleted'] ?></td>
        </tr>
    <?php } // foreach ?>
  </table>

  <p class="table-title">Użytkownicy aktualni</p>
  <table>
    <tr>
      <th>Subscriber name</th>
    </tr>
    <?php foreach($users_exist as $row) { ?>
        <tr>
            <td><?php echo $row['subscriber_name'] ?></td>
        </tr>
    <?php } // foreach ?>
  </table>
  <a href="index.php">Formularz</a>
</body>
</html>
