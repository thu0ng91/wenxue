<?php
/**
 * @filename _order.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-20  14:58:53 
 */
?>
<div class="media ui-border-b">
    <p class="color-grey">
        <span>订单号：<?php echo $data['orderId']; ?></span>
        <span class="pull-right"><?php echo zmf::time($data['paidTime']); ?></span>
    </p>
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('shop/detail', array('id' => $data['gid'])); ?>">
            <img class="media-object lazy w70" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $data['faceUrl']; ?>" alt="<?php echo $data['title']; ?>">
        </a>
    </div>
    <div class="media-body">
        <p class="title"><?php echo $data['title']; ?></p>
        <p class="color-grey ui-nowrap-multi"><?php echo $data['desc']; ?></p>
    </div>
    <div class="media-right">
        <div class="text-right" style="width: 100px;">
            <p>x <?php echo $data['num']; ?></p>
            <p class="price">= <?php echo $data['totalPrice'].$data['typeLabel']; ?></p>
        </div>
    </div>
</div>