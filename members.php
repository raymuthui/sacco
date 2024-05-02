<?php include './db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><strong>Members</strong></h4>
                </div>
                <div class="card-body">
                    <table class="table-striped table-bordered col-md-12">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $members = $conn->query("SELECT *, CONCAT(lastname, ' ', middlename, ' ', firstname) AS full_name FROM members ORDER BY lastname ASC");
                            $i = 1;
                            while ($row = $members->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $row['full_name'] ?></td>
                                    <td>
                                        <?php
                                        if ($row['status'] == 0) {
                                            echo 'Pending';
                                        } elseif ($row['status'] == 1) {
                                            echo 'Approved';
                                        } else {
                                            echo 'Unknown';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <center>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <?php if ($row['status'] == 0) : ?>
                                                        <a class="dropdown-item approve_member" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Approve</a>
                                                    <?php endif; ?>
                                                    <a class="dropdown-item edit_member" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_member" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                                                </div>
                                            </div>
                                        </center>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function delete_member(member_id) {
            start_load()
            $.ajax({
                url: 'ajax.php?action=delete_member',
                method: 'POST',
                data: {
                    id: member_id
                },
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast('Member successfully deleted');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast('Failed to delete member. Please try again', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while processing your request. Please try again later.');
                }
            });
        }
        $(document).ready(function() {
            $('.approve_member').click(function() {
                var member_id = $(this).attr('data-id');
                approveMember(member_id);
            });

            function approveMember(member_id) {
                $.ajax({
                    url: 'ajax.php?action=approve_member',
                    method: 'POST',
                    data: {
                        id: member_id
                    },
                    success: function(resp) {
                        if (resp == 1) {
                            alert_toast('Member approved successfully.');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            alert_toast('Approval failed. Please try again.', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert_toast('An error occurred while processing your request. Please try again later.', 'danger');
                    }
                });
            }

            $('.edit_member').click(function() {
                var member_id = $(this).attr('data-id');
                editMember(member_id);
            });

            function editMember(member_id) {
                // Open the modal for editing member details using uni_modal function
                uni_modal('Edit Member', 'manage_member.php?id=' + member_id);
            }

            $('.delete_member').click(function() {
                var member_id = $(this).attr('data-id');
                _conf('Are you sure to delete this member?', 'delete_member', [member_id]);
            });
        });
    </script>

</body>

</html>