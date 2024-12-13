<?php
// Sandra Liu, 156146, gr D1, Internetowe Bazy Danych Projekt, zad 2 - PHP

  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $err = '';
  $result = '';

  try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Polaczenie z bazą danych
      $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Przygotowanie zapytania
      $query = $pdo->prepare('INSERT INTO `subscribers`(`fname`, `email`) VALUES(
      :name,
      :email
      )');

      // Podpiecie danych z formularza do konkretnych placeholder'ow z zapytania
      $query->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
      $query->bindValue(':email', $_POST['email'], PDO::PARAM_STR);

      // Sprawdzenie czy zmienne nie sa puste ani null
      if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['email']) || empty($_POST['email'])) {
        $err = 'Prosze wypelnic wszystkie pola!';
      } else {
        $flush = $query->execute();
      }

      // Informacja o sukcesie lub bledzie w zaleznosci od wartosci zmiennej $flush
      if ($flush > 0) {
        $result = 'Użytkownik dodany.';
      } else {
        $result = 'Nie udało sie dodać użytkownika. Spróbuj jeszcze raz.';
      }

      echo 'Polaczenie nawiazane.';
      $query->closeCursor();
    }
  } catch(PDOException $e) {
    echo 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internetowe bazy danych</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Formularz dodający użytkownika</h1>
  <p>Submit - dodaje użytkownika. Przed dodaniem użytkownika do bazy danych uruchomi się wyzwalacz.</p>
  <form action="#" method="POST">
    <label for="name">Imie</label>
    <input name="name" id="name" type="text">
    <label for="email">Email</label>
    <input name="email" id="email" type="email">
    <button type="submit">Submit</button>
  </form>
  <?php echo $err ?>
  <?php echo $result ?>
  <a href="viewsubscribers.php">Lista użytkowników</a>
</body>
</html>
