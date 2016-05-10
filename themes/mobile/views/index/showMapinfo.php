<style>
    .map-fiexed-btns{
        position: absolute;
        right: 0;
        top: 10px;
        z-index: 999;
    }
    .map-fiexed-btns .btn-group{
        background: #fff;
    }
    .map-fiexed-btns .btn{
        padding: 8px 10px;
        display: inline-block;
        border-left: 1px solid #f2f2f2
    }
    .map-mininfo{
        width: 300px
    }    
    .map-mininfo .left-num{
        margin-right: 10px
    }
    .map-canvas{
        width: 100%;
    }
</style>
<div class="map-fiexed-btns">
    <div class="btn-group" role="group">
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 上一步','javascript:;',array('class'=>'btn btn-default','onclick'=>'history.back()'));?>
        <?php echo CHtml::link('<i class="fa fa-list"></i> 文章', array('index/index'),array('class'=>'btn btn-default'));?>
    </div>
</div>
<div id="map-canvas" class="map-canvas"></div>
<script>
    var initMap = false;
    var jsonData='<?php echo $postJson;?>';
    function initialize() {
        data=$.parseJSON(jsonData);
        var bounds=new google.maps.LatLngBounds();
        var infowindow = new google.maps.InfoWindow();
        var map;        
        var current_lat = '<?php echo $model->lat!='' ? $model->lat : '29.55942';?>';
        var current_lng = '<?php echo $model->long!='' ? $model->long : '106.58334';?>';
        var current_zoom = <?php echo $model->mapZoom>0 ? $model->mapZoom : '5';?>;
        initMap = true;
        var mapOptions = {
            zoom: current_zoom,
            center: new google.maps.LatLng(current_lat, current_lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggable: true
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);        
        var len = data.length; 
        for (var i = 0; i < len; i++) {
            var info=data[i];
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(info['lat'], info['long']),
                map: map,
                title: info['title']
            });            
            var _contentString='<div class="map-mininfo"><p class="map-item-title"><a href="' + info['href'] + '" target="_blank">' + info['title'] + '</a></p><p class="help-block"><span class="left-num"><i class="fa fa-comments"></i> ' + info['comments'] + '</span><span class="left-num"><i class="fa fa-heart"></i> ' + info['favorite'] + '</span><span class="pull-right">' + info['cTime'] + '</span></p></div>';
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(_contentString);
                infowindow.open(map, this)
            });
            bounds.extend(new google.maps.LatLng(info['lat'], info['long']));
        };
        map.fitBounds(bounds);
    }
    function loadScript() {
        if (!initMap) {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src =  "http://ditu.google.cn/maps/api/js?key=<?php echo zmf::config('googleApiKey');?>&sensor=false&callback=initialize";
            document.body.appendChild(script);
        }
    }
</script>    