<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/blog.min.css">
</head>
<body>
    <?php
    require_once 'header.php'
    //if(isset)


    ?>
    <div class="container bb-1">
        <div class="row mt-3">
            <div class="col">
                <?php
                $req_post = 'SELECT COUNT(*) AS nbposts FROM `post` WHERE `blog_id`=' . $_SESSION['blogid'];
                $res_post = $conx->requete($req_post, 'fetch');
                $article_label = $res_post['nbposts'] > 1 ? ' articles.' : ' article.';
                ?>
                <?= $current_blog_logo;?>
                <h1 class="d-inline-flex">
                    Blog <?= $current_blog_title . ' : ' . $res_post['nbposts'] . $article_label ?>
                </h1>
                <a href="newpost.php?blogid=<?= $_SESSION['blogid']; ?>" class="btn btn-sm btn-primary">Nouveau post</a>

            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <?php
                $req_posts = 'SELECT p.id,p.title AS postitle, FROM_UNIXTIME(p.date_post, GET_FORMAT(DATE,"EUR")) as datePost, u.firstname, u.lastname  FROM `post` p
                    INNER JOIN `user` u ON p.author = u.id
                    WHERE `blog_id`=' . $_SESSION['blogid'];
                $res_posts = $conx->requete($req_posts);
                $content = '';
                foreach($res_posts as $post) {
                    $content .= '<div class="card mb-3">';
                    $content .= '<div class="card-body">';
                    $content .= '<h5 class="card-title">' . $post['postitle'] . '</h5>';
                    $content .= '<p class="card-text">' . $post['datePost'] . '</p>';

                    // Sélection des tags du post
                    $req_tags = 'SELECT `name` FROM `tag` t
                        INNER JOIN `post_tag_mm` mm ON t.`id` = mm.`id_tag`
                        INNER JOIN `post` p ON p.`id` = mm.`id_post`
                        WHERE p.`id`=' . $post['id'];
                    $res_tags = $conx->requete($req_tags);
                    foreach($res_tags as $tag) {
                        $content .= '<span class="badge text-bg-warning">#' . $tag['name'] . '</span>&nbsp;';
                    }
                    $content .= '<hr class="discreet" /><a href="detail.php?blogid=' . $_SESSION['blogid'] . '&postid=' . $post['id'] . '" class="btn btn-primary btn-sm">consulter</a>';
                    $content .= '</div></div>';
                }
                echo $content;
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

