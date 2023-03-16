<?php
$title = 'Cornnect';
// open database
$db = open_sqlite_db('secure/site.sqlite');
// query the table
$result = exec_sql_query($db, 'SELECT * FROM cornnect;');
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
  'major' => '',
  'clubs' => ''
);

// sticky values
$sticky_values = array(
  'name' => '',
  'phone' => '',
  'first' => '',
  'soph' => '',
  'junior' => '',
  'senior' => '',
  'major' => '',
  'clubs' => ''
);

// validates if form was submitted:
if (isset($_POST['add-user'])) {

  $form_values['name'] = trim($_POST['name']); // untrusted
  $form_values['netid'] = trim($_POST['netid']); // untrusted
  $form_values['year'] = trim($_POST['year']); // untrusted
  $form_values['major'] = trim($_POST['major']); // untrusted
  $form_values['clubs'] = trim($_POST['clubs']); // untrusted

  $form_valid = True;

  // check if one of the years was selected
  if ($form_values['year'] == NULL) {
    $form_valid = False;
    $form_feedback_classes['year'] = '';
  }

  if ($form_values['name'] == '') {
    $form_valid = False;
    $form_feedback_classes['name'] = '';
  }

  if ($form_values['netid'] == '') {
    $form_valid = False;
    $form_feedback_classes['netid'] = '';
  }


  // show confirmation if form is valid, otherwise set sticky values and echo them
  if ($form_valid) {
    $show_confirmation = True;
  } else {
    $sticky_values['name'] = $form_values['name'];
    $sticky_values['netid'] = $form_values['netid'];
    $sticky_values['major'] = $form_values['major'];
    $sticky_values['clubs'] = $form_values['clubs'];

    $sticky_values['first'] = ($form_values['year'] == 'first' ? 'checked' : '');
    $sticky_values['soph'] = ($form_values['year'] == 'soph' ? 'checked' : '');
    $sticky_values['junior'] = ($form_values['year'] == 'junior' ? 'checked' : '');
    $sticky_values['senior'] = ($form_values['year'] == 'senior' ? 'checked' : '');
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
  if (isset($_POST['database'])) {
    $show_db = true;
    $show_form = false;
  }

  if (isset($_POST['form'])) {
    $show_db = false;
    $show_form = true;
  }
  ?>



  <?php if (!$show_confirmation && $show_form) { ?>
    <section>
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
              <input type="radio" id="first_input" name="year" value="first" <?php echo $sticky_values['first']; ?>>
              <label for="roses_input">First-Year</label>
            </div>
            <div>
              <input type="radio" id="soph_input" name="year" value="soph" <?php echo $sticky_values['soph']; ?>>
              <label for="soph_input">Sophomore</label>
            </div>
            <div>
              <input type="radio" id="junior_input" name="year" value="junior" <?php echo $sticky_values['junior']; ?>>
              <label for="junior_input">Junior</label>
            </div>
            <div>
              <input type="radio" id="senior_input" name="year" value="junior" <?php echo $sticky_values['senior']; ?>>
              <label for="senior_input">Senior</label>
            </div>
          </div>
        </div>

        <div class="label-input">
          <label for="major_field">Major:</label>
          <input id="major_field" type="text" name="major" value="<?php echo $sticky_values['major']; ?>">
        </div>
        <div class="label-input">
          <label for="clubs_field">Clubs and Activities:</label>
          <input id="clubs_field" type="text" name="clubs" value="<?php echo $sticky_values['clubs']; ?>">
        </div>

        <div class="align-right">
          <input type="submit" value="Add me!" name="add-user">
        </div>
      </form>
    </section>
  <?php } ?>

  <?php if ($show_confirmation) { ?>
    <section>
      <h2>You've Been Added!</h2>
      <p>Thank you <?php echo htmlspecialchars($form_values['name']); ?>. Classmates can now find you on the World Wide Web! Hopefully you can find other <?php echo htmlspecialchars($form_values['year']); ?>s in similar majors/clubs! </p>

      <?php $show_form = FALSE ?>
      <button>
        <p><a href="/">See the database</a>.</p>
      </button>
    </section>
  <?php } ?>

  <?php if ($show_db) { ?>
    <h3>Find your classmates:</h3>

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

    <?php } ?>
</body>

</html>
