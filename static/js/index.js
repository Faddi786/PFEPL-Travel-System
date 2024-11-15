function getLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(callback, showError);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function showError(error) {
    switch(error.code) {
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
            empIDDropdown.innerHTML = "<option value=''>Select Employee ID</option>";
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


function fetchVehicleDetails(vehicleNumber) {
    if (vehicleNumber.length == 0) {
        document.getElementById("v_name").value = "";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("v_name").value = this.responseText;
        }
    };
    xmlhttp.open("GET", "getVehicleDetails.php?v_id=" + vehicleNumber, true);
    xmlhttp.send();
}

function capturePhoto(inputId) {
    const video = document.createElement('video');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    const constraints = {
        video: {
            width: { ideal: 1280 },
            height: { ideal: 720 }
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then((stream) => {
            video.srcObject = stream;
            video.play();

            document.body.appendChild(video); // For preview

            setTimeout(() => {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const dataURL = canvas.toDataURL('image/png');
                document.getElementById(inputId).value = dataURL;

                video.pause();
                video.srcObject.getTracks().forEach(track => track.stop());
                document.body.removeChild(video);
            }, 3000); // Capture after 3 seconds
        })
        .catch((error) => {
            console.error('Error accessing media devices.', error);
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

document.addEventListener('DOMContentLoaded', function() {
    setStartLocation();
    setEndLocation();
});
