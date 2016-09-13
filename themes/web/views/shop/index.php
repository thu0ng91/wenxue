<style>
    .goods-header{
        position: relative;
    }
    .goods-classify{
        width: 220px;
        float: left;
        height: 454px;        
        background: #93ba5f;
        border: 1px solid #93ba5f;
        color: #fff
    }
    .goods-classify a.goods-nav-item{
        color: #fff;
        height: 31px;
        line-height: 31px;
        padding: 0 15px;
        width: 220px;
        display: block;
        font-weight: 700
    }
    .goods-classify a.goods-nav-item:hover{
        background: #fff;
        color: #93ba5f;
        text-decoration: none;
        width: 220px; 
    }
    .goods-classify a .fa{
        padding-top: 10px
    }
    .goods-classify a.goods-nav-item:hover .fa{
        color: #fff
    }
    .goods-classify-holder{
        width: 740px;
        min-height: 454px;
        position: absolute;
        left: 220px;
        top: 0;
        background: #fff;
        z-index: 99;
        border: 1px solid #93ba5f;
        border-left: none;
        box-sizing: border-box;
    }
    .goods-classify-holder .items-holder{
        line-height: 31px;
    }
    .goods-classify-holder .items-holder .items-label{
        float: left;
        width: 80px;
        text-align: right;
        font-weight: 700
    }
    .goods-classify-holder .items-holder .items-label a{
        color: #333
    }
    .goods-classify-holder .items-holder .items{
        padding-left: 90px
    }
    .goods-carousel{
        width: 730px;
        height: 454px;
        float: right
    }
    .goods-carousel .item img{
        width: 730px;
        height: 454px;
    }
</style>
<div class="container">
    <div class="goods-header">
        <div class="goods-classify">
            <?php foreach ($navbars as $navbar){?>
            <?php echo CHtml::link($navbar['title'].'<i class="fa fa-angle-right pull-right"></i>','javascript:;',array('class'=>'goods-nav-item','data-id'=>$navbar['id']));?>  
            <?php }?>
        </div>
        <?php foreach ($navbars as $navbar){$seconds=$navbar['items'];?>
        <div class="goods-classify-holder">
            <?php foreach($seconds as $second){$thirds=$second['items'];?>
            <div class="items-holder">
                <span class="items-label"><?php echo CHtml::link($second['title'],array());?></span>     
                <div class="items">
                    <?php foreach($thirds as $third){?>
                    <?php echo CHtml::link($third['title'],array(),array('class'=>'item'));?>
                    <?php }?>   
                </div>
            </div>
            <?php }?>
        </div>
        <?php }?>
        <div class="goods-carousel">
            <?php $this->renderPartial('/site/login-carousel');?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="module" style="margin-top:20px">
        <div class="module-header">推荐</div>
        <div class="module-body">
            
        </div>
    </div>
</div>