<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau blog</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/blog.min.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <div class="container bb-1">
        <div class="row justify-content-center mt-3">
            <div class="col table-responsive">

                <table class="table table-dark table-hover table-sm table-striped">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <h2>Liste des blogs existants</h2>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($res_blog as $blog) {
                                $blogCells = '<tr>';
                                $blogCells .= '<th>'. $blog['title'] .'</th>';
                                $blogCells .= '<td><a href="'.$_SESSION['default_url'] . '?blogid=' . $blog['id'].'">' . $_SESSION['default_url'] . '?blogid=' . $blog['id'] . '</a></td>';
                                $blogCells .= '</tr>';
                                echo $blogCells;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col">
                <h3 class="d-inline-flex">Créer un nouveau Blog</h3>
                <a href="<?= $_SESSION['default_url']; ?>?blogid=<?= $_SESSION['blogid']; ?>" class="btn btn-sm btn-primary">Annuler</a>
            </div>
        </div>
    </div>
    <?php require_once 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
