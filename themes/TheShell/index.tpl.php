<body class="home-template">	
<header id="site-head" >
	<div id="particles" style="
	    top: 0px;
    /* position: fixed; */
    left: 0px;
    position: absolute;
    width: 100%;
    height: 100%;
	">

	</div>
    <div class="vertical animated bounceInDown">
        <div id="site-head-content" class="inner">
            <h1 class="blog-title"><a href="<?php echo $meta->URL;?>"><?php echo $meta->Name;?></a></h1>
            <h2 class="blog-description"><?php echo $meta->Title;?></h2>
        </div>
    </div>
</header>
<?php
   /* $suffix = isset($php->post->query) ? "&query=".$php->post->query : "";
    if (empty($suffix) && isset($php->get->query)){
        $suffix = "&query=".$php->get->query;
    }*/
    $postCount = count($posts);
    $currentSite = $f3->get("GET.site") !== null ? (int)$f3->get("GET.site") : 1;
    
    $allPageCount = $postCount/5.0;
    $fullPageCount = ceil($postCount/5.0);
    $min = 0;
    $max = $fullPageCount;
    //filter
    $offset = $currentSite == 1 ? 0 : ($currentSite -1)*5.0;
    $posts = array_slice($posts,$offset,$currentSite*5.0);
            
    $canGoBack = $currentSite -1 != 0;
    $canGoForward = $currentSite < $max;
?>
<?php

    $markdown = new Parsedown();
    foreach($posts as $post){
        if (!is_null($post) && !$post->Page){
            include __DIR__."/post.tpl.php";
        }
    }
?>

<nav class="pagination" role="navigation">
    <span class="page-number">
        <?php echo $currentSite;?>/ <?php echo $max;?>
    </span>
    <?php if ($canGoBack) :?>
        <a class="newer-posts" href="?site=<?php echo $currentSite-1;?><?php echo $suffix;?>"><span aria-hidden="true">←</span></a>
    <?php endif;?>
    <?php if ($canGoForward) :?>
        <a class="older-posts" href="?site=<?php echo $currentSite+1;?><?php echo $suffix;?>"><span aria-hidden="true">→</span></a>
    <?php endif;?>
</nav>