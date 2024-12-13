<?php
  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $err = '';
  $result = '';

  try {
    // Polaczenie z baza danych
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania
    $query = $pdo->prepare('DELETE FROM subscribers WHERE id = :id');

    // Podpiecie wartosci do placeholder'a
    $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);


    // Sprawdzenie czy wartosc zmiennej nie jest pusta ani null
    if (!isset($_GET['id']) || empty($_GET['id'])) {
      $err = 'Nie znaleziono rekordu.';
    } else {
      $flush = $query->execute();
    }

    if ($flush > 0) {
      $result = 'Użytkownik został usunięty.';
    } else {
      $result = 'Błąd usuwania użytkownika. Spróbuj jeszcze raz.';
    }
    $query->closeCursor();
  } catch(PDOException $e) {
    $result = 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
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
  <?php $err ?>
  <?php echo $result ?>
  <a href="viewsubscribers.php">Powrót do listy</a>
  <a href="index.php">Powrót do formularza</a>
</body>
</html>
