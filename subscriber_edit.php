<?php
  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $err = '';
  $result = '';

  try {
    // Sprawdzenie czy tablica $_SERVER posiada wartosc POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Polaczenie z baza danych
      $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, 'root', 'root');

      // Przygotowanie zapytania
      $query = $pdo->prepare('UPDATE `subscribers` SET `fname` = :name, `email` = :email WHERE id = :id');

      // Podpiecie wartosci do placeholder'ow
      $query->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
      $query->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
      $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

      // Sprawdzenie czy zmienne nie sa null i nie sa puste
      if(!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['email']) || empty($_POST['email'])) {
        $err = 'Prosze wypełnić wszystkie pola!';
      } else if (!isset($_GET['id']) || empty($_GET['id'])) {
        $err = 'Brak rekodu w tabeli. Spróbuj jeszcze raz.';
      } else {
        $flush = $query->execute();
      }

      // Informacja o sukcesie lub beldzie w zaleznosci od wartosci zmiennej
      if ($flush > 0) {
        $result = 'Użytkownik został edytowany.';
      } else {
        $result = 'Błąd edycji użytkownika. Spróbuj jeszcze raz.';
      }
    }
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
  <title>Internetowe Bazy Danych</title>
</head>
<body>
  <form method="POST">
    <label for="name">Imie</label>
    <input type="text" name="name" id="name">
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <button type="submit">Submit</button>
  </form>
  <?php echo $err ?>
  <?php echo $result ?>
  <a href="viewsubscribers.php">Lista użytkowników</a>
  <a href="index.php">Powrót do formularza</a>
</body>
</html>
