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
            <div class="col">
                <h1 class="d-inline-flex">Créer un nouveau Blog</h1>
                <a href="<?= $_SESSION['default_url']; ?>?blogid=<?= $_SESSION['blogid']; ?>" class="btn btn-sm btn-primary">Annuler</a>
            </div>
        </div>
    </div>
    <?php require_once 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
