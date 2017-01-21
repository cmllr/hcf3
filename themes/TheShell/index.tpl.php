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
        $post = $hm->PostUnit->getPost($post);
        if (!is_null($post)){
            include __DIR__."/post.tpl.php";
        }
    }
?>