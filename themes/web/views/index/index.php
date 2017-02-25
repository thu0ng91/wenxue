<div class="container">
    <div class="main-part main-tops"><?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft1']));?></div>
    <div class="aside-part aside-tops"><?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight1']));?></div>
</div>
<div class="container"><?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd1']));?></div>
<div class="container">
    <div class="main-part main-tops"><?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft3']));?></div>
    <div class="aside-part aside-tops"><?php $this->renderPartial('/index/latest',array('sideInfo'=>$posts['indexRight3']));?></div>
</div>
<div class="container"><?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd2']));?></div>
<div class="container">
    <div class="main-part main-tops"><?php $this->renderPartial('/index/digestes',array('digestes'=>$digestes));?></div>
    <div class="aside-part aside-tops"><?php $this->renderPartial('/index/authors',array('authors'=>$authors));?></div>
</div>