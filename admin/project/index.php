<?php include ('../includes-admin/connection.php');?>
<?php include('../includes-admin/headers-admin.php');?>

    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="header-container text-center">
                    <h4>Project Details</h4>
                    <div class="btnAdd">
                        <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal"
                            class="btn btn-primary">Add Project</a>
                    </div>
                </div>
                <hr>
                <table id="example" class="table">
                    <thead>
                        <th>Project Name</th>
                        <th>Action</th>
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
                    'url': 'fetch_data.php',
                    'type': 'post',
                },
                'columns': [
                    { data: '1' }, // project_name
                    {
                        "render": function (data, type, full, meta) {
                            return '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-secondary btn-sm editbtn">Edit</a> ' +
                                '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-danger btn-sm deleteBtn">Delete</a>';
                        },
                        "targets": 2 // Action column
                    }
                ]
            });
        });
        $(document).on('submit', '#addUser', function (e) {
            e.preventDefault();
            var project_id = $('#addProjectID').val();
            var project_name = $('#addProjectName').val();

            if (project_id !== '' && project_name !== '') {
                $.ajax({
                    url: "add_user.php",
                    type: "post",
                    data: {
                        project_id: project_id,
                        project_name: project_name
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
            var id = $('#project_id').val();

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
                    $('#project_id').val(response.project_id); // Assuming response contains project_id
                    $('#nameField').val(response.project_name); // Populate name field
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
            var project_id = $(this).data('id');
            if (confirm("Are you sure you want to delete this Project?")) {
                $.ajax({
                    url: "delete_user.php",
                    data: { project_id: project_id },
                    type: "post",
                    success: function (data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'success') {
                            table.row($("#" + project_id).closest('tr')).remove().draw();
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
                    <h5 class="modal-title" id="exampleModalLabel">Update Project</h5>
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
                            <label for="project_id" class="col-md-3 form-label">Project ID</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="project_id" name="project_id" readonly>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUser" action="">
                        <!-- <div class="mb-3 row">
                            <label for="addProjectID" class="col-md-3 form-label">Project ID</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addProjectID" name="project_id">
                            </div>
                        </div> -->
                        <div class="mb-3 row">
                            <label for="addProjectName" class="col-md-3 form-label">Project Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addProjectName" name="project_name">
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
<script src="https://kit.fontawesome.com/4bbef46563.js" crossorigin="anonymous"></script>
