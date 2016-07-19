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
<div class="container forum-page">
    <div class="main-part">
        <div class="module">
            <div class="module-header">
                <?php echo $label;?>                
                <?php 
                if($type=='author'){
                    if($this->userInfo['authorId']>0 || $this->userInfo['isAdmin']){
                        echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                    }
                }else{
                    echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                }
                ?>
            </div>
            <div class="module-body">
                <ul class="ui-list ui-list-link ui-border-tb">
                <?php foreach ($posts as $k=>$_post){?>
                    <li class="ui-border-t <?php echo $_post['top'] && !$posts[$k+1]['top'] ? 'last-toped' : '';?>" data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$_post['id']));?>">
                        <?php if($_post['faceimg']){?>
                        <div class="ui-list-img">
                            <span style="background-image:url(<?php echo $_post['faceimg'];?>)"></span>
                        </div>
                        <?php }?>
                        <div class="ui-list-info">
                            <h4 class="ui-nowrap <?php echo Posts::exTopClass($_post['styleStatus']);?>"><?php echo $_post['title'];?></h4>
                            <p class="color-grey tips">
                                <?php if($_post['top']){?>
                                <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
                                <?php }?>
                                <?php if($_post['styleStatus']){?>
                                <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
                                <?php }?>
                                <?php if($_post['classify']==Posts::CLASSIFY_AUTHOR && $_post['aid']){?>
                                <span><?php echo $_post['username'];?></span>  
                                <?php }else{?>
                                <span><?php echo $_post['username'];?></span>  
                                <?php }?>
                                <span><?php echo zmf::formatTime($_post['cTime']);?></span>                            
                                <span><?php echo $_post['comments'];?>评论</span>                            
                                <span><?php echo $_post['favorite'];?>赞</span>                            
                            </p>
                        </div>
                    </li>
                <?php }?>
                </ul>                
            </div>
        </div>
    </div>
</div>