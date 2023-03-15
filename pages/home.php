<?php
$title = 'Cornnect';
// open database
$db = open_sqlite_db('secure/site.sqlite');
// query the table
$result = exec_sql_query($db, 'SELECT * FROM cornnect;');
// get records from query
$records = $result->fetchAll();

const YEAR = array(
  1885 => '1885',
  2026 => 'First-year',
  2025 => 'Sophomore',
  2024 => 'Junior',
  2023 => 'Senior',
  2 => 'soph'
);



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cornnect</title>
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">


</head>

<body>

  <h1>Cornnect</h1>
  <h2>The place for all your Pals!</h2>
  <p>Find your classmates:</p>

  <main class="cornnect">
    <h2><?php echo $title; ?></h2>

    <table>
      <tr>
        <th>Name</th>
        <th>NetID</th>
        <th>Year</th>
        <th>Major</th>
        <th>Clubs</th>
      </tr>
      <?php
      // write a table row for each record
      foreach ($records as $record) { ?>
        <tr>
          <td><?php echo htmlspecialchars($record['name']); ?></td>
          <td><?php echo htmlspecialchars($record['netid']); ?></td>
          <td><?php echo htmlspecialchars(YEAR[$record['year']]); ?></td>
          <td><?php echo htmlspecialchars($record["major"]); ?></td>
          <td><?php echo htmlspecialchars($record["clubs"]); ?></td>
        </tr>
      <?php } ?>
    </table>


</body>

</html>
