<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<?
		if($cate == 'pj_status'){
		?>
		<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>
        	<li><a href="/project">Project List</a></li>   
        	<li class="active"><?=$pj_name;?></li>   
      	</ol>
      	<h4 class="text-center"><?=$pj_name;?></h4>
      	
      	<?      	         	
      	if($error_count->count == 0){
      	?>
      		<button class="btn btn-danger btn-sm pull-right" id="errorlist" disabled style="margin-right:15px;"><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;Error List</button>
      	<?
      	}else{
      	?>
      		<a href="/project/errorlist/<?=$pj_id;?>"><button class="btn btn-danger btn-sm pull-right" id="errorlist" style="margin-right:15px;"><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;Error List<span class="badge" style="background-color:transparent;"><?=$error_count->count;?></span></button></a>
      	<?
      	}
      	?>
      	
      	<!-- Button trigger modal -->
      	<?			        
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


        <!-- Modal -->
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
			      <button type="button" class="btn btn-primary" id="add_submit">Yes</button>
			    </div>
			  </div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
      	<?
		}else{ // status
		?>
		<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>        	
        	<li class="active">Status </li>   
      	</ol>
      	<h4 class="text-center">All Member Status</h4>      	
    	<? } ?>  		
  		</br>
  	</div>
  	<div class="row" style="margin-top:10px;">		
  		<? 		  				
  		$list_count = count($list);   		
  		foreach ($list as $value) {
  			$name = $value->name;  			
  			if($cate == 'pj_status'){  				
  				$recent = $value->update_count;
  				$total = $value->count-1;
  				$done_count = $value->done_count;
  				$tbd = $value->tbd;
  			}else{
  				$tagging = $value->tagging;
  				$writing = $value->writing;
  				$sum = $tagging+$writing;
  			}  			
  			$usr_id = $value->usr_id;  			
  		?>
	  		<div class="col-md-4">
				<div class="alert alert-border-col">
			  		<div class="row">				  		
				  			<?
				  			if($cate == 'pj_status'){  				
				  			?>
				  			<h4 style="margin-left:50px;"><?=$name;?>
				  				<?
				  				if($list_count > 1){
				  				?>
								<button class="btn btn-default btn-xs pull-right" id="pj_del" data-toggle="modal" data-target="#usr_del<?=$usr_id;?>" style="border-color:#BDBDBD; color:#BDBDBD; margin-right:10px;">
					          		<span class="glyphicon glyphicon-trash"></span> 
					        	</button> 
					        		<?
					        		if($total > 0){
					        		?>
						        		<a href="/project/board/share/<?=$pj_id;?>/<?=$usr_id;?>" style="text-decoration:none;">
						        			<button class="btn btn-default btn-xs pull-right" style="margin-right:10px;">
						          				<span class="glyphicon glyphicon-share"></span> Share
						          			</button>
						          		</a>
					        		<?
					        		}else{
					        		?>
					        			<a href="/project/board/share/<?=$pj_id;?>/<?=$usr_id;?>" style="text-decoration:none;">
						        			<button class="btn btn-default btn-xs pull-right" style="margin-right:10px;" disabled>
						          				<span class="glyphicon glyphicon-share"></span> Share
						          			</button>
						          		</a>
					        		<?
					        		}		        	
				  				}else{
				  				?>
				  				<button class="btn btn-default btn-xs pull-right" id="pj_del" data-toggle="modal" data-target="#usr_del<?=$usr_id;?>" style="border-color:#BDBDBD; color:#BDBDBD; margin-right:10px;" disabled>
					          		<span class="glyphicon glyphicon-trash"></span> 
					        	</button> 				        	
					        	<button class="btn btn-default btn-xs pull-right" style="margin-right:10px;" disabled>
					          		<a href="#" style="text-decoration:none;" disabled><span class="glyphicon glyphicon-share"></span> Share</a>
					        	</button> 				        	
				  				<?
				  				}
				  				?>
				  			
				        	<? }else{ ?>
				        	<h4 class="text-center"><?=$name;?>
				        	<? } ?>
				        </h4>

				        <!-- Modal -->
					      <div class="modal fade" id="usr_del<?=$usr_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					        <div class="modal-dialog">
					          <div class="modal-content">
					            <div class="modal-header">
					              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					              <h4 class="modal-title" id="myModalLabel" style="color:black;">Delete</h4>
					            </div>
					            <div class="modal-body">
					              <dl class="dl-horizontal">
					                <dt style="color:black;"><?=$name?></dt>
					                <dd style="color:black;">Are you sure?</dd>                             
					              </dl>					              
					            </div>
					            <div class="modal-footer">
					              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
					              <button type="button" class="btn btn-primary btn-sm" id="del_user" pjid="<?=$pj_id;?>" usrid="<?=$usr_id;?>" >Yes</button>
					            </div>
					          </div><!-- /.modal-content -->
					        </div><!-- /.modal-dialog -->
					      </div><!-- /.modal -->
				    </div>

				    <div class="row">				  		
				  		<div class="col-md-6 text-center">
					  		<?
					  		if($cate == 'pj_status'){
					  		?>
					  		<p>Status</p>
					  		<p><strong>Done : </strong><?=$done_count;?></p>
					  		
					  		<p><strong>Total : </strong><?=$total;?></p>
					  		<?
					  		}else{
					  		?>
					  		<p>Essay Count</p>
					  		<p><strong>Tagging : </strong><?=$tagging;?></p>
					  		<p><strong>Writing : </strong><?=$writing;?></p>
					  		<p><strong>Total : </strong><?=$tagging+$writing;?></p>
					  		<? } ?>					  		
				  		</div>

				  		<div class="col-md-2 text-center">
				  			
				  		</div>
				  		<div class="col-md-5 text-center">
				  			<div class="row">
				  				<p>List Button</p>
				  			</div>
				  			<div class="row">
				  				<?

						  		if($cate == 'pj_status'){
						  			if($total == 0){
						  		?>
						  			<a href="#" class="no-uline"><button type="button" class="btn btn-primary btn-sm" disabled>To do List</button></a>
				  					<a href="#" class="no-uline"><button type="button" class="btn btn-success btn-sm" disabled>Completed</button></a>
						  		<?
						  			}else{
						  		?>
						  			<a href="/project/board/todo/<?=$pj_id;?>/<?=$usr_id;?>" class="no-uline"><button type="button" class="btn btn-primary btn-sm">To do List</button></a>
				  					<a href="/project/board/done/<?=$pj_id;?>/<?=$usr_id;?>" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a>
						  		<?
						  			}
						  		} else { 
						  			
						  			if($sum == 0){
						  			?>
						  				<a href="#" class="no-uline"><button type="button" class="btn btn-primary btn-sm" disabled>To do List</button></a>
				  						<a href="#" class="no-uline"><button type="button" class="btn btn-success btn-sm" disabled>Completed</button></a>	
						  			<?						  			
						  			} else {
						  			?>
						  				<a href="/status/board/todo/<?=$usr_id;?>" class="no-uline"><button type="button" class="btn btn-primary btn-sm">To do List</button></a>
				  						<a href="/status/board/done/<?=$usr_id;?>" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a>	
							  	<?
							  		}
						  		} 
						  		?>
				  			</div>					  						  							  			

				  			<div class="row">
				  				<?
						  		if($cate == 'pj_status'){ // Admin
						  			if($total == 0){
						  		?>
						  			<a href="#" class="no-uline">
						  				<button type="button" class="btn btn-warning btn-sm pull-left" style="margin:5px 0px 0px 5px; width:76px; height:30px;" disabled>TBD <span class="badge" style="background-color:#F29661;">00</span></button>
						  			</a>
						  			<a href="#" class="no-uline">
						  				<button type="button" class="btn btn-default btn-sm pull-right" style="margin:5px 6px 0px 3px; width:81px; height:30px;" disabled>&nbsp;&nbsp;&nbsp;History&nbsp;&nbsp;&nbsp;</button>
						  			</a>
						  		<?
						  			}else{
						  				if($tbd == 0){
						  		?>
						  			<a href="/project/board/discuss/<?=$pj_id;?>/<?=$usr_id;?>" class="no-uline">
						  				<button type="button" class="btn btn-warning btn-sm pull-left" style="margin:5px 0px 0px 5px; width:76px; height:30px;" disabled>TBD <span class="badge" style="background-color:#F29661;">00</span></button>
						  			</a>
						  			<?}else{?>
						  			<a href="/project/board/discuss/<?=$pj_id;?>/<?=$usr_id;?>" class="no-uline">
						  				<button type="button" class="btn btn-warning btn-sm pull-left" style="margin:5px 0px 0px 5px; width:76px; height:30px;">TBD <span class="badge" style="background-color:#F29661;">0<?=$tbd;?></span></button>
						  			</a>
						  			<?}?>
						  			<a href="/project/board/history/<?=$pj_id;?>/<?=$usr_id;?>" class="no-uline">
						  				<button type="button" class="btn btn-default btn-sm pull-right" style="margin:5px 6px 0px 3px; width:81px; height:30px;">&nbsp;&nbsp;&nbsp;History&nbsp;&nbsp;&nbsp;</button>
						  			</a>
						  		<?
						  			}
						  		} else { // Editor
						  			if($sum == 0){
						  		?>
						  			<a href="#" class="no-uline"><button type="button" class="btn btn-default btn-sm" style="margin:5px" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;History&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a>
						  		<? 
						  			}else{
						  		?>
						  			<a href="/status/board/history/<?=$usr_id;?>" class="no-uline"><button type="button" class="btn btn-default btn-sm" style="margin:5px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;History&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a>
						  		<?
						  			}
						  		} ?>
				  			</div>					  		
				  		</div>
				  	</div>
				</div>
			</div>
  		<?
  		}
  		?>
  	</div>
</div>
<script>
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

$("button#add_submit").click(function(){
	var user_chk = $('input:checkbox:checked.select_user').map(function ()
	{
		return this.value;
	}).get();		
	
	var data = {
		users: user_chk.toString(),
		pj_id: '<?=$pj_id?>'
	}
	console.log(data);

	$.ajax({
        type: "POST",
        url: '/project/add_users',
        data: data,
        dataType: 'json',
        success: function(json) {
        	console.log(json['result']);        	
            if(json['result']) {
            	alert('It’s been successfully processed!');
            	location.reload();
            }
            else {
            	alert("DB -> add_users Error");
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
        url: '/project/del_user',
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
