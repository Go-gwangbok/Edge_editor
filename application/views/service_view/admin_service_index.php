<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>        
        <li class="akacolor">Service</li>   
      </ol>
      <h3 class="text-center">Service</h3>
      
    </div>     
    <br>    
    <div class="row">
        <?php
        foreach ($services as $value) {
            $ser_name = $value->name;
            $ser_id = $value->id;
        ?>
        <div class="col-md-4">                      
            <div class="col-md-8 line font-white" id="set_type" href="/service/serviceType/<?=$ser_name;?>" style="height:150px; margin-left:40px; background-color:#9FC93C; cursor:pointer;">
                <br/>
                <h1 class="text-center"><span class="glyphicon glyphicon-globe"></span></h1>
                <h4 class="text-center"><?=strtoupper($ser_name);?></h4>
            </div>
        </div>
        <?php } ?>        
    </div>
</div>
<script type="text/javascript">
$('div#set_type').click(function(){
    console.log($(this).attr('href'));
    window.document.location = $(this).attr("href");
});
</script>

