<?php
$title = 'Cornnect';
// open database
$db = open_sqlite_db('secure/site.sqlite');
// query the table
$result = exec_sql_query($db, 'SELECT * FROM cornellians;');
// get records from query
$records = $result->fetchAll();

// year arrays for key-value pairing
const YEAR = array(
  1885 => '1885',
  2026 => 'First-year',
  2025 => 'Sophomore',
  2024 => 'Junior',
  2023 => 'Senior',
  2 => 'soph'
);


// default page state.
$show_confirmation = False;
if ($form_valid == False) {
  $show_form = True;
  $show_db = False;
} else {
  $show_form = False;
  $show_db = True;
}


// feedback message CSS classes
$form_feedback_classes = array(
  'name' => 'hidden',
  'year' => 'hidden',
  'netid' => 'hidden'
);

// values
$form_values = array(
  'name' => '',
  'netid' => '',
  'year' => '',
  'major' => NULL,
  'club' => NULL,
);

// sticky values
$sticky_values = array(
  'name' => '',
  'phone' => '',
  '2026' => '',
  '2025' => '',
  '2024' => '',
  '2023' => '',
  'year' => NULL,
  'major' => NULL,
  'clubs' => NULL
);

// validates if form was submitted:
if (isset($_POST['add-user'])) {
  $form_values['name'] = trim($_POST['name']);
  $form_values['netid'] = trim($_POST['netid']);
  $form_values['year'] = trim($_POST['year']);
  $form_values['major'] = trim($_POST['major']);
  $form_values['club'] = trim($_POST['club']);

  // make sure major and club are NULL if empty
  if ($form_values['major'] == '') {
    $form_values['major'] = NULL;
  }

  if ($form_values['club'] == '') {
    $form_values['club'] = NULL;
  }

  $form_valid = True;

  // check if one of the years was selected
  if ($form_values['year'] == NULL) {
    $form_valid = False;
    $form_feedback_classes['year'] = NULL;
    $retry_form = True;
  }

  if ($form_values['name'] == '') {
    $form_valid = False;
    $form_feedback_classes['name'] = '';
    $retry_form = True;
  }

  if ($form_values['netid'] == '') {
    $form_valid = False;
    $form_feedback_classes['netid'] = '';
    $retry_form = True;
  }

  // show confirmation if form is valid, otherwise set sticky values and echo them
  if ($form_valid) {
    exec_sql_query(
      $db,
      "INSERT INTO cornellians (name, netid, year, major, club) VALUES (:name, :netid, :year, :major, :club);",
      array(
        ':name' => $form_values['name'],
        ':netid' => $form_values['netid'],
        ':year' => $form_values['year'],
        ':major' => $form_values['major'],
        ':club' => $form_values['club']
      )
    );
    $retry_form = False;
    $show_confirmation = True;
  } else {
    $retry_form = True;

    $sticky_values['name'] = $form_values['name'];
    $sticky_values['netid'] = $form_values['netid'];
    $sticky_values['major'] = $form_values['major'];
    $sticky_values['club'] = $form_values['club'];

    $sticky_values['2026'] = ($form_values['year'] == '2026' ? 'checked' : '');
    $sticky_values['2025'] = ($form_values['year'] == '2025' ? 'checked' : '');
    $sticky_values['2024'] = ($form_values['year'] == '2024' ? 'checked' : '');
    $sticky_values['2023'] = ($form_values['year'] == '2023' ? 'checked' : '');
  }
}
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

  <div id="menu-bar">
    <form action="/" method="post">
      <input type="hidden" name="database">
      <button type="submit" name="database">See the Database</button>
    </form>
    <form action="/" method="post">
      <input type="hidden" name="form">
      <button type="submit" name="form">Add yourself!</button>
    </form>
  </div>


  <?php
  if ($retry_form == TRUE) {
    $show_db = False;
    $show_form = True;
  } else {
    if (isset($_POST['database'])) {
      $show_db = true;
      $show_form = false;
    }

    if (isset($_POST['form'])) {
      $show_db = false;
      $show_form = true;
    }
  }
  ?>



  <?php if (!$show_confirmation && $show_form) { ?>
    <section>
      <div class="add-form">

        <h2>Add yourself!</h2>
        <p>Help other classmates find and connect with you!</p>
        <form method="post" action="/" novalidate>
          <p class="feedback <?php echo $form_feedback_classes['name']; ?>">Please provide your name.</p>
          <div class="label-input">
            <label for="name_field">Your Name:</label>
            <input id="name_field" type="text" name="name" value="<?php echo $sticky_values['name']; ?>">
          </div>
          <p class="feedback <?php echo $form_feedback_classes['netid']; ?>">Please provide your netID</p>
          <div class="label-input">
            <label for="netid_field">netID:</label>
            <input id="netid_field" type="text" name="netid" value="<?php echo $sticky_values['netid']; ?>">
          </div>
          <p class="feedback <?php echo $form_feedback_classes['year']; ?>">Please select your year.</p>
          <div class="form-group label-input" role="group" aria-labelledby="year_head">
            <div id="year_head">Year:</div>
            <div>
              <div>
                <input type="radio" id="first_input" name="year" value="2026" <?php echo $sticky_values['2026']; ?>>
                <label for="first_input">First-Year</label>
              </div>
              <div>
                <input type="radio" id="soph_input" name="year" value="2025" <?php echo $sticky_values['2025']; ?>>
                <label for="soph_input">Sophomore</label>
              </div>
              <div>
                <input type="radio" id="junior_input" name="year" value="2024" <?php echo $sticky_values['2024']; ?>>
                <label for="junior_input">Junior</label>
              </div>
              <div>
                <input type="radio" id="senior_input" name="year" value="2023" <?php echo $sticky_values['2023']; ?>>
                <label for="senior_input">Senior</label>
              </div>
            </div>
          </div>
          <div class="label-input">
            <label for="major_field">Major:</label>
            <input id="major_field" type="text" name="major" value="<?php echo $sticky_values['major']; ?>">
          </div>
          <div class="label-input">
            <label for="club_field">Clubs and Activities:</label>
            <input id="club_field" type="text" name="club" value="<?php echo $sticky_values['club']; ?>">
          </div>
          <div class="add-button">
            <input type="submit" value="Add me!" name="add-user">
          </div>
        </form>
      </div>
    </section>
  <?php } ?>

  <?php if ($show_confirmation) { ?>
    <div class="confirmation">
      <section>
        <h3>You've Been Added!</h3>
        <p>Thank you <?php echo htmlspecialchars($form_values['name']); ?>. Classmates can now find you on the World Wide Web! Hopefully you can find other <?php echo htmlspecialchars(YEAR[$form_values['year']]); ?>s in similar majors/clubs! </p>
        <?php $show_form = FALSE ?>
      </section>
    </div>
  <?php } ?>

  <?php if ($show_db) { ?>
    <div class="table">
      <h3>Find your classmates:</h3>

      <main class="cornnect">

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
              <td><?php echo htmlspecialchars($record["club"]); ?></td>
            </tr>
          <?php } ?>
        </table>
    </div>
  <?php } ?>
</body>

</html>
