@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-dark text-white" style="height: 100vh">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="container">
                        <h3 class="text-center">Water Meter Readings</h3>

                        <hr>
                        <table class="table table-bordered" id="readings-table">
                            <thead>
                                <tr>
                                    <th>Meter ID</th>
                                    <th>Reading</th>
                                    <th>Bill</th>
                                    <th>Amount Paid</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be dynamically added here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            MAKE PAYMENT
                        </button>
                    </div>

                    <!-- Payment Modal -->
                    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
                        aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="paymentForm">
                                        <div class="form-group">
                                            <label for="paymentAmount">Meter ID:</label>
                                            <input type="number" class="form-control" id="meterId" name="meterId"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentAmount">Enter Amount to Pay:</label>
                                            <input type="number" class="form-control" id="paymentAmount"
                                                name="paymentAmount" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">SUBMIT</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                    <script>
                        $('#paymentForm').submit(function(event) {
                            event.preventDefault();
                            // Fetch balance from user table
                            const paymentAmount = parseFloat($('#paymentAmount').val());
                            const meterId = parseFloat($('#meterId').val()); // Corrected to fetch meterId value
                    
                            const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    
                            $.ajax({
                                type: 'POST',
                                url: '/process-payment',
                                data: {
                                    _token: csrfToken, // Include CSRF token in the request data
                                    meterId: meterId,
                                    amount: paymentAmount
                                },
                                success: function(response) {
                                    if (response.success) {
                                        alert('Payment processed successfully.');
                                        $('#paymentModal').modal('hide');
                                        fetchReadings();
                                    } else {
                                        alert('Payment failed.');
                                    }
                                }
                            });
                        });
                    </script>
                    
                    <script>
                        function fetchReadings() {
                            fetch('/readings')
                                .then(response => response.json())
                                .then(data => {
                                    const tableBody = $('#readings-table tbody');
                                    tableBody.empty();
                                    data.forEach(reading => {
                                        const row = `<tr>
                                            <td>${reading.meter_id}</td>
                                            <td>${reading.reading}</td>
                                            <td>${reading.bill}</td>
                                            <td>${reading.due_amount}</td>
                                            <td>${reading.created_at}</td>
                                        </tr>`;
                                        tableBody.append(row);
                                    });
                                });
                        }
                        setInterval(fetchReadings, 5000);
                        fetchReadings();
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
