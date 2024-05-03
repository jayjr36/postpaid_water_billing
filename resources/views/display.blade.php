<!-- readings.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Meter Readings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Water Meter Readings</h1>
        <div class="row">
            <div class="col-md-6">
                <form id="addReadingForm">
                    <div class="form-group">
                        <label for="meter_id">Meter ID:</label>
                        <input type="text" class="form-control" id="meter_id" name="meter_id" required>
                    </div>
                    <div class="form-group">
                        <label for="reading">Reading:</label>
                        <input type="number" class="form-control" id="reading" name="reading" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Reading</button>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-bordered" id="readings-table">
            <thead>
                <tr>
                    <th>Meter ID</th>
                    <th>Reading</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Submit new reading
            $('#addReadingForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '/store-reading',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Clear form fields
                            $('#meter_id').val('');
                            $('#reading').val('');
                            // Fetch and display readings
                            fetchReadings();
                        } else {
                            alert('Failed to add reading.');
                        }
                    }
                });
            });

            // Function to fetch and display readings in real-time
            function fetchReadings() {
                fetch('/readings')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = $('#readings-table tbody');
                        tableBody.empty(); // Clear existing rows
                        data.forEach(reading => {
                            const row = `<tr>
                                <td>${reading.meter_id}</td>
                                <td>${reading.reading}</td>
                                <td>${reading.created_at}</td>
                            </tr>`;
                            tableBody.append(row);
                        });
                    });
            }

            // Fetch readings every 5 seconds (adjust as needed)
            setInterval(fetchReadings, 5000);

            // Call fetchReadings initially to load readings on page load
            fetchReadings();
        });
    </script>
</body>
</html>
