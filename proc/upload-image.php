<?php
if (isset($_FILES['image'])) {
    $filename =
        date('YmdHisu') .
        '.' .
        pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION );
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../files/' . $filename);
    ?>
    {
    "result": "success",
    "filename": "/files/<?php echo $filename ?>"
    }
    <?php
} else {
    ?>
    {
    "result": "failed"
    }
    <?php
}
?>
