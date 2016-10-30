<div class="module">
    <div class="module-body">
        <ul class="ui-list ui-list-link">
        <?php foreach ($forums as $forum){$_favorited=in_array($forum['id'],$favorites);?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('posts/index',array('forum'=>$forum['id']));?>">
                <?php if($forum['faceImg']){?>
                <div class="ui-avatar">
                    <span style="background-image:url(<?php echo $forum['faceImg'];?>)"></span>
                </div>
                <?php }?>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo $forum['title'];?></h4>
                    <p>关注 <?php echo $forum['favors'];?>  帖子 <?php echo $forum['posts'];?></p>
                    <p class="color-grey ui-nowrap-multi"><?php echo $forum['desc'];?></p>        
                </div>
            </li>
        <?php }?>
        </ul>
    </div>
</div>