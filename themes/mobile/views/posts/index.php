<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  17:02:32 
 */
?>
<div class="forum-page">    
    <div class="module">
        <div class="module-body">
            <ul class="ui-list ui-list-link ui-border-tb">
            <?php foreach ($posts as $k=>$_post){?>
                <li class="ui-border-t <?php echo $_post['top'] && !$posts[$k+1]['top'] ? 'last-toped' : '';?>" data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$_post['id']));?>">
                    <?php if($_post['faceImg']){?>
                    <div class="ui-list-img">
                        <span style="background-image:url(<?php echo $_post['faceImg'];?>)"></span>
                    </div>
                    <?php }?>
                    <div class="ui-list-info">
                        <h4 class="ui-nowrap <?php echo Posts::exTopClass($_post['styleStatus']);?>"><?php echo $_post['title'];?></h4>
                        <p class="color-grey tips ui-nowrap">
                            <?php if($_post['top']){?>
                            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
                            <?php }?>
                            <?php if($_post['styleStatus']){?>
                            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
                            <?php }?>
                            <span><?php echo $_post['username'];?></span>  
                            <span class="pull-right">
                            <span><?php echo $_post['hits'];?>查看</span>                            
                            <span><?php echo $_post['posts'];?>回复</span>    
                            </span>
                        </p>
                    </div>
                </li>
            <?php }?>
            </ul>                
        </div>
    </div>
</div>
