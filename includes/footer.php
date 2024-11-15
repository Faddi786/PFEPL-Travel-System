<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- <script>
    $(document).ready(function () {
        $('#employeeTable').DataTable({
            "pagingType": "simple_numbers",
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
            "searching": true,
            "ordering": false,
            "info": true
        });
    });
</script> -->
<script>
    function openModal(src) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("modalImage");
        modal.style.display = "block";
        modalImg.src = src;
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    function handleAction(id) {
        // Handle the action button click here
        // For demonstration, we'll just alert the ID
        alert("Row ID: " + id);
    }
</script>
<!-- JavaScript for delete confirmation and handling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attach click event to all delete buttons
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = button.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this record?")) {
                // User confirmed, proceed with deletion
                deleteRecord(id);
            }
        });
    });

    // Function to send AJAX request to delete record
    function deleteRecord(id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request successful
                    var response = xhr.responseText;
                    alert(response); // Show success message
                    // location.reload(); // Reload the page to reflect changes
                } else {
                    // Request failed
                    alert('Error: ' + xhr.status);
                }
            }
        };

        // Send POST request to delete_row.php (or your delete script)
        xhr.open('POST', 'delete_row.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + id); // Send ID of the row to be deleted
    }
});
</script>

</body>

</html>