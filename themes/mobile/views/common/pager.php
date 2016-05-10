<?php

//分页widget代码: 
$this->widget('CLinkPager', array(
    'header' => '',
    'prevPageLabel' => '<i class="fa fa-angle-double-left"></i>',
    'nextPageLabel' => '<i class="fa fa-angle-double-right"></i>',
    'firstPageLabel' => '',
    'lastPageLabel' => '',
    'maxButtonCount' => 5,
    'pages' => $pages,
        )
);
?>