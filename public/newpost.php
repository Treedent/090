<?php

require_once '../App/Utils/Php/Outils.php';
require_once '../App/Models/Post.php';
require_once '../App/Models/Tag.php';
require_once '../App/Models/PostTag.php';

use SYRADEV\Utils\Php\Outils;
use SYRADEV\App\Models\Post;
use SYRADEV\App\Models\Tag;
use SYRADEV\App\Models\PostTag;

// On initilaise les variables de champs
$title = $content = '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau post</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/blog.min.css">
    <link rel="stylesheet" href="css/choices.min.css">
</head>
<body>
<?php
require_once 'header.php';

// On récupère les tags
$req_tags = 'SELECT * FROM `tag`';
if (!empty($conx)) {
    $res_tags = $conx->requete($req_tags);
}

// Si le formulaire de création d'un article vient d'être posté et que le champ escobar est bien vide
if (isset($_POST['saveNewPost']) && empty($_POST['escobar'])) {

    // On nettoie la valeur des champs
    $formPost = Outils::cleanUpValues($_POST);

    // On récupère les valeurs des champs du formulaire
    $title = $formPost['title'];
    $content = $formPost['content'];

    // On valide les champs
    $FormErr = [];
    //Le champ title est obligatoire
    if (empty($title)) {
        array_push($FormErr, 'title');
    }
    //Le champ content est obligatoire
    if (empty($content)) {
        array_push($FormErr, 'content');
    }

    // Si le formulaire ne comporte pas d'erreur
    if (count($FormErr) === 0) {

        // On enregistre le post
        $post = new Post($formPost);
        $conx->inserer('post', $post);
        $postid = $conx->dernierIndex();

        // Gestion des nouveaux tags et tags existants
        if (isset($formPost['newtags']) && !empty($formPost['newtags'])) {

            // On récupère les nouveaux tags et on les stocke dans un tableau
            $newTags = explode(',', $formPost['newtags']);

            // On parcours le tableau des nouveaux tags
            foreach ($newTags as $newTag) {

                //On vérifie si le tag n'existe pas déjà en base de données
                $req_tag_exists = "SELECT `id` FROM `tag` WHERE `name`='" . $newTag . "'";
                $res_tag_exists = $conx->requete($req_tag_exists, 'fetch');

                // Si le tag existe on l'ajoute aux tags déjà existants à lier au post
                if (is_array($res_tag_exists) && !empty($res_tag_exists)) {
                    $formPost['tags'][] = $res_tag_exists['id'];
                } // Si le tag n'existe pas on le créé et on récupère son nouvel id
                else {
                    $tag = new Tag($newTag);
                    $conx->inserer('tag', $tag);
                    $tagid = $conx->dernierIndex();
                    // Puis on l'ajoute aux tags déjà existants à lier au post
                    $formPost['tags'][] = $tagid;
                }
            }
        }

        // On assure l'unicité des tags à lier
        if (isset($formPost['tags'])) {
            $selectedTags = array_unique($formPost['tags']);

            // On enregistre les tags du post
            foreach ($selectedTags as $selectedTag) {
                $posttag_ar = ['idpost' => (int)$postid, 'idtag' => (int)$selectedTag];
                $postTag = new PostTag($posttag_ar);
                $conx->inserer('post_tag_mm', $postTag);
            }
        }
        // On redirige sur la page d'accueil avec le paramètre postinsert=$postid pour générer un affichage
        header('Location:' . $conf['defaults']['home_url'] . '?blogid=' . $_SESSION['blogid'] . '&postinsert=' . $postid);

    }
}
?>
<div class="container bb-1">
    <div class="row justify-content-center mt-3">
        <div class="col">
            <?= /** @var string $current_blog_logo */
            $current_blog_logo; ?>
            <h1 class="d-inline-flex">
                Créer un nouvel article dans le Blog <?= /** @var string $current_blog_title */
                $current_blog_title; ?>
            </h1>
            <a href="<?= $_SESSION['default_url']; ?>?blogid=<?= $_SESSION['blogid']; ?>"
               class="btn btn-sm btn-primary">Annuler</a>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-8">
            <form action="newpost.php?blogid=<?= $_SESSION['blogid']; ?>" method="post" autocomplete="off">
                <div class="form-group has-validation mb-3">
                    <label class="form-label" for="title">Titre <?= $conf['defaults']['mandatory']; ?>:</label>
                    <?php
                    $titleValidationClass = '';
                    if (isset($FormErr) && in_array('title', $FormErr)) {
                        $titleValidationClass = ' is-invalid';
                    }
                    ?>
                    <input class="form-control<?= $titleValidationClass; ?>" id="title" name="title"
                           value="<?= $title; ?>"
                           placeholder="Veuillez saisir ici le titre de l'article...">
                    <div class="invalid-feedback">Veuillez saisir le titre de l'article !</div>
                    <input type="hidden" value="<?= $_SESSION['blogid']; ?>" name="blogid">
                    <input type="hidden" value="1" name="author">
                </div>
                <div class="form-group has-validation mb-3">
                    <label class="form-label" for="content">Contenu de l'article <?= $conf['defaults']['mandatory']; ?>
                        :</label>
                    <?php
                    $contentValidationClass = '';
                    if (isset($FormErr) && in_array('content', $FormErr)) {
                        $contentValidationClass = ' is-invalid';
                    }
                    ?>
                    <textarea rows="10" class="form-control<?= $contentValidationClass; ?>" id="content" name="content"
                              placeholder="Veuillez saisir ici le contenu de l'article..."><?= $content; ?></textarea>
                    <div class="invalid-feedback">Veuillez saisir le contenu de l'article !</div>
                </div>
                <div class="form-group d-none">
                    <label class="form-label" for="escobar"></label>
                    <input name="escobar" id="escobar" value="">
                </div>
                <div class="form-group mt-3 mb-3">
                    <label class="form-label" for="tags">Tags existants :</label>
                    <select id="tags" name="tags[]" multiple>
                        <?php
                        $optionsTags = '';
                        foreach ($res_tags as $tag) {
                            $optionsTags .= '<option value="' . $tag['id'] . '">' . $tag['name'] . '</option>';
                        }
                        echo $optionsTags;
                        ?>
                    </select>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label class="form-label" for="newtags">Nouveaux Tags :</label>
                    <input type="text" id="newtags" name="newtags">
                </div>
                <div class="form-group mt-5 mb-3">
                    <small>Les champs précédés d'une astérisque <?= $conf['defaults']['mandatory']; ?> sont <span
                                class="text-danger">obligatoires</span>.</small>
                </div>
                <div class="form-group mt-3 mb-3 float-end">
                    <button type="submit" name="saveNewPost" class="btn btn-sm btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php' ?>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/choices.min.js"></script>
<script src="js/tagChooser.min.js"></script>
</body>
</html>
