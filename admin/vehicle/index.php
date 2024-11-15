<?php include ('../includes-admin/connection.php');?>
<?php include('../includes-admin/headers-admin.php');?>

<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="header-container text-center">
                <h4>Vehicle Details</h4>
                <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal"
                        class="btn btn-primary">Add Vehicle</a>
                </div>
            </div>
            <hr>
            <table id="example" class="table">
                <thead>
                    <tr>
                        <th>Vehicle Number</th>
                        <th>Vehicle Name</th>
                        <th>Vehicle Working</th>
                        <th>Person Name</th>  <!-- This will show employee name -->

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('../includes-admin/footers-admin.php');?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable({
            "fnCreatedRow": function (nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': true,
            'processing': true,
            'paging': true,
            'order': [],
            'ajax': {
                'url': 'fetch_data.php',  // Ensure this PHP script returns the employee name
                'type': 'post',
            },
            'columns': [
                { data: '0' }, // Vehicle ID (v_id)
                { data: '1' }, // Vehicle Name (v_name)
                { data: '2' }, // Vehicle Working Status
                { data: '3' }, // Employee Name (emp_name) - Make sure this is the correct index
                {
                    "render": function (data, type, full, meta) {
                        return '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-secondary btn-sm editbtn">Edit</a> ' +
                               '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-danger btn-sm deleteBtn">Delete</a>';
                    },
                    "targets": 4 // This should match your Action column
                }
            ]
        });
    });
        $(document).on('submit', '#addUser', function (e) {
            e.preventDefault();
            var v_id = $('#addVehicleID').val();
            var v_name = $('#addVehicleName').val();

            if (v_id !== '' && v_name !== '') {
                $.ajax({
                    url: "add_user.php",
                    type: "post",
                    data: {
                        v_id: v_id,
                        v_name: v_name
                    },
                    success: function (data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'true') {
                            var mytable = $('#example').DataTable();
                            mytable.draw();
                            $('#addUserModal').modal('hide');
                        } else {
                            alert('Failed to add user');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error:', error);
                        alert('Failed to add user');
                    }
                });
            } else {
                alert('Fill all the required fields');
            }
        });

        $(document).on('submit', '#updateUser', function (e) {
            e.preventDefault();
            var username = $('#nameField').val();
            var id = $('#v_id').val();

            // Validate if necessary fields are filled
            if (username !== '' && id !== '') {
                $.ajax({
                    url: "update_user.php",
                    type: "post",
                    data: {
                        username: username,
                        id: id
                    },
                    success: function (data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status === 'true') {
                            // window.reload();
                        window.location.reload();
                            // Update the DataTable row with new data
                            var table = $('#example').DataTable();
                            var trid = $('#trid').val();
                            var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-secondary btn-sm editbtn">Edit</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
                            var newRowData = [id, username, button];
                            var row = table.row("#" + trid);
                            row.data(newRowData).draw(); // Update the row data and redraw

                            // Close the modal
                            $('#exampleModal').modal('hide');
                        } else {
                            alert('Update failed');
                        }
                    },
                    error: function (xhr, status, error) {
                        window.location.reload();
                        console.error('AJAX error:', error);
                        alert('Failed to update user');
                    }
                });
            } else {
                alert('Fill all the required fields');
            }
        });

        $('#example').on('click', '.editbtn', function (event) {
            var table = $('#example').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            // Show the modal
            $('#exampleModal').modal('show');

            // Send AJAX request to get data for the selected user
            $.ajax({
                url: 'get_single_data.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json', // Ensure response is parsed as JSON
                success: function (response) {
                    $('#v_id').val(response.v_id); // Assuming response contains v_id
                    $('#nameField').val(response.v_name); // Populate name field
                    $('#trid').val(trid); // Store trid for later use if needed
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                    // Handle error scenario (e.g., show an alert)
                }
            });
        });

        $(document).on('click', '.deleteBtn', function (event) {
            var table = $('#example').DataTable();
            event.preventDefault();
            var v_id = $(this).data('id');
            if (confirm("Are you sure you want to delete this Vehicle?")) {
                $.ajax({
                    url: "delete_user.php",
                    data: { v_id: v_id },
                    type: "post",
                    success: function (data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'success') {
                            table.row($("#" + v_id).closest('tr')).remove().draw();
                        } else {
                            alert('Failed to delete user');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error:', error);
                        alert('Failed to delete user');
                    }
                });
            }
        });


    </script>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateUser">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="trid" id="trid" value="">
                        <div class="mb-3 row">
                            <label for="nameField" class="col-md-3 form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nameField" name="name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="v_id" class="col-md-3 form-label">Vehicle Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="v_id" name="v_id" readonly>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add user Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUser" action="">
                        <div class="mb-3 row">
                            <label for="addVehicleID" class="col-md-3 form-label">Vehicle Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addVehicleID" name="v_id">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="addVehicleName" class="col-md-3 form-label">Vehicle name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addVehicleName" name="v_name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </body>