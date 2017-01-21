INDEX 

<?php

    $markdown = new Parsedown();
    foreach($posts as $post){
        $post = $hm->PostUnit->getPost($post);
        if (!is_null($post)){
            include __DIR__."/post.tpl.php";
        }
    }
?>