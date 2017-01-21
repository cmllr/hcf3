<?php
    $markdown = new Parsedown();
?>

<?php var_dump($post);?>
<a href="<?php echo $meta->URL;?>post/<?php echo $post->URL;?>/"><h1><?php echo $post->Title;?></h1></a>
Date: <?php echo $post->Date;?>
<div>
    <?php echo $markdown->text($post->Content);?>
</div>
<?php foreach($post->Tags as $tag) :?>
    <pre>#<?php echo $tag;?></pre>
<?php endforeach;?>