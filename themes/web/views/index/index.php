<style>
    *{margin:0; padding:0; list-style:none; }
    body{ background:#fff; font:normal 12px/22px 宋体;  }
    img{ border:0;  }
    a{ text-decoration:none; color:#333;  }	a:hover{ color:#1974A1;  }

    .douban{ width:590px; padding-top:10px;  overflow:hidden;   }	
    .douban .hd{ height:22px; line-height:22px;  overflow:hidden;   }
    .douban .hd .next,.douban .hd .prev{ float:right; display:block; width:18px; height:18px; background: #000; overflow:hidden; margin-right:6px; cursor:pointer; }
    .douban .hd .next{ background-position:-34px -7px; }
    .douban .hd .prevStop{ background-position:-6px -40px; cursor:default; }
    .douban .hd .nextStop{ background-position:-34px -40px; cursor:default; }
    .douban .hd ul{ float:right; margin:6px 6px 0 0; zoom:1; }
    .douban .hd ul li{ float:left; cursor:pointer; display:block; width:8px; height:8px; margin-right:4px; _display:inline; background: #000; }
    .douban .hd ul li.on{ background:red; }
    
    .douban .bd{ padding:12px 0 0 0;  }
    .douban .bd ul{zoom:1; }
    .douban .bd ul li{ width:106px; margin-right:15px;}
</style>

<div class="container">
    <div class="main-part main-tops">
        <?php $moduleInfo1=$posts['indexLeft1'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo1['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts1=$moduleInfo1['posts'];foreach ($_posts1 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
                
                <div class="douban" style="margin:0 auto">		
                    <div class="hd">				
                        <a class="next"></a>			
                        <a class="prev prevStop"></a>		
                        <ul></ul>
                    </div>		
                    <div class="bd">
                        <ul>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/1.1.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/1.2.jpg"></a></li>	
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/1.3.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/1.4.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/1.5.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/2.1.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/2.2.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/2.3.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/2.4.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/2.5.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/3.1.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/3.2.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/3.3.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/3.4.jpg"></a></li>		
                            <li ><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/otherDemo/3.2/images/3.5.jpg"></a></li>		
                        </ul>                        	
                    </div>
                </div>
                <script type="text/javascript">
     $(document).ready(function(){
                jQuery(".douban").slide({ mainCell:".bd ul", effect:"left", delayTime:800,vis:10,scroll:5,autoPlay:false,pnLoop:true,trigger:"click",easing:"easeOutCubic" });
            });                
    </script>
                
            </div>
        </div>
        <?php $caseInfo1=$posts['indexAd1'];?>
        <div class="showcase">
            <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $caseInfo1['post'][0]['faceimg'];?>" class="lazy"/>
        </div>
        <?php $moduleInfo2=$posts['indexLeft2'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo2['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts2=$moduleInfo2['posts'];foreach ($_posts2 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
        <?php $caseInfo2=$posts['indexAd2'];?>
        <div class="showcase">
            <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $caseInfo2['post'][0]['faceimg'];?>" class="lazy"/>
        </div>
        <?php $moduleInfo3=$posts['indexLeft3'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $moduleInfo3['title'];?>
            </div>
            <div class="module-body">
                <div class="row">
                <?php $_posts3=$moduleInfo3['posts'];foreach ($_posts3 as $_post){?>
                    <div class="col-xs-2 col-sm-2 top-book-item">
                        <p><img src="<?php echo $_post['faceImg'];?>" class="img-responsive"/></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']));?></p>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
        <?php $caseInfo3=$posts['indexAd3'];?>
        <div class="showcase">
            <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $caseInfo3['post'][0]['faceimg'];?>" class="lazy"/>
        </div>
    </div>
    <div class="aside-part aside-tops">
        <?php $sideInfo1=$posts['indexRight1'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo1['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts1=$sideInfo1['posts'];foreach ($_sidePosts1 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
        <?php $sideInfo2=$posts['indexRight2'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo2['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts2=$sideInfo2['posts'];foreach ($_sidePosts2 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
        <?php $sideInfo3=$posts['indexRight3'];?>
        <div class="module">
            <div class="module-header">
                <?php echo $sideInfo3['title'];?>
            </div>
            <div class="module-body">
                <?php $_sidePosts3=$sideInfo3['posts'];foreach ($_sidePosts3 as $_post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>                    
                <?php }?>
            </div>
        </div>
    </div>
</div>