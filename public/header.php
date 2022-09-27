<?php
require_once '../App/Utils/Database/Database.php';
require_once '../config/app.php';

use SYRADEV\Utils\Database\PdoDb;

// On se connecte à la base de données
// On notera ici que la classe PdoDb possède un accès statique
$conx = PdoDb::getInstance();

// On sélectionne la liste des blogs
$req_blog = 'SELECT `id`, `title`, `subtitle`, `description`, `logo` FROM `blog` ORDER BY `title`';
$res_blog = $conx->requete($req_blog);

session_start();
$_SESSION['blogid'] = $_GET['blogid'] ?? $conf['defaults']['blog'];
$_SESSION['default_url'] = $conf['defaults']['home_url'];

$current_blog_title = $current_blog_logo = '';
foreach ($res_blog as $blog) {
    if($blog['id']=== (int)$_SESSION['blogid']) {
        $current_blog_title = $blog['title'];
        $current_blog_logo = $blog['logo'];
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col px-0">
            <nav class="navbar navbar-expand-lg navbar-top navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="<?= $_SESSION['default_url'] . '?blogid=' . $_SESSION['blogid']; ?>">Mes blogs</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarBlogs"
                            aria-controls="navbarBlogs" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarBlogs">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="blogs" role="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    Veuilez en sélectionner un...
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="blogs">
                                    <?php
                                    $blogs = '';
                                    foreach ($res_blog as $blog) {
                                        $blogs .= '<li><a class="dropdown-item" href="'. $_SESSION['default_url'] .'?blogid='.$blog['id'].'">' . $blog['title'] . '</a></li>';
                                    }
                                    $blogs .= '<li><a class="dropdown-item" href="newblog.php">Créer un nouveau blog</a></li>';
                                    $blogs .= '<li><a class="dropdown-item" href="Schema.html" target="_blank">Voir le Schéma de la BDD</a></li>';
                                    echo $blogs;
                                    ?>
                                </ul>
                            </li>
                        </ul>

                        <div class="d-flex">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">
                                        &nbsp;<?= $current_blog_title; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
