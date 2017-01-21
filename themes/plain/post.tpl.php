<h1><?php echo $post->Title;?></h1>

<div>
    <?php echo $markdown->text($post->Content);?>
</div>
<?php foreach($post->Tags as $tag) :?>
    <pre>#<?php echo $tag;?></pre>
<?php endforeach;?>