<html>
    <head>
        <?php 
            foreach($hm->Units["hitchhike2\\IWebUnit"] as $unit){
                $unit->run();
            }
        ?>
        <title><?php echo $title;?></title>
    </head>
    <body>
        <?php require $inner;?>
    </body>
</html>