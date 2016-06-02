<style>
body{background: #fff}
.error-tips h1{font-size: 96px;text-shadow: 10px 15px 10px #ccc;}
.error-tips .success{color: #93ba5f}
.error-tips .error{color: #BF1031}
.error-tips .error-msg{color: #333;font-size: 14px;font-weight: 700}
</style>
<div class="alert text-center error-tips">
    <h1><?php echo $icon; ?></h1>
    <p class="error-msg"><?php echo CHtml::encode($message); ?></p>
    <p class="color-grey"><span id="wait"><?php echo($waitSecond); ?></span>秒后自动跳转</p>
    <p>
        <?php echo CHtml::link('立即跳转',  $jumpUrl,array('class'=>'btn btn-default btn-xs','id'=>'href','target'=>'_top'));?>
        <?php echo CHtml::link('返回首页',  zmf::config('baseurl'),array('class'=>'btn btn-default btn-xs'));?>
    </p>
</div>
<script type="text/javascript">
(function(){var wait = document.getElementById('wait'),href = document.getElementById('href').href;var interval = setInterval(function(){var time = --wait.innerHTML;if(time <= 0) {location.href = href;clearInterval(interval);};}, 1000);})();
</script>