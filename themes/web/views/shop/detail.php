<?php

/**
 * @filename detail.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-18  17:42:29 
 */
?>
<style>
    .goods-detail-container h1{
        line-height: 1.75;
        overflow: hidden;
        font-weight: 700;
        font-size: 24px;
        margin-top: 10px;
    }
    .goods-detail-container .goods-info{
        background: #fff;
        margin-bottom: 10px;
        margin-top: 0;
        padding-top: 0
    }
    .goods-detail-container .goods-info .media-left img{
        width: 240px;
        height: 240px;
    }
    .goods-detail-container .price-holder{
        background: #f8f8f8;
        padding: 15px 10px;
        color: #666;
        margin-bottom: 15px;
    }
    .goods-detail-container .price-holder .price{
        font-size: 16px;
        color: #F40;
    }
    .goods-detail-container .buy-btns{
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .goods-content .content-aside{
        width: 240px;
        float: left
    }
    .goods-content .content-main{
        width: 710px;
        float: right
    }
    .goods-content .content-main img{
        margin: 0 auto
    }
    
    .tb-amount dd {
    height: 35px;
    line-height: 31px;
    color: #878787;
}
.tb-metatit {
    text-align: left;
    float: left;
    width: 66px;
    margin-top: 6px;
}
.tb-amount-widget .mui-amount-input {
    vertical-align: middle;
}
.tb-amount-widget .mui-amount-btn {
    display: inline-block;
    vertical-align: middle;
}

.tb-text {
    color: #666;
    font-size: 12px;
    margin: 0;
    padding: 3px 2px 0 3px;
    height: 26px;
    border: 1px solid #a7a6ac;
    width: 36px;
    line-height: 26px;
}
.tb-amount-widget .mui-amount-increase {
    width: 16px;
    height: 12px;
    overflow: hidden;
    cursor: pointer;
    border: 1px solid #a7a6ab;
    display: block;
    line-height: 12px;
    font-size: 12px;
    margin-bottom: 3px;
    text-align: center;
}
.tb-amount-widget .mui-amount-decrease {
    width: 16px;
    height: 12px;
    overflow: hidden;
    cursor: pointer;
    border: 1px solid #a7a6ab;
    display: block;
    line-height: 12px;
    font-size: 12px;
    text-align: center;
}
.tb-amount-widget .mui-amount-unit {
    vertical-align: middle;
    margin-left: 5px;
}
</style>
<div class="container goods-detail-container">
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
        <?php foreach($info['classify'] as $_nav){?>
        <li><?php echo CHtml::link($_nav['title'],array('shop/all','id'=>$_nav['id']));?></li>        
        <?php }?>
    </ol>
    <div class="media goods-info">
        <div class="media-left">
            <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $info['faceUrl'];?>"/>
        </div>
        <div class="media-body">
            <h1><?php echo $info['title'];?></h1>
            <p><?php echo $info['desc'];?></p>
            <div class="price-holder">
                <?php if($info['scorePrice']!=0){?>
                <p>积分价格：<span class="price"><?php echo $info['scorePrice'];?></span></p>
                <?php }if($info['goldPrice']!=0){?>
                <p>金币价格：<span class="price"><?php echo $info['goldPrice'];?></span></p>
                <?php }?>
            </div>             
            <dl class="tb-amount tm-clear">
                <dt class="tb-metatit">数量</dt>
                <dd id="J_Amount"><span class="tb-amount-widget mui-amount-wrap">
                        <input type="text" class="tb-text mui-amount-input" value="1" maxlength="8" title="请输入购买量" id="amount-input">
                        <span class="mui-amount-btn">
                            <span class="mui-amount-increase"><i class="fa fa-angle-up"></i></span>
                            <span class="mui-amount-decrease"><i class="fa fa-angle-down"></i></span>
                        </span>
                        <span class="mui-amount-unit">件</span>
                    </span>
                </dd>
            </dl>
            <div class="buy-btns">
                <?php echo GroupPowers::link('favoriteGoods',$this->userInfo,'收藏商品','javascript:;',array('class'=>'btn btn-default','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'goods'));?>
                <?php echo $info['scorePrice']!=0 ? GroupPowers::link('buyGoods',$this->userInfo,'积分兑换','javascript:;',array('action'=>'gotoBuy','action-data'=> Posts::encode($info['id'].'@score','goToBuy'),'title'=>'积分兑换','class'=>'btn btn-danger')) : '';?>
                <?php echo $info['goldPrice']!=0 ? GroupPowers::link('buyGoods',$this->userInfo,'金币兑换','javascript:;',array('action'=>'gotoBuy','action-data'=> Posts::encode($info['id'].'@gold','goToBuy'),'title'=>'金币兑换','class'=>'btn btn-danger')) : '';?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="goods-content">
        <div class="content-aside module">
            <div class="module-header">相关推荐</div>
            <div class="module-body">
                <?php foreach($posts as $post){?>
                <div class="media ui-border-b">
                    <div class="media-left">
                        <a href="<?php echo Yii::app()->createUrl('shop/detail',array('id'=>$post['id']));?>" target="_blank">
                            <img src="<?php echo zmf::lazyImg();?>" class="lazy a64" data-original="<?php echo $post['faceUrl'];?>" alt="<?php echo $post['title'];?>"/>
                        </a>
                    </div>
                    <div class="media-body">
                        <p><?php echo CHtml::link($post['title'],array('shop/detail','id'=>$post['id']),array('target'=>'_blank'));?></p>
                        <?php if($post['scorePrice']!=0){?>
                        <p>积分：<span class="price"><?php echo $post['scorePrice'];?></span></p>
                        <?php }if($post['goldPrice']!=0){?>
                        <p>金币：<span class="price"><?php echo $post['goldPrice'];?></span></p>
                        <?php }?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <div class="content-main module">
            <?php if($info['content']){?>
            <div class="module-header">详情</div>
            <div class="module-body">
                <?php echo $info['content'];?>
            </div>
            <?php }if($useInfo){?>
            <div class="module-header">如何使用</div>
            <div class="module-body">
                <?php echo $useInfo['content'];?>
            </div>
            <?php }?>
        </div>
    </div>
</div>