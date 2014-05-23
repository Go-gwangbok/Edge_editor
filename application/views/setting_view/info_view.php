<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Setting</li>   
      </ol>            
      <h3 style="margin-left:13px;">Setting</h3>  
      <h3 class="text-center" style="margin-top:-35px;" id="title">New Editors</h3>
  </div>  <!-- Nav or Head title End -->
  <br>    
  <div class="row" >
  	<div class="col-md-12">
	  	<!-- Nav tabs -->
		<ul class="nav nav-tabs">
		  <li class="active" id="new_title"><a href="#new" data-toggle="tab">New Editors</a></li>
		  <li id="active_title"><a href="#active" data-toggle="tab">Active Editors</a></li>	  
		  <li id="setting_title"><a href="#setting" data-toggle="tab">Tab setting</a></li>	  
		  <li id="rubric_title"><a href="#tagscore" data-toggle="tab">Rubric setting</a></li>	  
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

		  	<!-- Active editors -->
		  	<div class="tab-pane" id="active">
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
					
					<tbody id="active_list">						
						<!-- Ajax -->
					</tbody>
				</table>
			</div>	  

			<!-- Templet setting -->
			<div class="tab-pane" id="setting">
				<br/><br/>
				<div class="row">
					<?
					foreach ($cateType as $rows) {
						$type_id = $rows->id;
						$name = $rows->name;
						$type = $rows->type;

						if($type == 'musedata'){
						?>
						<div class="col-md-3">						
							<div class="col-md-8 line font-white" id="set_type" set="<?=$name?>" setid="<?=$type_id;?>" style="height:150px; margin-left:40px; background-color:#9FC93C; cursor:pointer;">
								<br/>
								<h1 class="text-center"><span class="glyphicon glyphicon-inbox"></span></h1>
								<h4 class="text-center"><?=strtoupper($name);?></h4>
							</div>
						</div>
						<?
						}else{
						?>
						<div class="col-md-3">						
							<div class="col-md-8 line font-white" id="set_type" set="<?=$name?>" setid="<?=$type_id;?>" style="height:150px; margin-left:40px; background-color:#f15e22; cursor:pointer;">
								<br/>
								<h1 class="text-center"><span class="glyphicon glyphicon-globe"></span></h1>
								<h4 class="text-center"><?=strtoupper($name);?></h4>
							</div>
						</div>
						<?
						}
					} ?>			  		
				</div>
				<br><br>
				<div class="row">			  		
					<div class="col-md-12" id="kind_list">
						<br>
						<table class="table table-hover" id="table" style="width:953px; margin-left:90px;">
						  	<thead>
								<tr>
									<th class="text-center" style="width:7%;">Num</th>
									<th class="text-center">Kind</th>							
									<th class="text-center" style="width:20%;">Action</th>
								</tr>
							</thead>
							
							<tbody id="set">												
								<!-- Ajax -->
							</tbody>
						</table>
					</div>
				</div>
			</div>	  
			<!-- Templet setting End.-->

			<!-- Active editors -->
		  	<div class="tab-pane" id="tagscore">
		  		<br>
				<table class="table table-hover">
				  	<thead>
						<tr>
							<th class="text-center">Num</th>
							<th class="text-center">Kind</th>							
							<th class="text-center">Action</th>
						</tr>
					</thead>
					
					<tbody id="tagscore_list">						
						<!-- Ajax -->
					</tbody>
				</table>
			</div>	
			<!-- Active editors End.-->
		</div>
	</div> <!-- col-md-12 -->
  </div> <!-- Row End -->

	<div id="membersmodal">
		<!-- Modal -->
	</div>
</div>
<script type="text/javascript">
$('div#set_type').click(function(){	
	var task_id = $(this).attr('setid');	
	$('div#set_type').css('opacity',0.4);
	$(this).css('opacity',1);

	if(task_id == 1){ // Musedata
		from_table = 'adjust_data';
	}else{ // Service
		from_table = 'service_data';
	}

	data = {task : task_id,from_table : from_table};
	console.log(data);
	$.post('/setting/info/getType_data_kind',data,function(json){
		console.log(json['data_kind']);
		//console.log(set_type);
		var data_kinds = json['data_kind'];

		$('table#table').show();
		$('tbody#set').empty();
		$('div#seltype').empty();

		$.each(data_kinds,function(i,values){
			var kind = values['kind'];
			var data_kind_id = values['id'];
			$('tbody#set').append('<tr href="/setting/info/templet/'+task_id+'/'+data_kind_id+'" style="cursor:pointer;" id="kindhref">'
				+'<td class="text-center">'+(i+1)+'</td>'
				+'<td class="text-center">'+kind.toUpperCase()+'</td>'
				+'<td class="text-center">'						
					+'<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>'
				+'</td>'
				+'</tr>');	
		}); // Each end.		
	}); // post End.
});

var cate = '<?=$cate?>';
console.log(cate);
$('li#new_title').click(function(){
	$('#title').html('New Editors');	
});

$('li#active_title').click(function(){
	$('#title').html('Active Editors');
});

$('li#setting_title').click(function(){
	$('#title').html('Templet Setting');
});

$('li#rubric_title').click(function(){
	$('#title').html('Rubric Setting');
});



$(document).ready(function(){
	$('table#table').hide();
	$.post('/setting/info/setting_data',{data:cate},function(json){
		var new_editors = json['get_editors'];
		var data_kinds = json['data_kind'];
		console.log(new_editors);
		//console.log(data_kinds);

		if(new_editors.length > 0){
			var active_num = 1;
			var new_num = 1;
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
						+'<td class="text-center">'+new_num+'</td>'
						+'<td class="text-center">'+name+'</td>'
						+'<td class="text-center">'+email+'</td>'
						+'<td class="text-center">'+date+'</td>'
						+'<td class="text-center">'						
							+'<button class="btn btn-primary btn-sm" style="margin-right:5px;" id="accept" usrid="'+id+'">Accept</button>'
							+'<button class="btn btn-danger btn-sm" id="decline" usrid="'+id+'">Decline</button>'
						+'</td>'
						+'</tr>');	
					new_num++;
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

					$('tbody#active_list').append('<tr id="active_mem" href="/setting/info/member_edit/'+id+'" style="cursor:pointer">'
						+'<td class="text-center">'+active_num+'</td>'
						+'<td class="text-center">'+name+'</td>'
						+'<td class="text-center">'+email+'</td>'						
						+'<td class="text-center">'+date+'</td>'
						+'<td class="text-center">'						
							+'<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>'
						+'</td>'
						+'</tr>');				
					active_num ++;
				}

			}); // Each End			
		}else{
			$('#list').append('<tr>Not Post</tr>');

		}// If end.

		// Templet Setting.
		$.each(data_kinds,function(i,values){
			var kind = values['kind'];
			var data_kind_id = values['id'];

			$('tbody#tagscore_list').append('<tr id="tagscore_action" style="cursor:pointer;" href="/setting/info/tagScoreSet/'+data_kind_id+'">'
						+'<td class="text-center">'+(i+1)+'</td>'
						+'<td class="text-center">'+kind.toUpperCase()+'</td>'
						+'<td class="text-center">'						
							+'<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>'
						+'</td>'
						+'</tr>');	
		});

	}); // Post End
}); // Document End


// Tab Kind href
$(document).delegate('tr#kindhref','click',function(){			
	window.document.location = $(this).attr("href");      
});

$(document).delegate('tr#active_mem','click',function(){		
	//console.log($(this).attr('href'));
	window.document.location = $(this).attr("href");      
});

// Tag & Score Setting Button
$(document).delegate('tr#tagscore_action','click',function(){			
	window.document.location = $(this).attr("href");      
});

$(document).delegate('button#accept','click',function(){	
	var usr_id = $(this).attr('usrid');
	var href = '/setting/info/member_edit/'+usr_id;
	console.log(usr_id);

	$.post('/setting/info/accept',{usr_id : usr_id},function(json){
		accept = json['result'];
		if(accept){
			window.document.location = href;
		}
	});	
});

$(document).delegate('button#decline','click',function(){
	var usr_id = $(this).attr('usrid');
	console.log(usr_id);
	$.post('/setting/info/decline',{usr_id:usr_id},function(json){
		var result = json['result'];
		console.log(result);
		location.reload();
	});
});

</script>
