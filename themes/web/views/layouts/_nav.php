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
<ul class="nav navbar-nav">
    <li<?php echo $this->selectNav == 'indexPage' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
    <?php foreach ($cols as $colid=>$colTitle){?>
    <li<?php echo $this->selectNav == 'column'.$colid ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('showcase/index','cid'=>$colid)); ?></li>
    <?php }?>
    <li<?php echo $this->selectNav == 'book' ? ' class="active"' : ''; ?>><?php echo CHtml::link('书城', array('book/index')); ?></li>
<!--    <li<?php echo $this->selectNav == 'shop' ? ' class="active"' : ''; ?>><?php echo CHtml::link('商城', array('shop/index')); ?></li>-->
    <?php if(!$this->uid || empty($this->userInfo['favoriteForums'])){?>
    <li<?php echo $this->selectNav == 'forum' ? ' class="active"' : ''; ?>><?php echo CHtml::link('基地', array('posts/types')); ?></li>
    <?php }else{?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">基地 <span class="caret"></span></a>               
        <ul class="dropdown-menu">
            <li role="presentation" class="dropdown-header">我的关注</li>
            <li role="separator" class="divider"></li>
            <?php foreach ($this->userInfo['favoriteForums'] as $_nav){?>
            <li><?php echo CHtml::link($_nav['title'],array('posts/index','forum'=>$_nav['id']));?></li>
            <?php }?>
            <li role="separator" class="divider"></li>
            <li><?php echo CHtml::link('全部版块', array('posts/types')); ?></li>
        </ul>
    </li>
    <?php }?>
    
</ul>