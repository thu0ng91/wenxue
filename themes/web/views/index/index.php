<div class="container">
    <div class="main-part">
        <?php $moduleInfo1=$posts['indexLeft1'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo1['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts1=$moduleInfo1['posts'];foreach ($_posts1 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
        <?php $moduleInfo2=$posts['indexLeft2'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo2['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts2=$moduleInfo2['posts'];foreach ($_posts2 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
        <?php $moduleInfo3=$posts['indexLeft3'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo3['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts3=$moduleInfo3['posts'];foreach ($_posts3 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <?php $sideInfo1=$posts['indexRight1'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo1['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts1=$sideInfo1['posts'];foreach ($_sidePosts1 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
        <?php $sideInfo2=$posts['indexRight2'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo2['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts2=$sideInfo2['posts'];foreach ($_sidePosts2 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
        <?php $sideInfo3=$posts['indexRight3'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo3['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts3=$sideInfo3['posts'];foreach ($_sidePosts3 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
    </div>
</div>