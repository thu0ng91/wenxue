<style>
    .main-tops .carousel-body{
        padding: 10px
    }
    .main-tops .carousel{
        height: 380px;
    }
    .main-tops .carousel-indicators{
        position: absolute;
        right: 10px;
        top: -35px;
        width: auto;
        margin-left: 0;
        left: auto
    }
    .main-tops .carousel-indicators li{
        border-color: #000
    }
    .aside-tops{
        width: 300px;
    }
    .aside-tops .module{
        height: 540px;
        margin-bottom: 40px;
        overflow: hidden
    }
    .aside-tops .module .top-item{
        margin-bottom: 10px;
    }
    .aside-tops .module .item{
        padding: 12px 0 8px;
        border-top: 1px solid #F2f2f2
    }
    .aside-tops .module .dot{
        background: #93ba5f;
        display: inline-block;
        padding: 1px 6px;
        color: #fff;
        margin-right: 10px;
        border-radius: 2px;
        line-height: 18px
    }
</style>
<div class="container">
    <div class="main-part main-tops">
        <?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft1']));?>
        <?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd1']));?>
        <?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft2']));?>
        <?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd2']));?>
        <?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft3']));?>
        <?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd3']));?>
    </div>
    <div class="aside-part aside-tops">
        <?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight1']));?>
        <?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight2']));?>
        <?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight3']));?>
    </div>
</div>