<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doecker :: PHP, MySQL & Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">


                <div class="card">
                    <div class="card-header">
                        Docker :: PHP project, data read from MySQL table
                    </div>
                    <div class="card-body shadow p-3 bg-body-tertiary rounded">
                        <table class="table table-striped ">
                            <?php
                            include_once "./src/core/database.php";

                            // Usage example
                            $db = Database::getInstance();

                            // Read
                            $blogs = $db->read('blogs');

                            foreach ($blogs as $blog) {
                            ?>
                                <tr>
                                    <td><?= $blog['id'] ?></td>
                                    <td><?= $blog['title'] ?></td>
                                    <td><?= $blog['details'] ?></td>
                                    <td><?= $blog['date'] ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                            <button type="button" class="btn btn-danger">Left</button>
                                            <button type="button" class="btn btn-warning">Middle</button>
                                            <button type="button" class="btn btn-success">Right</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>