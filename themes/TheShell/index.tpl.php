<?php
    $posts = array_filter($posts,function($p){
        return $p->Hidden !== true;
    }); 
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
    if ($postCount === 0){
        header("Location: ".$meta->URL."404/");
    }
?>
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
    $markdown = new Parsedown();
    foreach($posts as $post){
        if (!is_null($post) && !$post->Page){
            include __DIR__."/post.tpl.php";
        }
    }
?>

<nav class="pagination" role="navigation">

    <?php if ($postCount  > 0) :?>
    <span class="page-number">
        <?php echo $currentSite;?>/ <?php echo $max;?>
    </span>
    <?php endif;?>
    <?php if ($canGoBack && $postCount  > 0) :?>
        <a class="newer-posts" href="?site=<?php echo $currentSite-1;?><?php echo $suffix;?>"><span aria-hidden="true">←</span></a>
    <?php endif;?>
    <?php if ($canGoForward && $postCount  > 0) :?>
        <a class="older-posts" href="?site=<?php echo $currentSite+1;?><?php echo $suffix;?>"><span aria-hidden="true">→</span></a>
    <?php endif;?>
</nav>