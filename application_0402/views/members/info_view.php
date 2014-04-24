<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Members</li>   
      </ol>            
      <h3 style="margin-left:13px;">Members</h3>  
      <h3 class="text-center" style="margin-top:-35px;" id="title">New Editors</h3>
  </div>  <!-- Nav or Head title End -->
  <br>    
  <div class="row" >
  	<div class="col-md-12">
	  	<!-- Nav tabs -->
		<ul class="nav nav-tabs">
		  <li class="active" id="new_title"><a href="#new" data-toggle="tab">New Editors</a></li>
		  <li id="active_title"><a href="#active" data-toggle="tab">Active Editors</a></li>	  
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
		  	<div class="tab-pane active" id="new">		  	
				<br>
				<table class="table table-hover">
			  	<thead>
						<tr>
							<th class="text-center">Num</th>
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Date</th>
							<th class="text-center">Action</th>
						</tr>
				</thead>
				
				<tbody id="list">						
					<!-- Ajax -->
				</tbody>
				</table>
		  	</div>
		  	<div class="tab-pane" id="active">
		  		<br>
				<table class="table table-hover">
				  	<thead>
							<tr>
								<th class="text-center">Num</th>
								<th class="text-center">Name</th>
								<th class="text-center">Email</th>
								<th class="text-center">Task</th>
								<th class="text-center">Date</th>
								<th class="text-center">Action</th>
							</tr>
					</thead>
					
					<tbody id="active_list">						
						<!-- Ajax -->
					</tbody>
				</table>
			</div>	  
		</div>
	</div> <!-- col-md-12 -->
  </div> <!-- Row End -->

	<div id="membersmodal">
		<!-- Modal -->
	</div>
</div>
<script type="text/javascript">
var cate = '<?=$cate?>';
console.log(cate);
$('li#new_title').click(function(){
	$('#title').html('New Editors');	
});

$('li#active_title').click(function(){
	$('#title').html('Active Editors');
});

$(document).ready(function(){
	$.post('/members/info/get_Editors',{data:cate},function(json){
		var new_editors = json['get_editors'];
		console.log(new_editors);

		if(new_editors.length > 0){
			var active_num = 1;
			$.each(new_editors,function(i,values){
				var name = values['name'];
				var id = values['id'];
				var date = values['date'];
				var email = values['email'];
				var conf = values['conf'];
				var musedata = values['musedata'];
				var writing = values['writing'];


				if(conf == 1){ // New Editors
					$('tbody#list').append('<tr>'
						+'<td class="text-center">'+(i+1)+'</td>'
						+'<td class="text-center">'+name+'</td>'
						+'<td class="text-center">'+email+'</td>'
						+'<td class="text-center">'+date+'</td>'
						+'<td class="text-center">'						
							+'<button class="btn btn-primary btn-sm" style="margin-right:5px;" id="accept" usrid="'+id+'" data-toggle="modal" data-target="#myModal'+id+'">Accept</button>'
							+'<button class="btn btn-danger btn-sm" id="decline" usrid="'+id+'">Decline</button>'
						+'</td>'
						+'</tr>');				

					$('#membersmodal').append('<div class="modal fade" id="myModal'+id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
												  +'<div class="modal-dialog">'
												    +'<div class="modal-content">'
												      +'<div class="modal-header">'
												        +'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
												        +'<h4 class="modal-title" id="myModalLabel" style="color:black;">New member setting</h4>'
												      +'</div>'
												      +'<div class="modal-body" style="color:black;">'											      
												  		+'<h5>Name : '+name+'</h5>'		
												  		+'<form action="/members/info/accept_data" method="Post" id="modalsubmit'+id+'">'
	        												+'<fieldset>'
												  			+'<span style="margin-right:40px;"><strong>Task</strong></span>'
												  			+'<label class="checkbox-inline">'
																+'<input type="checkbox" id="musedata" name="musedata" value="musedata"> Muse data'
															+'</label>'
															+'<label class="checkbox-inline" style="margin-left:40px;">'
																+'<input type="checkbox" id="writing" name="writing" value="writing"> Writing'
															+'</label><br><br>'
															+'<span style="margin-right:40px;"><strong>Type</strong></span>'
															+'<label class="checkbox-inline">'
																+'<input type="checkbox" id="part_time" name="part_time" value="part_time" disabled> Part-time'
															+'</label>'
															+'<label class="checkbox-inline" style="margin-left:49px;">'
																+'<input type="checkbox" id="case" value="case" name="case" disabled> Case by Case'															
															+'</label><br><br>'															
															+'<span style="margin-right:40px;"><strong>Time</strong></span>'
																+'<input type="time" id="start" name="start" disabled>&nbsp;&nbsp; ~ &nbsp;&nbsp;<input type="time" id="end" name="end" disabled>'
																+'<br><br>'
															+'<span style="margin-right:13px;"><strong>Pay</strong>(hour)</span>'
																+'<input type="number" id="pay" name="pay" disabled> US<br>'
																+'</fieldset>'
																+'<input type="hidden" name="usr_id" value="'+id+'">'
	        												+'</form>'
												      +'</div>'
												      +'<div class="modal-footer">'
												        +'<button type="button" class="btn btn-default" data-dismiss="modal" id="cancel">Cancel</button>'
												        +'<button type="button" class="btn btn-primary" id="modal_ok" usr_id="'+id+'">&nbsp; Ok &nbsp;</button>'
												      +'</div>'
												    +'</div>'
												  +'</div>'
												+'</div>');
				}else if(conf == 0){ // Active Editors
					
					var task = new Array();

					if(musedata == 1){
						musedata = 'musedata';
						task.push(musedata);
					}

					if(writing == 1){
						writing = 'writing';
						task.push(writing);
					}

					$('tbody#active_list').append('<tr>'
						+'<td class="text-center">'+active_num+'</td>'
						+'<td class="text-center">'+name+'</td>'
						+'<td class="text-center">'+email+'</td>'
						+'<td class="text-center">'+task.toString()+'</td>'
						+'<td class="text-center">'+date+'</td>'
						+'<td class="text-center">'						
							+'<a href="/members/info/member_edit/'+id+'" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</a>'
						+'</td>'
						+'</tr>');				
					active_num ++;
				}

			}); // Each End			
		}else{
			$('#list').append('<tr>Not Post</tr>');

		}
	}); // Post End
}); // Document End


$(document).delegate('input[type=checkbox]','click',function(){		
	var classify = $(this).attr('id');
	console.log(classify);
	
	if(classify == 'musedata' || classify == 'writing'){
		if ( !$('input#writing').is(":checked") && !$('input#musedata').is(":checked"))
	    {
	      	$('input#part_time').attr ( "disabled" , true );
	      	$('input#case').attr ( "disabled" , true );
	      	$('input#start').attr ( "disabled" , true );
			$('input#end').attr ( "disabled" , true );
			$('input#pay').attr ( "disabled" , true );
	    }
	    else{
	      $('input#part_time').removeAttr ( "disabled" );
	      $('input#case').removeAttr ( "disabled" );
	    }	
	}else if(classify == 'part_time'){
		if($(this).is(':checked')){
			$('input#case').attr('disabled',true);
			if(!$('input#writing').is(":checked")){
				$('input#writing').attr('disabled',true);
			}else if(!$('input#musedata').is(":checked")){
				$('input#musedata').attr('disabled',true);
			}
			$('input#start').removeAttr("disabled");
			$('input#end').removeAttr("disabled");
			$('input#pay').removeAttr("disabled");
		}else{
			$('input#case').removeAttr ( "disabled" );
			if(!$('input#writing').is(":checked")){
				$('input#writing').removeAttr ( "disabled" );
			}else if(!$('input#musedata').is(":checked")){
				$('input#musedata').removeAttr ( "disabled" );
			}
			$('input#start').attr ( "disabled" , true );
			$('input#end').attr ( "disabled" , true );
			$('input#pay').attr ( "disabled" , true );			
		}
	}else if(classify == 'case'){
		if($(this).is(':checked')){
			$('input#part_time').attr('disabled',true);
			if(!$('input#writing').is(":checked")){
				$('input#writing').attr('disabled',true);
			}else if(!$('input#musedata').is(":checked")){
				$('input#musedata').attr('disabled',true);
			}
			$('input#pay').removeAttr("disabled");

		}else{
			$('input#part_time').removeAttr ( "disabled" );
			if(!$('input#writing').is(":checked")){
				$('input#writing').removeAttr ( "disabled" );
			}else if(!$('input#musedata').is(":checked")){
				$('input#musedata').removeAttr ( "disabled" );
			}
			$('input#pay').attr ( "disabled" , true );
		}
	}	
});


$(document).delegate('button#cancel','click',function(){		
	location.reload();
});

$(document).delegate('button#modal_ok','click',function(){	
	var usr_id = $(this).attr('usr_id');	
	$('#modalsubmit'+usr_id).submit();		
});

$(document).delegate('button#decline','click',function(){
	var usr_id = $(this).attr('usrid');
	console.log(usr_id);
	$.post('/members/info/decline',{usr_id:usr_id},function(json){
		var result = json['result'];
		console.log(result);
		location.reload();
	});
});

</script>
