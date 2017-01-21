<?php
    $markdown = new Parsedown();
?>
<body class="post-template">
    <?php if($f3->get("PARAMS.name") !== null) :?>
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
    <?php endif;?>
	<main class="content" role="main">
		<article class="post">
            <span class="post-meta"><?php echo date($meta->DateFormat,$post->Date);?></span>
            <h1 class="post-title"><a href="<?php echo $meta->URL;?>post/<?php echo $post->URL;?>"><?php echo $post->Title;?></a></h1>
            <section class="post-content">
                <p>
                    <?php echo $f3->get("PARAMS.name") !== null ? $markdown->text($post->Content) : substr(strip_tags($markdown->text($post->Content)),0,300).(strlen($post->Content) > 300 ? "..." : "");?>
                </p>
            </section>

    <?php if($f3->get("PARAMS.name") !== null) :?>
	        <footer class="post-footer">
                <?php if(count($post->Tags) > 0) :?>
                    <section class="unit">
                        <div style="text-align:center;   margin-bottom: -1em">
                            <?php foreach($post->Tags as $tag) :?>
                                <a style="text-decoration: none;font-weight: 500;" href="<?php echo $meta->URL;?>tag/<?php echo $tag;?>/">#<?php echo $tag;?></a>
                            <?php endforeach;?>
                        </div>
                    </section>
                <?php endif;?>
                <section class="unit">
                    <?php 
                    foreach($hm->Units["hitchhike2\\IWebUnit"] as $unit){
                            if (in_array("after-post",$unit->getEntryPoints())){
                                $unit->run();
                            }
                        }
                    ?>
                </section>
                
                <div class="post-post-meta">
                    <section class="author">
                        <h4><?php echo $post->Author->Name;?></h4>
                        <p><?php echo $post->Author->Description;?></p>
                    </section>
                </div>
<script>
/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
var d = document, s = d.createElement('script');
s.src = '//0fury.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
//(d.head || d.body).appendChild(s);

document.getElementById("disqus").addEventListener("click",
function(event){
	event.preventDefault();
	(d.head || d.body).appendChild(s);
	document.getElementById("disqus").style.display="none";
});

</script>
	</footer>

    <?php endif;?>
</article>
</main>
