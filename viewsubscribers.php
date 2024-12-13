<?php
  $host = 'localhost';
  $dbname = 'test';
  $username = 'root';
  $password = 'root';

  $query = '';
  $result = '';

  $tableHeaders = [];

  if($_SERVER["REQUEST_METHOD"] == "POST") {
      try {
        // Polaczenie z baza danych
        $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql_tables = '';
        $viewsSet = array_unique($_POST);
        $viewsValues = array_values($viewsSet);
        var_dump($viewsSet);
        var_dump($viewsValues);

        foreach ($viewsValues as $view) {
            $sql_tables = $sql_tables . $view . ' ';
        }
        var_dump($sql_tables);
//        $query = $pdo->query('SELECT * FROM ')

        // Przygotowanie zapytania
        $query = $pdo->query('SELECT id, fname, email FROM subscribers');

        // Pobieranie pierwszego rekordu z tabeli, aby zobaczyc czy tabela posiada rekordy
        $is_record = $query->fetch();

        // Sprawdzenie czy zmienna $is_record jest pusta, w celu wyswietlenia informacji o braku rekordow
        if (empty($is_record)) {
          $result = 'Tabela nie zawiera rekordów.';
        }
        echo 'Połączenie nawiązane.';
      } catch(PDOException $e) {
        echo 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
      }
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

  <form action="" method="POST" class="subscriber-form">
      <select name="user-type" id="user-type">
          <option value="users_added" onclick="setValue('users_added')">Wszyscy</option>
          <option value="users_exist" onclick="setValue('users_exist')">Istniejący</option>
          <option value="users_edited" onclick="setValue('users_edited')">Edytowani</option>
          <option value="users_deleted" onclick="setValue('users_deleted')">Usunięci</option>
      </select>
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
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Date of insert</th>
        <th>Date of edit</th>
        <th>Date of deletion</th>
        <th>Action</th>
      </tr>
      <?php foreach($query as $row) { ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['fname'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <td>insert</td>
        <td>edit</td>
        <td>delete</td>
        <td>
          <a href="subscriber_edit.php?id=<?php echo $row['id'] ?>">Edit</a> | <a href="subscriber_del.php?id=<?php echo $row['id']?>">Delete</a>
        </td>
      </tr>
      <?php } // foreach ?>
      <?php $query->closeCursor(); ?>
    </table>
    <p><?php echo $result; ?></p>
  <a href="index.php">Formularz</a>
</body>
</html>

<script>
function setValue(string) {
    const deleted = document.getElementById('date-delete');
   string == 'current' ? deleted.disabled = true : deleted.disabled = false;
}
</script>
