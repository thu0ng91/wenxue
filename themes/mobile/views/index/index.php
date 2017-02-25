<?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft1']));?>
<?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight1']));?>
<?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd1']));?>
<?php $this->renderPartial('/index/carousel',array('moduleInfo'=>$posts['indexLeft3']));?>
<?php $this->renderPartial('/index/sideModule',array('sideInfo'=>$posts['indexRight3']));?>
<?php $this->renderPartial('/index/showCase',array('caseInfo'=>$posts['indexAd2']));?>


<?php $this->renderPartial('/index/authors',array('authors'=>$authors));?>
<?php $this->renderPartial('/index/digestes',array('digestes'=>$digestes));?>