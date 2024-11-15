<?php include ('../includes-admin/connection.php'); ?>
<?php include ('../includes-admin/headers-admin.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="header-container text-center">
                <h4>Site Details</h4>
                <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal"
                        class="btn btn-primary">Add Site</a>
                </div>
            </div>
            <hr>
            <table id="example" class="table">
                <thead>
                    <th>ID</th>
                    <th>Site Name</th>
                    <th>Action</th>
                    <th>Project Name</th> <!-- Add this line for project name -->
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include ('../includes-admin/footers-admin.php'); ?>
<script type="text/javascript">
$(document).ready(function () {
    // Fetch projects and populate the dropdown for adding a user
    $.ajax({
        url: 'fetch_projects.php',
        type: 'get',
        success: function (data) {
            console.log('Projects fetched:', data); // Debugging line
            var projects = JSON.parse(data);
            var options = '<option value="">Select Project</option>';
            projects.forEach(function (project) {
                options += '<option value="' + project.project_id + '">' + project.project_name + '</option>';
            });
            $('#addProjectName').html(options);
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch projects:', error);
        }
    });

    // Initialize DataTable
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
            { data: '0' }, // site_id
            { data: '1' }, // site_name
            { data: '2' }, // project_name
            {
                "render": function (data, type, full, meta) {
                    return '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-secondary btn-sm editbtn">Edit</a> ' +
                        '<a href="javascript:void();" data-id="' + full[0] + '" class="btn btn-danger btn-sm deleteBtn">Delete</a>';
                },
                "targets": 3 // Action column
            }
        ]
    });

    // Submit form to add a user
    $(document).on('submit', '#addUser', function (e) {
        e.preventDefault();
        var site_name = $('#addSiteName').val();
        var project_id = $('#addProjectName').val();

        if (site_name !== '' && project_id !== '') {
            $.ajax({
                url: "add_user.php",
                type: "post",
                data: {
                    site_name: site_name,
                    project_id: project_id
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

    // Fetch projects and populate the dropdown for updating a user
    function fetchProjectsForUpdate() {
        return $.ajax({
            url: 'fetch_projects.php',
            type: 'get'
        });
    }

    // Handle the edit button click
    $('#example').on('click', '.editbtn', function (event) {
        var table = $('#example').DataTable();
        var trid = $(this).closest('tr').attr('id');
        var id = $(this).data('id');

        // Show the modal
        $('#exampleModal').modal('show');

        // Fetch projects and populate the dropdown
        fetchProjectsForUpdate().done(function (data) {
            console.log('Projects fetched for update:', data); // Debugging line
            var projects = JSON.parse(data);
            var options = '<option value="">Select Project</option>';
            projects.forEach(function (project) {
                options += '<option value="' + project.project_id + '">' + project.project_name + '</option>';
            });
            $('#updateProjectName').html(options);

            // Send AJAX request to get data for the selected user
            $.ajax({
                url: 'get_single_data.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json', // Ensure response is parsed as JSON
                success: function (response) {
                    $('#site_id').val(response.site_id); // Assuming response contains site_id
                    $('#nameField').val(response.site_name); // Populate name field
                    $('#updateProjectName').val(response.project_id); // Populate project dropdown
                    $('#trid').val(trid); // Store trid for later use if needed
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }).fail(function (xhr, status, error) {
            console.error('Failed to fetch projects:', error);
        });
    });

    // Submit form to update a user
    $(document).on('submit', '#updateUser', function (e) {
        e.preventDefault();
        var site_name = $('#nameField').val();
        var id = $('#site_id').val();
        var project_id = $('#updateProjectName').val();

        // Validate if necessary fields are filled
        if (site_name !== '' && id !== '' && project_id !== '') {
            $.ajax({
                url: "update_user.php",
                type: "post",
                data: {
                    site_name: site_name,
                    id: id,
                    project_id: project_id
                },
                success: function (data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status === 'true') {
                        // Update the DataTable row with new data
                        var table = $('#example').DataTable();
                        var trid = $('#trid').val();
                        var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-secondary btn-sm editbtn">Edit</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
                        var newRowData = [id, site_name, project_id, button];
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

    $(document).on('click', '.deleteBtn', function (event) {
        var table = $('#example').DataTable();
        event.preventDefault();
        var site_id = $(this).data('id');
        if (confirm("Are you sure you want to delete this Site?")) {
            $.ajax({
                url: "delete_user.php",
                data: { site_id: site_id },
                type: "post",
                success: function (data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'success') {
                        table.row($("#" + site_id).closest('tr')).remove().draw();
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
});

</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Site</h5>
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
                        <label for="site_id" class="col-md-3 form-label">Site ID</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="site_id" name="site_id" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="updateProjectName" class="col-md-3 form-label">Project</label>
                        <div class="col-md-9">
                            <select class="form-control" id="updateProjectName" name="project_id"></select>
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
                <h5 class="modal-title" id="exampleModalLabel">Add Site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUser" action="">
                    <div class="mb-3 row">
                        <label for="addSiteName" class="col-md-3 form-label">Site Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="addSiteName" name="site_name">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="addProjectName" class="col-md-3 form-label">Project Name</label>
                        <div class="col-md-9">
                            <select class="form-control" id="addProjectName" name="project_id">
                                <!-- Options will be populated dynamically using JavaScript -->
                            </select>
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