<!<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Galerie</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
<?php
// suppression de l'image si clic sur le bouton
$confirm = '';
if(isset($_POST['delete'])) {
    unlink('./'.$_POST['file']);
    $confirm = "Fichier " . $_POST['file'] . " supprimé !";
}
// ajout d'images si formulaire d'ajout posté
if(isset($_POST['submit'])){
    if(count($_FILES['upload']['name']) > 0){
        // Loop through each file
        for($i=0; $i<count($_FILES['upload']['name']); $i++) {
            // MIME verification
            $extensions = array('.png', '.gif', '.jpg');
            $extension = strrchr($_FILES['upload']['name'][$i], '.');
            if(!in_array($extension, $extensions))
            {
                $confirm = 'Vous devez uploader des images de type png, gif ou jpg.';
            }
            // Size verification
            $maxSize = 100000;
            if (file_exists($_FILES['upload']['tmp_name'][$i]) and (filesize($_FILES['upload']['tmp_name'][$i]) > $maxSize))
                {
                $erreur = 'Le fichier est trop gros (choisissez moins de 1Mo).';
            }
            if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
            {
                // Get the temp file path
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

                // Make sure we have a filepath
                if($tmpFilePath != ""){

                    //save the filename
                    $shortName = $_FILES['upload']['name'][$i];

                    //save the url and the file
                    $extension = pathinfo($shortName, PATHINFO_EXTENSION);
                    $filename = "image" . uniqid() . '.' . $extension;
                    $filePath = "upload/$filename";

                    //Upload the file into the temp dir
                    if(move_uploaded_file($tmpFilePath, $filePath)) {

                        $files[] = $filename;
                        //show success message
                        echo "<h3>Image(s) mise(s) à jour :</h3>";
                        if(is_array($files)){
                            echo "<ul>";
                            foreach($files as $file){
                                echo '<li class="list-group-item list-group-item-dark">';
                                echo '<img src="upload/' . $file . '" alt="' . $file .'" class="img-thumbnail">';
                                echo $file . '</li>';
                            }
                            echo "</ul>";
                        }
                    }

                    else {
                        echo '<p style="color: red;">Merci de créer un dossier upload</p>';
                    }
                }
            }
            else
            {
                echo '<br><p style="color: red;">' . $erreur . '</p>';
            }
        }
    }
}
?>
<h3>Ajoutez des images à votre superbe galerie</h3>
<form action="" enctype="multipart/form-data" method="post">

    <div>
        <label for='upload'>Ajoutez des images * :</label>
        <input id='upload' name="upload[]" type="file" multiple="multiple" />
    </div>
    <p><input type="submit" name="submit" value="Ajouter à ma galerie"></p>
<p>* Plusieurs images autorisées, formats PNG, GIF, JPG, < 1Mo.</p>
</form>
            <p style="color: red;"><?= $confirm ?></p>
        </div>
        <div class="col-sm-8">

    <h3>Images déjà présentes dans notre galerie :</h3>

            <?php

            $dir = 'upload/*.{jpg,gif,png}';
            $files = glob($dir,GLOB_BRACE);

            foreach($files as $file)
            {
                echo '<li class="list-group-item list-group-item-dark">';
                echo '<div class="col-xs-6 col-md-3">';
                echo '<a href="' . $file .'" class="thumbnail" target="_blank"><img src="' . $file . '" alt="' . $file .'"></a>';
                echo $file;
                echo '</div><br>';
                echo '<form action="" enctype="multipart/form-data" method="post">';
                echo '<input type="hidden" name="file" value="';
                echo $file;
                echo '"><input type="submit" name="delete" value="Supprimer ';
                echo $file . '" class="btn btn-secondary"></li>';
                echo '</form>';
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>