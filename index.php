<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
    <title>Hello, world!</title>
</head>
<body>
<div class="container">
    <form id="image_form"
          method="post"
          enctype="multipart/form-data"
          action="/proc/upload-image.php">
        <div class="form-group">
            <label for="image">이미지</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="image" id="image" onchange="uploadFile()">
                <label class="custom-file-label" for="image">Choose file</label>
            </div>
        </div>
    </form>


    <div class="form-group">
        <img src="" id="cropped_image">
        <input type="text" id="cropped_image_src" class="form-control">
    </div>
</div>


<div class="modal fade" id="image_cropper" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="image_cropper_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveImage()">저장</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script src="/js/jquery.Jcrop.min.js"></script>
<script>
    var jcrop;
    var imageFileName;

    function uploadFile() {
        var $form = $('#image_form');

        $form.ajaxSubmit({
            success: function(response) {
                var json = JSON.parse(response);

                if (json.result == 'success') {
                    var $img = $('<img style="max-width: 100%;">');

                    $img.attr('src', json.filename);
                    $img.addClass('modal-image');

                    $img.appendTo($('#image_cropper_body'));

                    imageFileName = json.filename;

                    $('#image_cropper').modal();
                }
            }
        });
    }

    $(function() {
        $('#image_cropper').on('shown.bs.modal', function() {
            $(this).find('img').Jcrop({
                boxWidth: $('#image_cropper_body').width()
            }, function() {
                jcrop = this;
            });
        });
    });

    function saveImage() {
        var bounds = jcrop.getBounds();
        var select = jcrop.tellSelect();

        $.ajax({
            url: '/proc/crop.php',
            data: {
                bound: bounds,
                image: imageFileName,
                select: select
            },
            type: 'post',
            success: function(response) {
                var json = JSON.parse(response);

                $('#cropped_image').attr('src', json.filename);
                $('#cropped_image_src').val(json.filename);

                $('#image_cropper').modal('hide');
            }
        })
    }
</script>
</body>
</html>