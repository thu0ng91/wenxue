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
            <div class="activity-header">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy c640" data-original="<?php echo $activityInfo['faceimg'];?>" alt="<?php echo $activityInfo['title'];?>"/>
                <h1><?php echo $activityInfo['title']; ?></h1>
                <p class="color-grey text-center"><?php echo $activityInfo['desc']; ?></p>
            </div>
            <div class="module-header">时限</div>
            <div class="module-body">
                <p><span class="title">投稿：</span><span class="color-warning"><?php echo zmf::time($activityInfo['startTime'], 'Y/m/d');?>~<?php echo zmf::time($activityInfo['expiredTime'], 'Y/m/d');?></span></p>
                <p><span class="title">投票：</span><span class="color-warning"><?php echo zmf::time($activityInfo['voteStart'], 'Y/m/d');?>~<?php echo zmf::time($activityInfo['voteEnd'], 'Y/m/d');?></span></p>
            </div>
            <div class="module-header">详情</div>
            <div class="module-body">
                <?php echo $activityInfo['content']; ?>
            </div>
        </div>
        <div class="module">
            <div class="module-header">参与作品</div>
            <div class="module-body">
                <?php if(!empty($posts)){?>
                    <?php foreach ($posts as $post){$this->renderPartial('/book/_activityItem',array('data'=>$post,'btnInfo'=>$btnInfo));}?>
                    <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
                <?php }else{?>
                <p class="help-block text-center">暂无作品</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>