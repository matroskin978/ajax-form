<?php

// https://learn.javascript.ru/formdata

function dump($data)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ((!empty($_FILES) && $_FILES['file']['error'] === 0) && !empty($_POST['sign'])) {
        $filename = $_FILES['file']['name'];
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
            $res = ['answer' => 'ok', 'file' => "<img src='{$filename}' alt=''>", 'sign' => $_POST['sign']];
        }
    } else {
        $res = ['answer' => 'error', 'file' => "File is required", 'sign' =>  "Sign is required"];
    }
    echo json_encode($res);
    die;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        .card-body img {
            max-width: 150px;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form id="form" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input class="form-control" type="file" name="file" id="file">
                </div>

                <div class="mb-3">
                    <label for="sign" class="form-label">Sign</label>
                    <input class="form-control" type="text" name="sign" id="sign">
                </div>

                <button class="btn btn-primary" id="send">Send</button>
            </form>

            <div class="card mt-5">
                <div class="card-body" id="res">
                    <h5 class="card-title mb-3 border-bottom">Uploaded Images</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    const form = document.getElementById('form');
    const btn = document.getElementById('send');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        btn.textContent = 'Sending...';
        btn.disabled = true;
        fetch('index.php', {
            method: 'POST',
            body: new FormData(form)
        })
            .then((response) => response.json())
            .then((data) => {
                setTimeout(() => {
                    if (data.answer === 'ok') {
                        document.getElementById('res').insertAdjacentHTML('beforeend', `<div style="float: left">${data.file}<p>${data.sign}</p></div>`);
                        form.reset();
                    } else {
                        alert(data.file + "\n" + data.sign);
                    }
                    btn.textContent = 'Send';
                    btn.disabled = false;
                }, 1000);

            });
    });

</script>

</body>

</html>