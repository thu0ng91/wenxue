<?php
/**
 * @filename GoodsClassifyController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:35 */
$this->renderPartial('_nav');
?>
<style>
    .classify-lists{
        width: 100%;
        margin: 15px 0; 
        border-bottom: 1px solid #e4e4e4
    }
    .classify-lists p{
        border-bottom: 1px dashed #F8F8f8;
        padding: 5px;
        margin-bottom: 0
    }
    .classify-lists p:hover{
        background: #F8F8f8
    }
    .classify-lists p:last-child{
        border-bottom: none
    }
    .classify-lists p a{
        margin-right: 10px
    }
</style>
<?php foreach($navbars as $navbar){$_seconds=$navbar['items'];?>
<div class="classify-lists">
    <p>
        <?php echo $navbar['title'];?>
        <span class="pull-right">
            <?php echo CHtml::link('添加分类',array('goodsClassify/create','belongid'=>$navbar['id']));?>
            <?php echo CHtml::link('排序',array('goodsClassify/order','belongid'=>$navbar['id']));?>
            <?php echo CHtml::link('编辑',array('goodsClassify/update','id'=>$navbar['id']));?>
            <?php echo CHtml::link('删除',array('goodsClassify/delete','id'=>$navbar['id']));?>
        </span>
    </p>
    <?php foreach($_seconds as $_second){$_thirds=$_second['items'];?>
    <p style="padding-left: 30px;">
        <?php echo $_second['title'];?>
        <span class="pull-right">
            <?php echo CHtml::link('添加分类',array('goodsClassify/create','belongid'=>$_second['id']));?>
            <?php echo CHtml::link('排序',array('goodsClassify/order','belongid'=>$_second['id']));?>
            <?php echo CHtml::link('编辑',array('goodsClassify/update','id'=>$_second['id']));?>
            <?php echo CHtml::link('删除',array('goodsClassify/delete','id'=>$_second['id']));?>
        </span>
    </p>
    <?php foreach($_thirds as $_third){?>
    <p style="padding-left: 60px;">
        <?php echo $_third['title'];?>
        <span class="pull-right">
            <?php echo CHtml::link('编辑',array('goodsClassify/update','id'=>$_third['id']));?>
            <?php echo CHtml::link('删除',array('goodsClassify/delete','id'=>$_third['id']));?>
        </span>
    </p>
    <?php }?>
    <?php }?>
</div>
<?php }