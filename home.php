<?php include 'db_connect.php' ?>
<style>

</style>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="card dashboard p-4">
            <hr style="padding: 10px;">
            <div class="row ml-2 mr-2">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75 ">Payments Today</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        $payments = $conn->query("SELECT sum(amount) as total FROM payments where date(date_created) = '" . date("Y-m-d") . "'");
                                        echo $payments->num_rows > 0 ? number_format($payments->fetch_array()['total'], 2) : "0.00";
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class=" text-white stretched-link" href="index.php?page=payments">View Payments</a>
                            <div class=" text-white">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75 ">Members</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        $members = $conn->query("SELECT * FROM members");
                                        echo $members->num_rows > 0 ? $members->num_rows : "0";
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class=" text-white stretched-link" href="index.php?page=members">View Members</a>
                            <div class=" text-white">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-danger text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75 ">Borrowers</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        // Assuming $conn is your database connection object
                                        $borrowers_query = $conn->query("SELECT COUNT(DISTINCT member_id) AS num_borrowers FROM loan_list");
                                        if ($borrowers_query) {
                                            $borrowers_data = $borrowers_query->fetch_assoc();
                                            $num_borrowers = $borrowers_data['num_borrowers'];
                                            echo $num_borrowers;
                                        } else {
                                            echo "Error fetching data.";
                                        }
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class=" text-white stretched-link" href="index.php?page=borrowers">View Borrowers</a>
                            <div class=" text-white">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-warning text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75 ">Active Loans</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        $loans = $conn->query("SELECT * FROM loan_list where status = 2");
                                        echo $loans->num_rows > 0 ? $loans->num_rows : "0";
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="text-white stretched-link" href="index.php?page=loans">View Loan List</a>
                            <div class=" text-white">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75 ">Total Receivable</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        $payments = $conn->query("SELECT sum(amount - penalty_amount) as total FROM payments where date(date_created) = '" . date("Y-m-d") . "'");
                                        $loans = $conn->query("SELECT sum(l.amount + (l.amount * (t.interest_percentage/100))) as total FROM loan_list l inner join loan_types t on t.id = l.loan_type_id where l.status = 2");
                                        $loans =  $loans->num_rows > 0 ? $loans->fetch_array()['total'] : "0";
                                        $payments =  $payments->num_rows > 0 ? $payments->fetch_array()['total'] : "0";
                                        echo number_format($loans - $payments, 2);
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="text-white stretched-link" href="index.php?page=loans">View Loan List</a>
                            <div class="text-white">

                            </div>
                        </div>
                    </div>
                </div>

                  <div class="col-md-4">
                    <div class="card bg-info text-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-3">
                                    <div class="text-white-75">Total Savings</div>
                                    <div class="text-lg font-weight-bold">
                                        <?php
                                        $total_savings_query = $conn->query("SELECT SUM(balance) AS total_savings FROM savings_account");
                                        if ($total_savings_query) {
                                            $total_savings_data = $total_savings_query->fetch_assoc();
                                            $total_savings = $total_savings_data['total_savings'];
                                            echo number_format($total_savings, 2);
                                        } else {
                                            echo "Error fetching data.";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="text-white stretched-link" href="index.php?page=savings">View Savings List</a>
                            <div class="text-white"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>