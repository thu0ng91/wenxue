<?php

/**
 * @filename carousel.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  13:50:35 
 */
$uuid=  uniqid();
$carouselPerSize=12;
?>
<div class="module">
    <div class="module-header">
        <?php echo $moduleInfo['title'];$_posts1=$moduleInfo['posts'];$_len1=count($_posts1);$_step1=ceil($_len1/$carouselPerSize);?>
    </div>
    <div class="module-body carousel-body">
        <?php if(!empty($_posts1)){?>
        <div id="carousel-<?php echo $uuid;?>" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php for($i1=0;$i1<$_step1;$i1++){?>
                <li <?php if($i1==0){?>class="active"<?php }?>></li>
                <?php }?>                        
            </ol>
            <div class="carousel-inner" role="listbox">
                <?php for($j1=0;$j1<$_step1;$j1++){$_tmpPost=  array_slice($_posts1, $j1*$carouselPerSize, $_len1<$carouselPerSize ? $_len1 : $carouselPerSize);?>
                <div class="item<?php if($j1==0){?> active<?php }?>">
                    <div class="row">
                    <?php foreach ($_tmpPost as $_post){?>
                        <div class="col-xs-2 col-sm-2 top-book-item">
                            <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                            <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                            <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                        </div>
                    <?php }?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
</div>