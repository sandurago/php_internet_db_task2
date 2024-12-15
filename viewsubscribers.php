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

    // Sprawdzenie czy nastapilo wyslanie ządania dla wyswietlenia danych z tabeli audit_subscribers
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Przygotowanie zapytania, ta kolumna bedzie zawsze wybierana
        $stmt = 'SELECT subscriber_name';

        // Jezeli zostal wybrany checkbox z "Data dodania"
        if($_POST['users_added'] == 'users_added') {
            $stmt = $stmt . ', date_added';
        } // Jezeli zostal wybrany checkbox z "Data edytowania"
        if($_POST['users_edited'] == 'users_edited') {
            $stmt = $stmt . ', date_edited';
        } // Jezeli zostal wybrany checkbox z "Data usuniecia"
        if ($_POST['users_deleted'] == 'users_deleted') {
            $stmt = $stmt . ', date_deleted';
        }

        // Przygotowanie zapytania
        $query_log = $pdo->query($stmt . ' FROM `all_users`');

        echo 'Połączenie nawiązane.';
      }

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

  <form action="" method="POST" class="subscriber-form">
      <div class="labels">
          <div>
              <label for="date-add">Data dodania</label>
              <input type="checkbox" id="date-add" name="users_added" value="users_added">
          </div>
          <div>
              <label for="date-edit">Data edytowania</label>
              <input type="checkbox" id="date-edit" name="users_edited" value="users_edited">
          </div>
          <div>
              <label for="date-delete">Data usunięcia</label>
              <input type="checkbox" id="date-delete" name="users_deleted" value="users_deleted">
          </div>
      </div>
      <button type="submit" value="submit">Pokaż tabelę</button>
  </form>

    <table>
      <tr>
        <th>Name</th>
        <?php if ($_POST['users_added'] == 'users_added'): ?>
        <th>Date of insert</th>
        <?php endif; ?>
        <?php if ($_POST['users_edited'] == 'users_edited'): ?>
        <th>Date of update</th>
        <?php endif; ?>
        <?php if ($_POST['users_deleted'] == 'users_deleted'): ?>
        <th>Date of deletion</th>
        <?php endif; ?>
      </tr>
      <?php foreach($query_log as $row): ?>
      <tr>
        <td><?php echo $row['subscriber_name'] ?></td>
        <?php if ($_POST['users_added'] == 'users_added'): ?>
        <td><?php echo $row['date_added'] ?></td>
        <?php endif; ?>
        <?php if ($_POST['users_edited'] == 'users_edited'): ?>
        <td><?php if($row['date_edited']) {
            echo $row['date_edited'];
        } else {
            echo 'Not updated';
            }?></td>
        <?php endif; ?>
        <?php if ($_POST['users_deleted'] == 'users_deleted'): ?>
        <td><?php if($row['date_deleted']) {
            echo $row['date_deleted'];
        } else {
            echo 'Not deleted';
            }?></td>
        <?php endif; ?>
      </tr>
      <?php endforeach; ?>
      <?php $query->closeCursor(); ?>
    </table>
    <p><?php echo $result; ?></p>
  <a href="index.php">Formularz</a>
</body>
</html>