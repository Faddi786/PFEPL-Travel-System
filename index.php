<?php
// Disable caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // HTTP 1.0
header("Pragma: no-cache"); // HTTP 1.0
session_start();

// // Echo the entire session array
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';


// Check if the session variable 'journey_id' exists
if (isset($_SESSION['journey_id']) && $_SESSION['journey_id'] !=="") {
  // Fetch the journey_id from the session
  $journey_id = $_SESSION['journey_id'];
// Assuming start_kilometer is already set in session
$start_kilometer = isset($_SESSION['start_kilometer']) ? $_SESSION['start_kilometer'] : 0;

  // Inject JavaScript into the HTML
  // echo "<script>console.log('Journey ID: " . htmlspecialchars($journey_id) . "');</script>";
}



// // Check if the session variables are set and print them
// if (isset($_SESSION['emp_id'])) {
//   echo "Employee ID: " . $_SESSION['emp_id'] . "<br>";
// }

// if (isset($_SESSION['emp_name'])) {
//   echo "Employee Name: " . $_SESSION['emp_name'] . "<br>";
// }

// if (isset($_SESSION['vehicle_number'])) {
//   echo "Vehicle Number: " . $_SESSION['vehicle_number'] . "<br>";
// }

// if (isset($_SESSION['vehicle_name'])) {
//   echo "Vehicle Name: " . $_SESSION['vehicle_name'] . "<br>";
// }

// if (isset($_SESSION['role'])) {
//   echo "Role: " . $_SESSION['role'] . "<br>";
// }



// // Check if the session variable 'vehicle_ids' is set
// if (isset($_SESSION['vehicle_ids'])) {
//   $vehicle_ids = $_SESSION['vehicle_ids'];

//   // Debugging: Print the array using print_r for readability
//   echo "Vehicle IDs from session: ";
//   print_r($vehicle_ids);
// } else {
//   echo "No vehicle IDs found in session.";
// }

// if (isset($_SESSION['start_kilometer'])) {
//   echo "start kilometer: " . $_SESSION['start_kilometer'] . "<br>";
// }



// // Check if the session variable 'vehicle_ids' is set
// if (isset($_SESSION['vehicle_ids'])) {
//   $vehicle_ids = $_SESSION['vehicle_ids'];
  
//   // Convert PHP array to JSON to pass to JavaScript
//   $vehicle_ids_json = json_encode($vehicle_ids);
// } else {
//   $vehicle_ids_json = json_encode([]); // Empty array if no vehicle IDs are found
// }

if ($_SESSION['role'] != 0) {
  header('Location: login.html');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS
    <link rel="stylesheet" href="style.css"> -->


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">


  <title>Travel Reimbursment System</title>

  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    html,
    body {
      display: grid;
      height: 100%;
      width: 100%;
      place-items: center;
      /*background-color: #8EC5FC;*/
      background-image: linear-gradient(62deg, #8EC5FC 0%, #E0C3FC 100%);
    }

    ::selection {
      background: #1a75ff;
      color: #fff;
    }

    .wrapper {
      overflow: hidden;
      max-width: 390px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
    }

    .wrapper .title-text {
      display: flex;
      width: 200%;
    }

    .wrapper .title {
      width: 50%;
      font-size: 29px;
      font-weight: 600;
      text-align: center;
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .wrapper .slide-controls {
      position: relative;
      display: flex;
      height: 50px;
      width: 100%;
      overflow: hidden;
      margin: 30px 0 10px 0;
      justify-content: space-between;
      border: 1px solid lightgrey;
      border-radius: 15px;
    }

    .slide-controls .slide {
      height: 100%;
      width: 100%;
      color: #fff;
      font-size: 18px;
      font-weight: 500;
      text-align: center;
      line-height: 48px;
      cursor: pointer;
      z-index: 1;
      transition: all 0.6s ease;
    }

    .slide-controls label.signup {
      color: #000;
    }

    .slide-controls .slider-tab {
      position: absolute;
      height: 100%;
      width: 50%;
      left: 0;
      z-index: 0;
      border-radius: 15px;
      background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    input[type="radio"] {
      display: none;
    }

    #signup:checked~.slider-tab {
      left: 50%;
    }

    #signup:checked~label.signup {
      color: #fff;
      cursor: default;
      user-select: none;
    }

    #signup:checked~label.login {
      color: #000;
    }

    #login:checked~label.signup {
      color: #000;
    }

    #login:checked~label.login {
      cursor: default;
      user-select: none;
    }

    .wrapper .form-container {
      width: 100%;
      overflow: hidden;
    }

    .form-container .form-inner {
      display: flex;
      width: 200%;
    }

    .form-container .form-inner form {
      width: 50%;
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .form-inner form .field {
      height: 50px;
      width: 100%;
      margin-top: 20px;
    }

    .form-inner form .field input {
      height: 100%;
      width: 100%;
      outline: none;
      padding-left: 15px;
      border-radius: 15px;
      border: 1px solid lightgrey;
      border-bottom-width: 2px;
      font-size: 17px;
      transition: all 0.3s ease;
    }

    .form-inner form .field input:focus {
      border-color: #1a75ff;
      /* box-shadow: inset 0 0 3px #fb6aae; */
    }

    .form-inner form .field input::placeholder {
      color: #999;
      transition: all 0.3s ease;
    }

    form .field input:focus::placeholder {
      color: #1a75ff;
    }

    .form-inner form .pass-link {
      margin-top: 5px;
    }

    .form-inner form .signup-link {
      text-align: center;
      margin-top: 30px;
    }

    .form-inner form .pass-link a,
    .form-inner form .signup-link a {
      color: #1a75ff;
      text-decoration: none;
    }

    .form-inner form .pass-link a:hover,
    .form-inner form .signup-link a:hover {
      text-decoration: underline;
    }

    form .btn {
      height: 50px;
      width: 100%;
      border-radius: 15px;
      position: relative;
      overflow: hidden;
    }

    form .btn .btn-layer {
      height: 100%;
      width: 300%;
      position: absolute;
      left: -100%;
      background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6);
      border-radius: 15px;
      transition: all 0.4s ease;
      ;
    }

    form .btn:hover .btn-layer {
      left: 0;
    }

    .autocomplete {
      position: relative;
      display: inline-block;
    }

    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      padding: 10px;
      font-size: 16px;
    }

    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }

    input[type=submit] {
      background-color: DodgerBlue;
      color: #fff;
      cursor: pointer;
    }

    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;

    }

    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff;
      border-bottom: 1px solid #d4d4d4;
    }

    /* /when hovering an item:/ */
    .autocomplete-items div:hover {
      background-color: #e9e9e9;
    }

    /* /when navigating through the items using the arrow keys:/  */
    .autocomplete-active {
      background-color: DodgerBlue !important;
      color: #ffffff;
    }

    form .btn input[type="submit"] {
      height: 100%;
      width: 100%;
      z-index: 1;
      position: relative;
      background: none;
      border: none;
      color: #fff;
      padding-left: 0;
      border-radius: 15px;
      font-size: 20px;
      font-weight: 500;
      cursor: pointer;
    }

    .button-15 {
      background-image: linear-gradient(#42A1EC, #0070C9);
      border: 1px solid #0077CC;
      border-radius: 4px;
      box-sizing: border-box;
      color: #FFFFFF;
      cursor: pointer;
      direction: ltr;
      display: block;
      font-family: "SF Pro Text", "SF Pro Icons", "AOS Icons", "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 17px;
      font-weight: 400;
      letter-spacing: -.022em;
      line-height: 1.47059;
      min-width: 30px;
      overflow: visible;
      padding: 4px 15px;
      text-align: center;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
      white-space: nowrap;
    }

    .button-15:disabled {
      cursor: default;
      opacity: .3;
    }

    .button-15:hover {
      background-image: linear-gradient(#51A9EE, #147BCD);
      border-color: #1482D0;
      text-decoration: none;
    }

    .button-15:active {
      background-image: linear-gradient(#3D94D9, #0067B9);
      border-color: #006DBC;
      outline: none;
    }

    .button-15:focus {
      box-shadow: rgba(131, 192, 253, 0.5) 0 0 0 3px;
      outline: none;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #E3F2FD;
    }

    .select-menu {
      width: 380px;
      margin: 140px auto;
    }

    .select-menu .select-btn {
      display: flex;
      height: 55px;
      background: #fff;
      padding: 20px;
      font-size: 18px;
      font-weight: 400;
      border-radius: 8px;
      align-items: center;
      cursor: pointer;
      justify-content: space-between;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .select-btn i {
      font-size: 25px;
      transition: 0.3s;
    }

    .select-menu.active .select-btn i {
      transform: rotate(-180deg);
    }

    .select-menu .options {
      position: relative;
      padding: 20px;
      margin-top: 10px;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
      display: none;
    }

    .select-menu.active .options {
      display: block;
    }

    .options .option {
      display: flex;
      height: 55px;
      cursor: pointer;
      padding: 0 16px;
      border-radius: 8px;
      align-items: center;
      background: #fff;
    }

    .options .option:hover {
      background: #F2F2F2;
    }

    .option i {
      font-size: 25px;
      margin-right: 12px;
    }

    .option .option-text {
      font-size: 18px;
      color: #333;
    }

    .mobile-content {
      display: none;
    }

    #start-error-message {
    color: blue; 
    background-color: white; /* Light red background */
    border: 1px solid blue; /* Dark red border */
    border-radius: 5px; /* Slightly rounded corners */
    padding: 10px; /* Some space around the text */
    margin-top: 5px; /* Space above the message */
    position: relative; /* Required for z-index to take effect */
    z-index: 1000; /* Increase the stacking order */
    font-size: 13px;/* display: none; Initially hidden */
    font-weight: bold;
    display: none;

}

#end-error-message {
  display: none;
    color: blue; 
    background-color: white; /* Light red background */
    border: 1px solid blue; /* Dark red border */
    border-radius: 5px; /* Slightly rounded corners */
    padding: 10px; /* Some space around the text */
    margin-top: 5px; /* Space above the message */
    position: relative; /* Required for z-index to take effect */
    z-index: 1000; /* Increase the stacking order */
    font-size: 13px;/* display: none; Initially hidden */
    font-weight: bold;
}

/*
    .error-visible {
        display: block; 
  }
*/
  </style>

</head>

<body>
  <div class="wrapper mobile-content">
    <div style="display: flex; justify-content: space-between; align-items:center;margin-top: 20px;">
      <img src="./static/logo/logo.png" alt="Logo" width="60px" class="mx-2">
      <a href="auth.php?action=logout" class="button-15" style="text-decoration:none; width:fit-content;  background: #0060c1; align-items:left">↩️Logout </a>
    </div>

    <br>
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
            <input placeholder="Your EmpID" type="text" id="emp_id_start" name="emp_id_start" required value="<?php echo $_SESSION['emp_id'] ?>">
          </div>
          <div class="field">
            <input placeholder="Your name" type="text" id="emp_name_start" name="emp_name_start" required readonly value="<?php echo $_SESSION['emp_name'] ?>">
          </div>

          <div>
            <select class="field" style="display: flex;
            height: 49px;
            cursor: pointer;
            padding: 0 16px;
            border-radius: 15px;
            align-items: center;
            background: #fff;" id="project" name="project" required>
              <option value="">Select Project</option>
            </select>
          </div>

          <div class="field">
            <input type="number" id="start_kilometer" name="start_kilometer" step="0.0001" placeholder="Enter start KM" required>
          </div>

          <div class="field">
            <input type="text" id="start_location" name="start_location" oninput="setStartLocation()" readonly>
          </div>

          <div class="field">
            <!-- Hidden input field to store the photo path -->
            <input type="hidden" id="start_photo" name="start_photo">

            <!-- Hidden file input element restricted to camera -->
            <input type="file" id="photo_input" capture="camera" style="display: none;" required>

            <!-- Placeholder for displaying the captured photo -->
            <button type="button" id="start_photo" class='button-15' onclick="capturePhoto('start')" style="background: #0060c1">Capture
              Photo</button>
          </div>
          <div id="startPhotoContainer" style="margin-top: 10px; display: block;">

          <p id="startphotomsg">Photo not uploaded</p>
          </div>

          <div class="autocomplete field" style="width:300px;">
            <input type="text" id="v_id_start" name="v_id_start" placeholder="Vehicle Number" oninput="fetchVehicleDetails(this.value, 'start')" style="width: 110%;" required>
            <span id="start-error-message"></span><br><br>

          </div>

          <div class="field">
            <input placeholder="Vehicle name" type="text" id="v_name_start" name="v_name_start" readonly>
          </div>

          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="Submit" id="start_submit" onclick="return validateStartForm()">
          </div>

        </form>
        <form id="endJourneyForm" method="POST" action="saveEndJourney.php" class="signup">
          <div class="field">
            <input placeholder="Your EmpID" type="text" id="emp_id_end" name="emp_id_end" required readonly value="<?php echo $_SESSION['emp_id'] ?>">
          </div>
          <div class="field">
            <input placeholder="Your name" type="text" id="emp_name_end" name="emp_name_end" required readonly value="<?php echo $_SESSION['emp_name'] ?>"><br><br>
          </div>
          <div class="field">
            <input placeholder="Vehicle number" type="text" id="v_id_end" name="v_id_end" required readonly value="<?php echo $_SESSION['vehicle_number'] ?>">
          </div>

          <div class="field">
            <input placeholder="Vehicle name" type="text" id="v_name_end" name="v_name_end" required readonly value="<?php echo $_SESSION['vehicle_name'] ?>">
          </div>

          <div class="field">
    <input type="text" id="end_kilometer" name="end_kilometer" step="0.0001" placeholder="Enter end KM" oninput="validateEndKilometer()" required>
    <span id="end-error-message"></span><br><br>
</div>


          <div class="field">
            <input type="text" id="end_location" name="end_location" oninput="setEndLocation()" readonly>
          </div>

          <div class="field">
            <input type="hidden" id="end_photo" name="end_photo">
            <button class="button-15" type="button" onclick="capturePhoto('end')" style="background: #0060c1">Capture
              Photo</button>
          </div>
          <p id="endphotomsg">Photo not uploaded</p>
          <div class="field">
            <input type="text" id="remark" placeholder='Remarks' name="remark" required>
          </div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="Submit" onclick="return validateEndForm()">
            </div>

        </form>
      </div>
    </div>
  </div>


  <script>
<?php if (isset($start_kilometer)) : ?>
    let startKilometer = <?php echo json_encode($start_kilometer); ?>;
<?php endif; ?>
</script>

  <script>




function validateEndForm() {
    // Call the validatePhoto function
    const isPhotoValid = validatePhoto('end');
    
    // Call the validateEndKilometer function
    const isEndKmValid = validateEndKilometer();

    // Check distance validity
    const is500distancevalid = check500distance();
    const is300distancevalid = check300distance();

    // Validate end kilometer
    if (!isEndKmValid) {
        alert("Please enter proper end kilometer");
        return false; // Stop form submission
    } 
    
    // Handle 300 km distance validation with confirm dialog
    if (is300distancevalid) {
        const userConfirmed = confirm("Distance is more than 300, do you confirm?");
        if (!userConfirmed) {
            return false; // Stop form submission if the user clicks Cancel
        }
    } 
    
    // Handle 500 km distance validation
    if (is500distancevalid) {
        alert("Distance is more than 500, please check.");
        return false; // Stop form submission
    } 



    // Only return true if all validations are true
    return isPhotoValid && isEndKmValid && !is500distancevalid;
}






function check500distance(){

  let endkm = document.getElementById('end_kilometer').value;

  startkm = startKilometer;
  distance = endkm - startkm;

  if (distance>500){
    return true;
  }else{
    return false;
  }

}

function check300distance(){

let endkm = document.getElementById('end_kilometer').value;

startkm = startKilometer;
distance = endkm - startkm;

if (distance>=300 && distance <= 500){
  return true;
}else{
  return false;
}

}



function validateEndKilometer() {
    let endKilometer = document.getElementById('end_kilometer').value;
    let errorMessage = document.getElementById('end-error-message');
    
    // Assuming startKilometer is defined elsewhere in your code
    startkmlength = startKilometer.toString().length;

    console.log(`start kilometer : ${startkmlength}`);

    // Clear any previous error messages
    errorMessage.textContent = "";
    // errorMessage.classList.remove('error-visible'); // Hide the error message initially

    // Check if end kilometer is a number and if it's greater than start kilometer
    if (endKilometer && !isNaN(endKilometer)) {
        endKilometer = parseFloat(endKilometer);
        startKilometer = parseFloat(startKilometer);

        // Check if end_kilometer is less than or equal to start_kilometer
        if (endKilometer <= startKilometer) {
            errorMessage.textContent = "End km must be greater than " + startKilometer;
            errorMessage.classList.add('error-visible'); // Show the error message
            errorMessage.style.display = "block"; // Show the error message

            return false;
        }
    } else {
        errorMessage.textContent = "Please enter a valid number.";
        errorMessage.classList.add('error-visible'); // Show the error message
        errorMessage.style.display = "block"; // Show the error message

        return false;
    }
    errorMessage.style.display = "none"; // Hide the error message

    // If everything is okay, hide the error message
    errorMessage.classList.remove('error-visible'); // Ensure error message is hidden
    return true;
}





    //switching tabs
    const loginText = document.querySelector(".title-text .login");
    const loginForm = document.querySelector("form.login");
    const loginBtn = document.querySelector("label.login");
    const signupBtn = document.querySelector("label.signup");
    const signupLink = document.querySelector("form .signup-link a");
    signupBtn.onclick = (() => {
      loginForm.style.marginLeft = "-50%";
      loginText.style.marginLeft = "-50%";
    });
    loginBtn.onclick = (() => {
      loginForm.style.marginLeft = "0%";
      loginText.style.marginLeft = "0%";
    });
    signupLink.onclick = (() => {
      signupBtn.click();
      return false;
    });
  </script>
  <?php
  // Check for unfinished journey alert
  $disableForm = false;
  if (isset($_SESSION['unfinished_journey']) && $_SESSION['unfinished_journey']) {
    $disableForm = true;
    $alertMessage = htmlspecialchars($_SESSION['journey_alert_message'], ENT_QUOTES, 'UTF-8'); // Escape for HTML
    echo "
    <style>
        #customModal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4); /* Black background with opacity */
        }
        #modalContent {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            font-size: 18px; /* Increase font size */
            color: #333;
            text-align: center; /* Center the text */
            border-radius: 8px; /* Rounded corners */
        }
        #closeBtn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        #closeBtn:hover,
        #closeBtn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <div id='customModal'>
        <div id='modalContent'>
            <span id='closeBtn'>&times;</span>
            <p><strong>Alert:</strong><br>" . $alertMessage . "</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('customModal');
            var closeBtn = document.getElementById('closeBtn');
            var submitButton = document.getElementById('start_submit');
            
            // Display the modal
            modal.style.display = 'block';

            // Disable the submit button
            if (submitButton) {
                submitButton.disabled = true;
            }

            // Close the modal when the close button is clicked
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                // Re-enable the submit button when closing the modal
                if (submitButton) {
                    submitButton.disabled = true;
                }
            };

            // Close the modal when clicking anywhere outside of the modal content
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    // Re-enable the submit button when closing the modal
                    if (submitButton) {
                        submitButton.disabled = true;
                    }
                }
            };
        });
    </script>";
} else {
    $disableForm = false;
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var submitButton = document.getElementById('start_submit');
            // Ensure the submit button is enabled if it exists
            if (submitButton) {
                submitButton.disabled = false;
            }
        });
    </script>";
}

  if ($disableForm) : ?>
    <script>
      window.onload = function() {
        disableFormFields();

      };
    </script>
  <?php endif; ?>
  <script>


    function disableFormFields() {
      const fields = document.querySelectorAll('#emp_id_start, #emp_name_start, #v_id_start, #v_name_start, #project, #start_kilometer, #start_photo, #start_submit');
      fields.forEach(field => field.disabled = true);
      $disableForm = false;
    }

    function getLocation(callback) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(callback, showError);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          alert("User denied the request for Geolocation.");
          break;
        case error.POSITION_UNAVAILABLE:
          alert("Location information is unavailable.");
          break;
        case error.TIMEOUT:
          alert("The request to get user location timed out.");
          break;
        case error.UNKNOWN_ERROR:
          alert("An unknown error occurred.");
          break;
      }
    }

    function fetchEmployeeDetails(empName) {
      if (empName.length == 0) {
        document.getElementById("emp_id").innerHTML = "<option value=''>Select Employee ID</option>";
        return;
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var empIDs = JSON.parse(this.responseText);
          var empIDDropdown = document.getElementById("emp_id");
          empIDDropdown.innerHTML = "<option  value=''>Select Employee ID</option>";
          for (var i = 0; i < empIDs.length; i++) {
            var option = document.createElement("option");
            option.value = empIDs[i];
            option.text = empIDs[i];
            empIDDropdown.add(option);
          }
        }
      };
      xmlhttp.open("GET", "getEmployeeDetails.php?emp_name=" + empName, true);
      xmlhttp.send();
    }

    function isMobileDevice() {
      return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
    }

    if (window.innerWidth <= 800 || isMobileDevice()) {
      document.querySelector('.mobile-content').style.display = 'block';
    } else {
      alert("This form is to be filled using Mobile Phone!");
      window.location.href = 'login.html';
    }



    function fetchEmployeeName(empId, formType) {
      if (empId.length == 0) {
        document.getElementById(formType === 'start' ? "emp_name_start" : "emp_name_end").value = "";
        return;
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(formType === 'start' ? "emp_name_start" : "emp_name_end").value = this.responseText;
        }
      };
      xmlhttp.open("GET", "getEmployeeDetails.php?emp_id=" + empId, true);
      xmlhttp.send();
    }

    function fetchProjectDetails() {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          try {
            var projects = JSON.parse(this.responseText);
            var projectDropdown = document.getElementById("project");

            projectDropdown.innerHTML = "<option value=''>Select Project</option>";
            projects.forEach(function(project) {
              var option = document.createElement("option");
              option.value = project.project_id; // Assuming your project_id is named project_id
              option.text = project.project_name;
              projectDropdown.add(option);
            });
          } catch (e) {
            console.error("Error parsing JSON: ", e);
            console.log("Response Text: ", this.responseText);
          }
        }
      };
      xmlhttp.open("GET", "getProjectDetails.php", true);
      xmlhttp.send();
    }

    // // Function to fetch site details based on the selected project
    // function fetchSiteDetails(projectId) {
    //   console.log("Fetching sites for project ID:", projectId);

    //   var xmlhttp = new XMLHttpRequest();
    //   xmlhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //       try {
    //         var sites = JSON.parse(this.responseText);
    //         console.log("Sites fetched:", sites);

    //         var siteDropdown = document.getElementById("sites");
    //         siteDropdown.innerHTML = "<option value=''>Select Site</option>";

    //         sites.forEach(function(site) {
    //           var option = document.createElement("option");
    //           option.value = site.site_id; // Assuming your site_id is named site_id
    //           option.text = site.site_name;
    //           siteDropdown.add(option);
    //         });
    //       } catch (e) {
    //         console.error("Error parsing JSON: ", e);
    //         console.log("Response Text: ", this.responseText);
    //       }
    //     }
    //   };
    //   xmlhttp.open("GET", "getSites.php?project_id=" + projectId, true);
    //   xmlhttp.send();
    // }

    // // Event listener for project dropdown change
    // document.getElementById("project").addEventListener("change", function() {
    //   var projectId = this.value; // Get the selected project ID
    //   console.log("Selected Project ID:", projectId);
    //   fetchSiteDetails(projectId); // Pass the project ID to fetch site details
    // });

    // Call fetchProjectDetails when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', fetchProjectDetails);

    function capturePhoto(formType) {
      console.log("Capture Photo: ok");
      // Trigger the hidden file input element to open camera
      document.getElementById('photo_input').click();

      // Listen for changes in the input
      document.getElementById('photo_input').addEventListener('change', function() {
        const file = this.files[0]; // Get the selected file

        if (file) {
          // Create FormData and append the selected file
          const formData = new FormData();
          formData.append('photo', file, 'photo.jpg'); // Assume saving as JPG

          // Perform the upload via fetch
          fetch('upload_photo.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              console.log('Server response:', data);
              if (data.success) {

                // Assuming ${formType}_photo is the ID of your input field for storing the image path
                document.getElementById(`${formType}_photo`).value = data.imagePath;
                //alert('Photo captured and uploaded successfully!');

            // Update the text message instead of displaying the image
            const messageElement = document.getElementById(`${formType}photomsg`);
            messageElement.textContent = 'Image uploaded successfully'; // Change the message
            messageElement.style.color = 'green'; // Set the text color to green
            messageElement.style.fontWeight = 'bold'; // Make the text bold

                // // Display the image in the form
                // const img = document.createElement('img');
                // img.src = data.imagePath;
                // img.style.width = '100px';
                // img.style.height = 'auto';
                // // Assuming ${formType}PhotoContainer is the ID of the container where you want to display the image
                // const container = document.getElementById(`${formType}PhotoContainer`);
                // container.innerHTML = ''; // Clear existing content
                // container.appendChild(img);
              } else {
                messageElement.textContent = 'Upload Failed, please try again'; // Change the message
            messageElement.style.color = 'red'; // Set the text color to green
            messageElement.style.fontWeight = 'bold'; // Make the text bold
                console.error('Upload failed:', data.error);
                alert('Failed to upload photo. Error: ' + data.error);
              }
            })
            .catch(error => {
              console.error('Error uploading photo:', error);
              alert('Error uploading photo. Please try again.');
            });
        }
      });
    }


    function setStartLocation() {
      getLocation(function(position) {
        document.getElementById('start_location').value = position.coords.latitude + ", " + position.coords.longitude;
        document.getElementById('start_latitude').value = position.coords.latitude;
        document.getElementById('start_longitude').value = position.coords.longitude;
      });
    }

    function setEndLocation() {
      getLocation(function(position) {
        document.getElementById('end_location').value = position.coords.latitude + ", " + position.coords.longitude;
        document.getElementById('end_latitude').value = position.coords.latitude;
        document.getElementById('end_longitude').value = position.coords.longitude;
      });
    }

    function validatePhoto(formType) {
      const photoField = document.getElementById(`${formType}_photo`).value;
      if (!photoField) {
        alert("Uploading the image is compulsory.");
        return false;
      }
      return true;
    }



    document.addEventListener('DOMContentLoaded', function() {
      setStartLocation();
      setEndLocation();
    });


    // auto start
    function autocomplete(inp) {
      var currentFocus;

      inp.addEventListener("input", function(e) {
        var val = this.value;
        if (!val) {
          closeAllLists();
          return false;
        }
        currentFocus = -1;

        fetch(`get_vehicles.php?term=${val}`)
          .then(response => response.json())
          .then(arr => {
            closeAllLists();
            var a, b, i, id;
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(a);
            id = this.id

            for (i = 0; i < arr.length; i++) {
              b = document.createElement("DIV");
              b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i].substr(val.length);
              b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
              b.addEventListener("click", function(e) {
                inp.value = this.getElementsByTagName("input")[0].value;
                if (id == "v_id_start") {
                  fetchVehicleDetails(inp.value, 'start')
                }
                if (id == "v_id_end") {
                  fetchVehicleDetails(inp.value, 'end')
                }


                closeAllLists();
              });
              a.appendChild(b);
            }
          });
      });

      inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          currentFocus++;
          addActive(x);
        } else if (e.keyCode == 38) {
          currentFocus--;
          addActive(x);
        } else if (e.keyCode == 13) {
          e.preventDefault();
          if (currentFocus > -1) {
            if (x) x[currentFocus].click();
          }
        }
      });

      function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
      }

      function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }

      function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }

      document.addEventListener("click", function(e) {
        closeAllLists(e.target);
      });
    }

    // Initialize autocomplete for multiple inputs
    document.addEventListener("DOMContentLoaded", function() {
      var inputIds = ["v_id_start", "v_id_end"]; // Replace with your actual input IDs
      inputIds.forEach(function(id) {
        var input = document.getElementById(id);
        if (input) {
          autocomplete(input);
        }
      });
    });


//     function fetchVehicleDetails(vehicleNumber, formType) {
//   console.log('Enter : ' + vehicleNumber);
  
//   if (vehicleNumber.length == 0) {
//     document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = "";
//     return;
//   }
  
//   var xmlhttp = new XMLHttpRequest();
  
//   xmlhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//       // Parse the JSON response from the PHP
//       var response = JSON.parse(this.responseText);
//       console.log("this is the response name" + response.v_name)

      
//       // Check the vehicle status, and alert if the status is 0
//       if (response.status == 1) {
//         alert("This car is currently working. Choose another car.");
//         document.getElementById(formType === 'start' ? "v_id_start" : "v_id_end").value = "";

//       }
//       else
//       {
//               // Set the vehicle name in the respective input field
//       document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = response.v_name;
//       }
//     }
//   };
  
//   // Send request to the PHP script
//   xmlhttp.open("GET", "getVehicleDetails.php?v_id=" + vehicleNumber, true);
//   xmlhttp.send();
// }
// // Now, pass the PHP array (JSON format) to a JavaScript variable


// // Print the array in JavaScript console
// console.log("Vehicle IDs: ", vehicleIds);


// function validateVehicleExists(inputValue) {
//     let errorMessage = document.getElementById('error-message'); // Error message element

//     // Clear any previous error messages
//     errorMessage.textContent = "";

//     // Check if inputValue exists in the vehicleIds array
//     if (vehicleIds.includes(inputValue)) {
//         // Value exists, no action needed
//         return true;
//     } else {
//         // Value does not exist, display an error message
//         errorMessage.textContent = "Vehicle doesn't exist, please add it from the admin.";
//         errorMessage.classList.add('error-visible'); // Show the error message
//         return false;
//     }
// }

    // Initialize the autocomplete functionality

    // auto end

    
let vehicleexists = false;
function fetchVehicleDetails(vehicleNumber, formType) {
    if (vehicleNumber.length == 0) {
        document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = "";
        return;
    }
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Parse the JSON response
            var response = JSON.parse(this.responseText);
            var vehicleNameField = document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end");
            var errorMessageField = document.getElementById('start-error-message'); // Assuming you have an error message element

            // Check if the response indicates no vehicle found
            if (response.v_name === 'No vehicle found') {
                vehicleNameField.value = ""; // Clear the vehicle name field
                errorMessageField.textContent = "No vehicle found"; // Set the error message
                errorMessageField.classList.add('error-visible'); // Optionally add a class to style the error message
                errorMessageField.style.display = "block"; // Show the error message

                vehicleexists = false;

            } 
            
            else if(response.status == 1){
        alert("This car is currently working. Choose another car.");
        document.getElementById(formType === 'start' ? "v_id_start" : "v_id_end").value = "";
console.log("this is the response" + response.status)
            }
            else {
              vehicleexists = true;
                vehicleNameField.value = response.v_name; // Set the vehicle name
                errorMessageField.style.display = "none"; // Show the error message

                errorMessageField.textContent = ""; // Clear any previous error messages
            }
        }
    };
    
    xmlhttp.open("GET", "getVehicleDetails.php?v_id=" + vehicleNumber, true);
    xmlhttp.send();
}




function validateStartForm() {
    // Call the validatePhoto function
    const isPhotoValid = validatePhoto('start');
        
    // Handle 500 km distance validation
    if (!vehicleexists) {
        alert("Please enter correct vehicle number or add through admin");
        return false; // Stop form submission
    } 

    // Only return true if all validations are true
    return isPhotoValid && isEndKmValid;
}




  </script>
</body>

</html>

