<?php
/**
 * @filename _cols.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-6-6  10:52:18 
 */
//$cols=  Column::allCols();
?>
<footer class="footer">
    <ul class="ui-tiled">
        <li data-href="<?php echo zmf::config('baseurl'); ?>"><i class="fa fa-home"></i>首页</li>
        <li data-href="<?php echo Yii::app()->createUrl('posts/index', array('type' => 'author')); ?>" class="<?php echo $this->selectNav == 'authorForum' ? 'active' : ''; ?>"><i class="fa fa-coffee"></i>作者</li>
        <li data-href="<?php echo Yii::app()->createUrl('posts/index', array('type' => 'reader')); ?>" class="<?php echo $this->selectNav == 'readerForum' ? 'active' : ''; ?>"><i class="fa fa-puzzle-piece"></i>读者</li>
        <?php if (!$this->uid) { ?>
            <li data-href="<?php echo Yii::app()->createUrl('site/login'); ?>"><i class="fa fa-user"></i>我的</li>
        <?php } else { ?>
            <li onclick="showUserSide()"><i class="fa fa-user"></i>我的</li>
        <?php } ?>
    </ul>
</footer>
<div class="user-side-holder" id="user-side-holder">
    <ul class="ui-list ui-border-tb">
        <li>
            <div class="ui-avatar-s">
                <span style="background-image:url(<?php echo zmf::getThumbnailUrl($this->userInfo['avatar'], 'a120', 'avatar'); ?>)"></span>
            </div>
            <div class="ui-list-info ui-border-t">
                <p class="ui-nowrap"><?php echo $this->userInfo['truename']; ?></p>
            </div>
        </li>
    </ul>
    <ul class="ui-list ui-list-text ui-border-tb">
        <?php if($this->userInfo['authorId']>0){?>
            <?php if($this->adminLogin){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('author/createBook');?>">
                <div class="ui-list-info"><p class="ui-nowrap">新增作品</p></div>
            </li>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$this->userInfo['authorId']));?>">
                <div class="ui-list-info"><p class="ui-nowrap">进入作者中心</p></div>
            </li>
            <?php }else{?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/authorAuth');?>">
                <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-lock"></i> 登录作者中心</p></div>
            </li>
            <?php }?>                                
        <?php }else{?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/author');?>">
                <div class="ui-list-info"><p class="ui-nowrap">成为作者</p></div>
            </li>                       
        <?php }?>          
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/notice');?>">
            <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-bell"></i> 消息</p></div>
        </li>
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/setting');?>">
            <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-info-circle"></i> 修改资料</p></div>
        </li>
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/setting',array('action'=>'passwd'));?>">
            <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-lock"></i> 修改密码</p></div>
        </li>
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('user/setting',array('action'=>'skin'));?>">
            <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-user"></i> 修改头像</p></div>
        </li>
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('site/logout');?>">
            <div class="ui-list-info"><p class="ui-nowrap"><i class="fa fa-sign-out"></i> 退出</p></div>
        </li>
    </ul>
</div>