
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

    function fetchVehicleDetails(vehicleNumber, formType) {
      if (vehicleNumber.length == 0) {
        document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = "";
        return;
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = this.responseText;
        }
      };
      xmlhttp.open("GET", "getVehicleDetails.php?v_id=" + vehicleNumber, true);
      xmlhttp.send();
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

    // Function to fetch site details based on the selected project
    function fetchSiteDetails(projectId) {
      console.log("Fetching sites for project ID:", projectId);

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          try {
            var sites = JSON.parse(this.responseText);
            console.log("Sites fetched:", sites);

            var siteDropdown = document.getElementById("sites");
            siteDropdown.innerHTML = "<option value=''>Select Site</option>";

            sites.forEach(function(site) {
              var option = document.createElement("option");
              option.value = site.site_id; // Assuming your site_id is named site_id
              option.text = site.site_name;
              siteDropdown.add(option);
            });
          } catch (e) {
            console.error("Error parsing JSON: ", e);
            console.log("Response Text: ", this.responseText);
          }
        }
      };
      xmlhttp.open("GET", "getSites.php?project_id=" + projectId, true);
      xmlhttp.send();
    }

    // Event listener for project dropdown change
    document.getElementById("project").addEventListener("change", function() {
      var projectId = this.value; // Get the selected project ID
      console.log("Selected Project ID:", projectId);
      fetchSiteDetails(projectId); // Pass the project ID to fetch site details
    });

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

                // Display the image in the form
                const img = document.createElement('img');
                img.src = data.imagePath;
                img.style.width = '100px';
                img.style.height = 'auto';
                // Assuming ${formType}PhotoContainer is the ID of the container where you want to display the image
                const container = document.getElementById(`${formType}PhotoContainer`);
                container.innerHTML = ''; // Clear existing content
                container.appendChild(img);
              } else {
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


    function fetchVehicleDetails(vehicleNumber, formType) {
  console.log('Enter : ' + vehicleNumber);
  
  if (vehicleNumber.length == 0) {
    document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = "";
    return;
  }
  
  var xmlhttp = new XMLHttpRequest();
  
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Parse the JSON response from the PHP
      var response = JSON.parse(this.responseText);
      

      
      // Check the vehicle status, and alert if the status is 0
      if (response.status == 1) {
        alert("This car is currently working. Choose another car.");
        document.getElementById(formType === 'start' ? "v_id_start" : "v_id_end").value = "";

      }
      else
      {
              // Set the vehicle name in the respective input field
      document.getElementById(formType === 'start' ? "v_name_start" : "v_name_end").value = response.v_name;
      }
    }
  };
  
  // Send request to the PHP script
  xmlhttp.open("GET", "getVehicleDetails.php?v_id=" + vehicleNumber, true);
  xmlhttp.send();
}








function validateForm() {

    // Call the validatePhoto function
    const isPhotoValid = validatePhoto('end');
    
    // Call the validateEndKilometer function
    const isEndKmValid = validateEndKilometer();

    // Call the validateEndKilometer function
    const is500distancevalid = check500distance();

    // Call the validateEndKilometer function
    const is300distancevalid = check300distance();

    if(!isEndKmValid){
      alert("Please enter proper end kilometer")
    } 
    
    if(!isEndKmValid){
  alert("Please enter proper end kilometer")
} else if (is300distancevalid){
      alert("Bhai dekh pakka??")
      return false;
    } else if (is500distancevalid){
      alert("diwane ho kya??")
      return false;
    } 

    // Only return true if both validations are true
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

if (distance>300){
  return true;
}else{
  return false;
}

}



function validateEndKilometer() {
    let endKilometer = document.getElementById('end_kilometer').value;
    let errorMessage = document.getElementById('error-message');
    
    // Assuming startKilometer is defined elsewhere in your code
    startkmlength = startKilometer.toString().length;

    console.log(`start kilometer : ${startkmlength}`)

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
            return false;
        }

        // Check if the number of digits in end_kilometer matches start_kilometer
        if (endKilometer.toString().length !== startkmlength) {
            errorMessage.textContent = "No. of digits must be " + startkmlength;
            errorMessage.classList.add('error-visible'); // Show the error message
            return false;
        }
    } else {
        errorMessage.textContent = "Please enter a valid number.";
        errorMessage.classList.add('error-visible'); // Show the error message
        return false;
    }

    // If everything is okay, hide the error message
    // errorMessage.classList.remove('error-visible'); // Ensure error message is hidden
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