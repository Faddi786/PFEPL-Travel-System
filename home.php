<?php include ('includes/header.php'); ?>

<div class="wrapper">
  <?php
  session_start(); // Start the session
  
  // Check if there is a message in the session
  if (isset($_SESSION['message'])) {
    $message = json_encode($_SESSION['message']); // Properly escape the message
    echo "<script>alert($message)</script>";
    unset($_SESSION['message']); // Clear the message from the session
  }
  ?>
  <div class="title-text">
    <div class="title login">Start Journey</div>
    <div class="title signup">End Journey</div>
  </div>

  <div class="form-container">
    <div class="slide-controls">
      <input type="radio" name="slide" id="login" checked>
      <input type="radio" name="slide" id="signup">
      <label for="login" class="slide login">Start</label>
      <label for="signup" class="slide signup">End</label>
      <div class="slider-tab"></div>
    </div>

    <div class="form-inner">

      <form id="startJourneyForm" method="POST" action="saveStartJourney.php" class="login">
        <div class="field">
          <input placeholder="Your EmpID" type="text" id="emp_id_start" name="emp_id_start" required
            oninput="fetchEmployeeName(this.value, 'start')">
        </div>
        <div class="field">
          <input placeholder="Your name" type="text" id="emp_name_start" name="emp_name_start" required>
        </div>
        <div class="field">
          <input type="text" id="start_location" name="start_location" readonly>
        </div>
        <div class="field">
          <input type="hidden" id="start_photo" name="start_photo">
          <button class="button-15" type="button" onclick="capturePhoto('start_photo')">Capture Photo</button>
        </div>
        <div class="field">
          <input placeholder="Vehicle number" type="text" id="v_id" name="v_id"
            oninput="fetchVehicleDetails(this.value)" required>
        </div>
        <div class="field">
          <input placeholder="Vehicle name" type="text" id="v_name" name="v_name" readonly>
        </div>
        <div class="field btn">
          <div class="btn-layer"></div>
          <input type="submit" value="Start Journey">
        </div>
      </form>

      <form id="endJourneyForm" method="POST" action="saveEndJourney.php" class="signup">
        <div class="field">
          <input placeholder="Your EmpID" type="text" id="emp_id_end" name="emp_id_end" required
            oninput="fetchEmployeeName(this.value, 'end')">
        </div>
        <div class="field">
          <input placeholder="Your name" type="text" id="emp_name_end" name="emp_name_end" required>
        </div>
        <div class="field">
          <input placeholder="Your location" type="text" id="end_location" name="end_location" readonly>
        </div>
        <div class="field">
          <input type="hidden" id="end_photo" name="end_photo">
          <button class="button-15" type="button" onclick="capturePhoto('end_photo')">Capture Photo</button>
        </div>
        <div class="field">
          <input type="text" id="remark" name="remark">
        </div>
        <div class="field btn">
          <div class="btn-layer"></div>
          <input type="submit" value="End Journey">
        </div>
      </form>
    </div>
  </div>
</div>

<?php include ('includes/footer.php'); ?>