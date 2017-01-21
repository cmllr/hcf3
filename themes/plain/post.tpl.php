<?php
    $markdown = new Parsedown();
?>
<a href="post/<?php echo $post->URL;?>/"><h1><?php echo $post->Title;?></h1></a>

<div>
    <?php echo $markdown->text($post->Content);?>
</div>
<?php foreach($post->Tags as $tag) :?>
    <pre>#<?php echo $tag;?></pre>
<?php endforeach;?>