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