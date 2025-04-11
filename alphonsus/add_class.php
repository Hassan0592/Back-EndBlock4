<?php
include 'config.php'; // Include the database configuration file This is all My code. bootstrap has been used and google graphs.
include 'header.php'; // Include the header file for the page layout
?>

<div class="container mt-5">
    <h2 class="mb-4">Add New Class</h2>

    <!-- Button to navigate back to the list of classes -->
    <div class="mb-3 text-end">
        <a href="view_classes.php" class="btn btn-primary">View Classes</a>
    </div>

    <!-- Form to add a new class -->
    <form action="save_class.php" method="POST">
        <!-- Input for the class name -->
        <div class="mb-3">
            <label for="class_name" class="form-label">Class Name</label>
            <input type="text" class="form-control" name="class_name" required>
        </div>

        <!-- Input for the class type (e.g., Year 1, Reception) -->
        <div class="mb-3">
            <label for="class_type" class="form-label">Class Type</label>
            <input type="text" class="form-control" name="class_type" placeholder="e.g., Year 1, Reception">
        </div>

        <!-- Input for the class capacity with a maximum limit of 15 -->
        <div class="mb-3">
            <label for="capacity" class="form-label">Class Capacity (Max 15)</label>
            <input type="number" class="form-control" name="capacity" min="1" max="15" required>
        </div>

        <!-- Optional notes field for additional information about the class -->
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (optional)</label>
            <textarea class="form-control" name="notes"></textarea>
        </div>

        <!-- Submit button to add the class -->
        <button type="submit" class="btn btn-success">Add Class</button>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>
