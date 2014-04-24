<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/musedata/project">Project</a></li>   
        <li class="akacolor">Members</li>   
      </ol>                  	
      	<h3 style="margin-left:13px;"><?=$pjName?>
      		<?      	         	
	      	if($error_count->count == 0){
	      	?>
	      		<button class="btn btn-danger btn-sm pull-right" id="errorlist" disabled style="margin-right:15px;"><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;Error List</button>
	      	<?
	      	}else{
	      	?>
	      		<a href="/musedata/project/errorlist/<?=$pj_id;?>"><button class="btn btn-danger btn-sm pull-right" id="errorlist" style="margin-right:15px;"><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;Error List<span class="badge" style="background-color:transparent;"><?=$error_count->count;?></span></button></a>
	      	<?
	      	}			        
		    
		    if(count($add_users) > 0){
		    ?>
		    <button class="btn btn-default btn-sm pull-right" id="add" data-toggle="modal" data-target="#adduser" style="border-color:#6799FF; color:#6799FF; margin-right:15px;">
		      &nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp; <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp; 
		    </button> 
		    <?}else{?>
		    <button class="btn btn-default btn-sm pull-right" id="add" data-toggle="modal" data-target="#adduser" style="border-color:#6799FF; color:#6799FF; margin-right:15px;" disabled>
		      &nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp; <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp; 
		    </button> 
		    <? } ?>
    	</h3>  		
    </div>

    <div class="row">
    	<br>
    	<?    	
    	foreach ($memlist as $value) {
    		$usr_name = $value->name;
    		$usr_id = $value->usr_id; // project id		    		    
		    $date = substr($value->date,0,16);		    
		    $tbd = $value->tbd;
		    $history = $value->count; // essay_id == 0 인것이 있기 때문에.             
            $share = $value->share;
		    $done_count = $value->done_count;

            if($history > 0){
                $pecent = round(($done_count/($history-$tbd))*100);         
                $pecent_other = round(($done_count/($history-$tbd))*100,1);             
            }else{
                $pecent = 0;
                $pecent_other = 0;
            }
		    
    	?>
    	<div class="col-md-4">
    		<div class="col-md-12 line" style="height:56px; background-color:#394263; border:2px; padding-top:7px;">
    			<font color="#fff"><?=$usr_name;?></font>                
    			<button class="btn btn-default btn-xs pull-right" id="usr_del" data-toggle="modal" data-target="#modal" name="<?=$usr_name;?>" memid="<?=$usr_id;?>" style="border-color:#BDBDBD; color:#BDBDBD; margin-top:2px; padding:2px; 0 2px; 0">
    				<span class="glyphicon glyphicon-trash pull-right"></span>
    			</button>
                <!-- share -->
                <?
                if($share > 0){
                ?>
                    <a href="/musedata/project/share/<?=$pj_id;?>/<?=$usr_id;?>" style="text-decoration:none;">
                        <button class="btn btn-default btn-xs pull-right" style="margin-right:10px;">
                            <span class="glyphicon glyphicon-share"></span> Share
                        </button>
                    </a>
                <?
                }else{
                ?>
                    <a href="/" style="text-decoration:none;">
                        <button class="btn btn-default btn-xs pull-right" style="margin-right:10px;" disabled>
                            <span class="glyphicon glyphicon-share"></span> Share
                        </button>
                    </a>
                <? } ?>
                <!-- Share End-->
    			<p class="text-right" style="margin-top:6px;"><font color="#fff" size="2px"><?=$date;?></font></p>
    		</div>    		
    		<div class="col-md-12 line" style="height:110px; margin-bottom:20px;">
    			<font size="2px;">Completed (<?=$done_count;?>)</font>
    			<div class="col-md-12 line" id="progressbar" style="margin-top:-1px;">
    				<?
    				if($pecent < 10 && $pecent > 1){
    					echo '<div id="progress10" style="width:'.$pecent.'%;">    					    							
    						</div><span class="pull-right" style="margin-top:-17px;"><font color="black" size="2px">'.$pecent_other.'%</font></span>';
    				}else if($pecent > 10 && $pecent < 20){
    					echo '<div id="progress20" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 20 && $pecent < 30){
    					echo '<div id="progress30" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 30 && $pecent < 40){
    					echo '<div id="progress40" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 40 && $pecent < 50){
    					echo '<div id="progress50" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 50 && $pecent < 60){
    					echo '<div id="progress60" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 60 && $pecent < 70){
    					echo '<div id="progress70" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 70 && $pecent < 80){
    					echo '<div id="progress80" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 80 && $pecent < 90){
    					echo '<div id="progress90" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else if($pecent > 90){
    					echo '<div id="progress" style="width:'.$pecent.'%;">    					
    							<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
    						</div>';
    				}else{ // 0% 일때!.
						echo '<div style="background-color: #FFF; height: 13px; border-radius: 5px;">
								<span class="pull-right" style="margin-right:10px; margin-top:-3px;"><font color="#fff" size="2px">'.$pecent_other.'%</font></span>    					
							</div>';
    				}
    				
    				?>    				
    			</div>
    			
    			<div class="col-md-6">
    				<div class="col-md-12 text-center" style="height:30px; padding-top:5px;">
    					<a href="/">
    						<font color="#0000ED"><h5><?=$tbd;?></h5></font>
    						<font color="#394263" size="2px;"><b>T.B.D</b></font>
    					</a>
    				</div>
    			</div>

    			<div class="col-md-6">
    				<div class="col-md-12 text-center" style="height:30px; padding-top:5px;">
    					<a href="/">
    						<font color="#0000ED"><h5><?=$history;?></h5></font>
    						<font color="#394263" size="2px;"><b>History</b></font>
    					</a>
    				</div>
    			</div>    			
    		</div>
    	</div>
    	<?
    	}
    	?>
    </div>

    <!-- Modal Add User-->
	<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
		    <div class="modal-header">
		      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		      <h4 class="modal-title" id="myModalLabel" style="color:black;">Add Editor</h4>
		    </div>
		    <div class="modal-body">
		      <dl>			        
		        <dt style="color:black;">Choose your editor!</dt>
		        <?
			        foreach ($add_users as $rows) {
			        	$usr_id = $rows->id;
			        	$usr_name = $rows->name;
		        ?>
		        <dd style="color:black;">
		        	<label class="checkbox" style="color:black;">
		        		<input type="checkbox" class="select_user" value="<?=$usr_id;?>"><strong><?=$usr_name;?></strong>
		        	</label>
		        </dd>             
		        <? } ?>
		        
		      </dl>
		    </div>
		    <div class="modal-footer">
		      <button type="button" class="btn btn-default" id="modal_cancel"data-dismiss="modal">Cancel</button>			      
		      <button type="button" class="btn btn-primary" id="add_user_submit">Yes</button>
		    </div>
		  </div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

    <!-- Modal Delete User-->
	  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title" id="myModalLabel" style="color:black;">Delete</h4>
	        </div>
	        <div class="modal-body">
	          <dl class="dl-horizontal">
	            <dt style="color:black;" id="usr_name"></dt>
	            <dd style="color:black;">Are you sure?</dd>                             
	          </dl>					              
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
	          <button type="button" class="btn btn-primary btn-sm" id="del_user" pjid="<?=$pj_id;?>" usrid="">Yes</button>
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
</div>

<script>

$('button#usr_del').click(function(){
	var name = $(this).attr('name');
	var user_id = $(this).attr('memid');
	$('dt#usr_name').html(name);
	$('button#del_user').attr("usrid",user_id);
	//console.log(user_id);
});

$("button#usr_del").mouseover(function(){
  $(this).css("border-color","red");
  $(this).css("color","red");
  $(this).css("background","white");
});
$("button#usr_del").mouseout(function(){
  $(this).css("border-color","#BDBDBD");
  $(this).css("color","#BDBDBD");
  $(this).css("background","white");
});

$("button#add").mouseover(function(){
  $(this).css("border-color","#4374D9");
  $(this).css("color","#4374D9");
  $(this).css("background","white");
});

$("button#add").mouseout(function(){
  $(this).css("border-color","#6799FF");
  $(this).css("color","#6799FF");
  $(this).css("background","white");
});

$("button#add_user_submit").click(function(){
	var user_chk = $('input:checkbox:checked.select_user').map(function ()
	{
		return this.value;
	}).get();		
	
	var data = {
		users: user_chk.toString(),
		pj_id: '<?=$pj_id?>'
	}
	//console.log(data);

	$.ajax({
        type: "POST",
        url: '/musedata/project/add_users',
        data: data,
        dataType: 'json',
        success: function(json) {
        	console.log(json['result']);        	
            if(json['result']) {
            	alert('It’s been successfully processed!');
            	location.reload();
            }
            else {
            	alert("DB -> pj_add_users Error");
            }                            
        }
    });			

});

$("button#modal_cancel").click(function(){
	$('input.select_user').attr('checked', false);
});

$("button#pj_del").mouseover(function(){
  $(this).css("border-color","red");
  $(this).css("color","red");
  $(this).css("background","white");
});

$("button#pj_del").mouseout(function(){
  $(this).css("border-color","#BDBDBD");
  $(this).css("color","#BDBDBD");
  $(this).css("background","white");
});

$("button#del_user").click(function(){
	var pj_id = $(this).attr('pjid');
	var usr_id = $(this).attr('usrid');	
	var datas = {
		pj_id: pj_id,
		usr_id: usr_id
	}
	console.log(datas);

	$.ajax({
        type: "POST",
        url: '/musedata/project/del_user',
        data: datas,
        dataType: 'json',
        success: function(json) {
        	console.log(json['result']);        	
            if(json['result']) {
            	alert('It’s been successfully processed!');
            	location.reload();
            }
            else {
            	alert("DB -> del_user Error");
            }                            
        }
    });			
});

</script>
