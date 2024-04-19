<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-news">
                    <div class="card">
                        <div class="card-header">
                            News Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label for="article_image" class="control-label">Image</label>
                                <input type="file" id="article_image" name="article_image" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Title</label>
                                <input type="text" name="article_title" id="article_title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Content</label>
                                <textarea name="article_content" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary col-sm-4 offset-md-2"> Save</button>
                                    <button class="btn btn-default col-sm-4" type="button" onclick="_reset()"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $baseurl = "http://localhost/sacco";
                                $i = 1;
                                $types = $conn->query("SELECT * FROM news order by id asc");
                                while ($row = $types->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td>
                                            <div><img width="100px" height="100px" src="<?php echo $baseurl . '/' . $row['article_image_path'] ?>" alt=""></div>
                                        </td>
                                        <td class="">
                                            <p>Article Title: <b><?php echo $row['article_title'] ?></b></p>
                                            <p>Article Content: <b><?php echo $row['article_content'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary edit_news" type="button" data-id="<?php echo $row['id'] ?>" data-article_image="<?php echo $row['article_image_path'] ?>" data-article_title="<?php echo $row['article_title'] ?>" data-article_content="<?php echo $row['article_content'] ?>"><i class="fa fa-edit"></i></button>
                                            <button class="btn  btn-danger delete_news" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>
<style>
    td {
        vertical-align: middle !important;
    }

    td p {
        margin: unset;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        text-wrap: wrap; /* Add word-wrap property to enable word wrapping */
    }

    .read-more {
        color: blue;
        cursor: pointer;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function delete_news($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_news',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }
    $(document).ready(function() {
        function _reset() {
            $('[name="id"]').val('');
            $('#manage-news').get(0).reset();
        }

        $('#manage-news').submit(function(e) {
            e.preventDefault()
            start_load()
            $.ajax({
                url: 'ajax.php?action=save_news',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully added", 'success')
                        setTimeout(function() {
                            location.reload()
                        }, 1500)

                    } else if (resp == 2) {
                        alert_toast("Data successfully updated", 'success')
                        setTimeout(function() {
                            location.reload()
                        }, 1500)

                    }
                }
            })
        })
        $('.edit_news').click(function() {
            start_load()
            var cat = $('#manage-news')
            cat.get(0).reset()
            cat.find("[name='id']").val($(this).attr('data-id'))
            cat.find("[name='article_title']").val($(this).attr('data-article_title'))
            cat.find("[name='article_content']").val($(this).attr('data-article_content'))
            end_load()
        })

        $('.delete_news').click(function() {
            _conf("Are you sure to delete this Loan Type?", "delete_news", [$(this).attr('data-id')])
        })

        function displayImg(input, _this) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#cimg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

    });
</script>