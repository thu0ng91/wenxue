<?php

/**
 * @filename copyright.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  19:34:04 
 */
?>
<div class="footer">
    <div class="wrapper">
        <?php if(!empty($this->links)){?>
        <div class="friend-links"><span class="_item">友情链接：</span><?php foreach($this->links as $link){echo CHtml::link($link['title'],$link['url'],array('target'=>'_blank','class'=>'_item'));}?></div>
        <?php }?>        
        <p>
            <?php echo CHtml::link(zmf::config('sitename'), zmf::config('baseurl')); ?>&nbsp;•&nbsp;
            <?php echo CHtml::link('关于我们',array('site/info','code'=>'about'),array('target'=>'_blank'));?>&nbsp;•&nbsp;
            <?php echo CHtml::link('联系我们',array('site/info','code'=>'contact'),array('target'=>'_blank'));?>&nbsp;•&nbsp;
            <?php echo CHtml::link('隐私条款',array('site/info','code'=>'about'),array('target'=>'terms'));?>&nbsp;•&nbsp;
            <?php echo CHtml::link('版权说明',array('site/info','code'=>'copyright'),array('target'=>'_blank'));?>            
            <span class="pull-right">
                Copyright&copy;<?php echo date('Y'); ?>
                <?php echo CHtml::link(zmf::config('beian'),'http://www.miitbeian.gov.cn/',array('target'=>'_blank'));?>
            </span>
        </p>
    </div>
</div>
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="fa fa-angle-up"></span></a></div>
<div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="fa fa-comment"></span></a></div>