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
        <p class="text-center">本站全部作品（包括小说、书评和帖子）版权为原创作者所有 本网站仅为网友写作提供上传空间储存平台。本站所收录作品、互动话题、书库评论及本站所做之广告均属其个人行为
与本站立场无关。网站页面版权为初心创文所有，任何单位，个人未经授权不得转载、复制、分发，以及用作商业用途。</p>
        <p>
            <?php echo CHtml::link(zmf::config('sitename'), zmf::config('baseurl')); ?>&nbsp;•&nbsp;
            <a href="#">关于我们</a>&nbsp;•&nbsp;
            <a href="#">联系我们</a>&nbsp;•&nbsp;
            <a href="#">隐私条款</a>&nbsp;•&nbsp;
            <a href="#">版权说明</a>
            <span class="pull-right">
                Copyright&copy;<?php echo date('Y'); ?>
                <?php echo CHtml::link(zmf::config('beian'),'http://www.miitbeian.gov.cn/',array('target'=>'_blank'));?>
            </span>
        </p>
    </div>
</div>
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="fa fa-angle-up"></span></a></div>
<div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="fa fa-comment"></span></a></div>