<?php include ('./admin/includes-admin/connection.php'); ?>
<?php include ('includes/header.php'); ?>
<?php include ('./admin/includes-admin/navbar-admin.php'); ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="col-md-6">
        <h3 class="text-center mb-4">Vehicle Usage Report</h3>
        <form action="generate_report.php" method="post" class="mt-3" target="_blank">
            <div class="mb-3">
                <label for="vehicle_number" class="form-label" style="font-size: large;">Vehicle Number</label>
                <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" required style="width: 100%;">
            </div>
            
            <!-- Row for Start Date and End Date (side by side) -->
            <div class="row mb-3">
                <div class="col">
                    <label for="start_date" class="form-label" style="font-size: large;">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required style="width: 100%;">
                </div>
                <div class="col">
                    <label for="end_date" class="form-label" style="font-size: large;">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required style="width: 100%;">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
