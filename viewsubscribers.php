<?php
  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $query = '';
  $result = '';

  try {
    // Polaczenie z baza danych
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania dla tabeli glownej
    $query = $pdo->query('SELECT id, fname, email FROM subscribers');

    // Sprawdzenie czy sa rekordy w zapytaniu
    if ($query->rowCount() == 0) {
        $result = 'Tabela nie zawiera rekordów.';
    }
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
  <h1>Lista użytkowników</h1>
  <p>Edit - etyduje dane użytkownika. Po edycji uruchomi się wyzwalacz.</p>
  <p>Delete - usuwa użytkownika. Po usunięciu uruchomi się wyzwalacz.</p>

  <table>
      <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Action</th>
      </tr>
      <?php foreach($query as $row) { ?>
          <tr>
              <td><?php echo $row['id'] ?></td>
              <td><?php echo $row['fname'] ?></td>
              <td><?php echo $row['email'] ?></td>
              <td>
                  <a href="subscriber_edit.php?id=<?php echo $row['id'] ?>">Edit</a> | <a href="subscriber_del.php?id=<?php echo $row['id']?>">Delete</a>
              </td>
          </tr>
      <?php } // foreach ?>
  </table>

    <p><?php echo $result; ?></p>
  <a href="index.php">Formularz</a>
  <a href="subscribersdynamictable.php">Tabela dynamiczna</a>
  <a href="subscriberstables.php">Tabele statyczne</a>
</body>
</html>
