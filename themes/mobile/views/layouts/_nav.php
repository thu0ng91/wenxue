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
<div class="ui-row-flex ui-border-t">    
    <div class="ui-col <?php echo $this->selectNav=='digest' ? 'active' : '';?>" data-href="<?php echo zmf::config('baseurl');?>">首页</div>        
    <?php foreach ($cols as $colid=>$colTitle){?>
    <li<?php echo $this->selectNav == 'column'.$colid ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('showcase/index','cid'=>$colid)); ?></li>
    <div class="ui-col <?php echo $this->selectNav == 'column'.$colid ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('showcase/index',array('cid'=>$colid));?>"><?php echo $colTitle;?></div>
    <?php }?>        
    <div class="ui-col <?php echo $this->selectNav=='authorForum' ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('posts/index',array('type'=>'author'));?>">作者专区</div>
    <div class="ui-col <?php echo $this->selectNav=='readerForum' ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('posts/index',array('type'=>'reader'));?>">读者专区</div>        
</div>