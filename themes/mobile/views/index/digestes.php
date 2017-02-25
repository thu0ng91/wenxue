<?php

/**
 * @filename digestes.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-25  11:05:05 
 */
?>
<div class="module">
    <div class="module-header">初心分享</div>
    <div class="module-body">
        <?php if(!empty($digestes)){?>
            <ul class="ui-list">
            <?php foreach ($digestes as $k=>$data){?>
                <li class="ui-border-t" data-href="<?php echo $data['url'];?>">
                    <?php if($data['faceImg']){?>
                    <div class="ui-list-img">
                        <span style="background-image:url(<?php echo $data['faceImg'];?>)"></span>
                    </div>
                    <?php }?>
                    <div class="ui-list-info">
                        <h4 class="ui-nowrap"><?php echo $data['title'];?></h4>
                        <p class="color-grey tips ui-nowrap-multi">
                            <?php echo zmf::subStr($data['content'],40);?>
                        </p>
                    </div>
                </li>
            <?php }?>
            </ul>
        <?php }?>
    </div>
</div>