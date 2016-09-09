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
    <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
    <?php foreach ($cols as $colid=>$colTitle){?>
    <li<?php echo $this->selectNav == 'column'.$colid ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('showcase/index','cid'=>$colid)); ?></li>
    <?php }?>
    <li<?php echo $this->selectNav == 'shop' ? ' class="active"' : ''; ?>><?php echo CHtml::link('商城', array('shop/index')); ?></li>
    <li<?php echo $this->selectNav == 'authorForum' ? ' class="active"' : ''; ?>><?php echo CHtml::link('论坛', array('posts/types')); ?></li>
</ul>