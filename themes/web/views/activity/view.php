<?php
/**
 * @filename view.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-12-10  10:11:44 
 */
?>
<div class="container">
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $activityInfo['faceimg'];?>" alt="<?php echo $activityInfo['title'];?>"/>
                <h1><?php echo $activityInfo['title']; ?></h1>
            </div>
            <div class="module-header">简介</div>
            <div class="module-body">
                <?php echo $activityInfo['content']; ?>
            </div>
        </div>
        <div class="module">
            <div class="module-header">参与作品</div>
            <div class="module-body">
                <?php if(!empty($posts)){?>
                    <?php foreach ($posts as $post){$this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>false));}?>
                    <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
                <?php }else{?>
                <p class="help-block text-center">暂无作品</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>