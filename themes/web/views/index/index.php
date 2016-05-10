<div class="container">
    <div class="main-part">
        <?php foreach ($posts as $post){?>
        <div class="module">
            <div class="module-header">
                <?php echo $post['colInfo']['colTitle'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts=$post['posts'];foreach ($_posts as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['bookId']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['authorId']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div>