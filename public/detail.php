<?php
    require_once '../App/Models/Comment.php';
    use SYRADEV\App\Models\Comment;
    global $conx, $conf;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Article</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/blog.min.css">
</head>

<body>
    <?php
    require_once 'header.php';
    // Si le formulaire de création d'un commentaire vient d'être posté
    if(isset($_POST['saveNewComment']) && empty($_POST['escobar'])) {
        $comment = new Comment($_POST);
        $conx->inserer('comment', $comment);
    }
    // Si seulement l'id du post est transmis dans l'url
    if(isset($_GET['postid']) && !empty($_GET['postid'])) {
        $postid = $_GET['postid'];

        // On requête les détails de l'article
        $req_post = 'SELECT p.id AS postid, p.title AS postitle, p.content, FROM_UNIXTIME(p.date_post, GET_FORMAT(DATE,"EUR")) as datePost, u.firstname, u.lastname  FROM `post` p
                INNER JOIN `user` u ON p.author = u.id
                WHERE p.`id`=' . $postid;
        $res_post = $conx->requete($req_post, 'fetch');

        // On requête les tags de l'article
        $req_tags = 'SELECT `name` FROM `tag` t
                        INNER JOIN `post_tag_mm` mm ON t.`id` = mm.`id_tag`
                        INNER JOIN `post` p ON p.`id` = mm.`id_post`
                        WHERE p.`id`=' . $postid;
        $res_tags = $conx->requete($req_tags);

        // On requête les commentaires de l'article
        $res_comment = 'SELECT c.content AS comment, FROM_UNIXTIME(c.date_comment, GET_FORMAT(DATE,"EUR")) as dateComment, u.firstname, u.lastname  FROM `comment` c
                INNER JOIN `user` u ON c.author = u.id
                WHERE c.`post_id`=' . $postid;
        $res_comment = $conx->requete($res_comment);
    } else {
        header('Location:' . $conf['defaults']['home_url'] . '?blogid=' . $_SESSION['blogid']);
    }
    ?>

    <!-- Détails de l'article -->
    <div class="container bb-1">
        <div class="row mt-3">
            <div class="col">
                <?=$current_blog_logo;?>
                <h1 class="d-inline-flex">
                    Blog <?=$current_blog_title;?>
                </h1>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>
                            <?= $res_post['postitle']; ?>
                        </h3>
                        <small>le <?= $res_post['datePost']; ?> par <strong><?= $res_post['firstname']; ?> <?= $res_post['lastname']; ?></strong>.</small><br />
                        <?php
                        foreach($res_tags as $tag) {
                            echo '<span class="badge text-bg-warning">#' . $tag['name'] . '</span>&nbsp;';
                        }
                        ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <?= nl2br($res_post['content']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Commentaire -->
        <?php
            if(isset($res_comment) && !empty($res_comment)) {
                foreach($res_comment as $comment) {
        ?>
                <div class="row mt-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3>Commentaire</h3>
                                <small>le <?= $comment['dateComment']; ?> par <strong><?= $comment['firstname']; ?> <?= $comment['lastname']; ?></strong>.</small>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <?= nl2br($comment['comment']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
                }
            }
        ?>

        <!-- Formulaire de Commentaire -->
        <div class="row mt-3 mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                            Si vous souhaitez laisser un commentaire :
                    </div>
                    <div class="card-body">
                        <form action="detail.php?postid=<?= $res_post['postid'] ?>" method="post" autocomplete="off">
                            <div class="form-group">
                                <label class="form-label" for="content">C'est ici : </label>
                                <textarea rows="4" class="form-control" name="content" id="content"></textarea>
                                <input type="hidden" name="post_id" id="post_id" value="<?= $res_post['postid'] ?>">
                                <input type="hidden" name="author" id="author" value="2">
                            </div>
                            <div class="form-group d-none">
                                <label class="form-label" for="escobar"></label>
                                <input name="escobar" id="escobar" value="">
                            </div>
                            <div class="form-group mt-3 mb-3 float-end">
                                <button type="submit" name="saveNewComment" class="btn btn-sm btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 mb-3">
            <div class="col">
                <a href="<?= $_SESSION['default_url'] . '?blogid=' . $_SESSION['blogid']; ?>" class="btn btn-sm btn-primary">Retour à la liste</a>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
