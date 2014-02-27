<div class="container" style="margin-top:-15px;">		
	<?
	if($this->session->userdata('classify') == 0){
	?>		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>
	       	<?
	       	if($cate == 'pj_mem_history'){
	       	?>
	       	<li><a href="/project/">Project List</a></li>   
	       	<li><a href="/project/status/<?=$pj_id;?>"><?=$pjName;?></a></li>   
	       	<li class="active">History</li>   
	       	<?
	       	}else{
	       	?>
	       	<li><a href="/status">Status</a></li>   
	       	<li class="active">History</li>   
	       	<?
	       	}
	       	?>        	        	
	    </ol>
	</div>

	<table class="table table-striped">	  		
		<?
		if($cate == 'all'){
		?>
			<h4 class="text-center">History</h4>
		<?
		}else{
		?>
			<h4 class="text-center">History (<?=$memName->name;?>)</h4>
		<?
		}
		?>	  		
	  	<thead>
			<tr>
				<th class="text-center">No.</th>
				<th class="text-center">Prompt</th>				
				<th class="text-center">Type</th>				
				<th class="text-center">Task</th>				
				<th class="text-center">Status</th>			
			</tr>
		</thead>
		<tbody>	
			<?
			$i = 1;
			foreach ($mem_list as $value) {
				$prompt = $value->prompt;				
				$draft = $value->draft;
				$essay_id = $value->essay_id;
				$submit = $value->submit;				
				$kind = $value->kind;
				$type = $value->type;
				$id = $value->id;
			?>
			<tr>
				<td class="text-center"><?=$i;?></td>
				<td><?=$prompt;?></td>				
				<td class="text-center"><?=$kind;?></td>				
				<td class="text-center"><?=$type;?></td>
				<?
				if($draft == 0 && $submit == 0){
				?>
					<td class="text-center"><button type="button" class="btn btn-default btn-sm" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;&nbsp;</button></td>
				<?
				}elseif($submit == 1 && $draft == 1){
					if($cate == 'all'){
				?>
					<td class="text-center"><a href="/text/all_history/<?=$id;?>" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>
					<?
					}else{
					?>
					<td class="text-center"><a href="/text/admin_eachdone/<?=$editor_id;?>/<?=$essay_id;?>/<?=$type;?>" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>
					<?
					}
				}else{
					if($cate == 'all'){
				?>
					<td class="text-center"><a href="/text/all_history/<?=$id;?>" class="no-uline"><button type="button" class="btn btn-danger btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>
					<?
					}else{
					?>
					<td class="text-center"><a href="/text/admin_eachdone/<?=$editor_id;?>/<?=$essay_id;?>/<?=$type;?>" class="no-uline"><button type="button" class="btn btn-danger btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>
				<?
					}
				}
				?>				
			</tr>	
			<?
			$i++;
			}
			?>
		</tbody>
		</table>
		<?
		}else{ // user_view(Essay List) 
		?>	
		<br>
		<div class="tab-content">
		<!-- Writing -->
			<?
			if($cate =='pj_mem_history'){
			?>
			<h4 class="text-center">History</h4>		  				
			<?
			}else{
			?>
			<h4 class="text-center">To do List</h4>		  				
			<?
			}
			?>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs">
			  <li class="active"><a href="#writing" data-toggle="tab">EDGE Writing</a></li>
			  <li><a href="#muse" data-toggle="tab">MUSE</a></li>	  
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="writing">
					<table class="table table-striped">	  		
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Title</th>				
								<th class="text-center">Type</th>				
								<th class="text-center">Time</th>
								<th class="text-center">Task</th>
								<th class="text-center">Status</th>					
							</tr>
						</thead>				
						<tbody id="writing">										
							<!-- table view ajax -->
						</tbody>				
					</table>				
				</div> <!-- tab Writing -->
				
				<div class="tab-pane" id="muse">
				  	<table class="table table-striped">	  		
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Title</th>				
								<th class="text-center">Type</th>				
								<th class="text-center">Time</th>
								<th class="text-center">Task</th>
								<th class="text-center">Status</th>					
							</tr> 
						</thead>				
						<tbody id="muse">										
							<!-- table view ajax -->
						</tbody>				
					</table>
					<div id="page" class="text-center">
						<!-- page ajax -->
					</div> 	
				</div> <!-- tab Muse -->			  
			</div> <!-- tab content -->			
		</div>		
		<?
		}
		?>			
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>		
<script src="/public/js/bootstrap.js"></script>	
<script src="/public/js/countdown.js"></script>	
<script type="text/javascript">			
var text = '';	
var result = '';
var	access = '';
var token = '';
var title = '';
var w_id = '';
var writing = '';
var critique = '';
var kind = '';

var last_num = '';
var num = 1; // No. 번호
var cate = '<?=$cate;?>';
var pj_id = '<?=$pj_id;?>';
var list_count = '';
var holistic_list_count = '';
var jj = 0;
$(document).ready(function(){		

	var secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
	var email = '<?=$this->session->userdata('email');?>';
	
	var secret_data = {
		secret : secret,
		email : email,
		pj_id : pj_id,
		cate : cate
	};
	//console.log(secret_data);	
	$.ajax({
		//url: "http://ec2-54-199-4-169.ap-northeast-1.compute.amazonaws.com/editor/auth",		
		url: '/writing/get_list',		
		type: 'post',		
		data: secret_data,
		dataType: 'json',
		success: function(json){						
			result = JSON.parse(json['result']);						
			access = JSON.parse(json['access']); //delegate에서 data token 필요하기 때문에 access 필요!		
			holistic_list_count = json['list_count'].length;

			var tagging_list = json['tagging_list']; // 10개만 가지고옴! 첫화면에 10개만 보요주기위함!
			var tagging_list_length = tagging_list.length;
			//console.log(json['tagging_list']);						
			//console.log(holistic_list_count);
			
			if(result['status']){				
				//num = 1; // No. 번호
				$("<tr id='trow'>").appendTo('tbody#writing');				
				$("tr#trow").append('<td class="text-center">1</td>');
				$("tr#trow").append('<td>'+result['data']['title']+"</td>");				
				$("tr#trow").append('<td class="text-center">'+result['data']['kind']+"</td>");
				
				// 24hr 안에 해결해야 하는경우!
				if(result['data']['is_24hr'] == 1){
					$("tr#trow").append('<td class="text-center" id="timer"></td>');					
					var db_date = result['data']['date'];
					// 2013-11-10 03:11:03
					var month = db_date.substring(5,7);
					var day = parseInt(db_date.substring(8,10))+1;						
					var year = db_date.substring(0,4);
					var time = db_date.substring(11,19);					
					var mon ="";

					switch(month)
					{
						case '01' : mon = "January"; break;
						case '02' : mon = "February"; break;
						case '03' : mon = "March"; break;
						case '04' : mon = "April"; break;
						case '05' : mon = "May"; break;
						case '06' : mon = "June"; break;
						case '07' : mon = "July"; break;
						case '08' : mon = "August"; break;
						case '09' : mon = "September"; break;
						case '10' : mon = "October"; break;
						case '11' : mon = "November"; break;
						case '12' : mon = "December"; break;											
					}					

					var countdate =  mon + ' ' + day +','+' '+ year +' '+ time;						
					
					$(function() {
					    $('td#timer').countdown({					        
					        date: countdate				
					        //date: "November 7, 2013 16:03:26"
					    });
					});	
					$("tr#trow").append('<td class="text-center" style="color:red;"><b>Writing</td>');
					$("tr#trow").append('<td class="text-center"><button class="btn btn-danger btn-sm" id="adjust">&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;</button></td>');
				}else{ // 24hr 이 아닌경우!!
					$("tr#trow").append('<td class="text-center">-- : -- : --</td>');
					$("tr#trow").append('<td class="text-center"><b>Writing</td>');
					$("tr#trow").append('<td class="text-center"><button class="btn btn-danger btn-sm" id="adjust">&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;</button></td>');
				}
			}else{ // 새로운 내용이 없을때, Tagging해야 할 정보를 보여준다!
				
				$("<tr id='trow'>").appendTo('tbody#writing');
				$("tr#trow").append('<td class="text-center" colspan="6">No new posts!</td>');				
			}			
			console.log(holistic_list_count);
			//list_count = holistic_list_count;

			if(holistic_list_count > 10){ // 전체 리스트가 10개 이상이면 처음 10개를 리스트로 보여준다!
				for(var ii = 0; ii < 10; ii++){
					$("<tr id="+ii+">").appendTo('tbody#muse');
					$("tr#"+ii).append('<td class="text-center">'+num+'</td>');
					$("tr#"+ii).append('<td>'+tagging_list[ii][0].replace(/"/g,'')+'</td>'); // title
					$("tr#"+ii).append('<td>'+tagging_list[ii][1]+'</td>'); // kind
					var tagging_time = tagging_list[ii][2].substring(0,16);
					//console.log(tagging_time);
					$("tr#"+ii).append('<td>'+tagging_time+'</td>'); // time
					$("tr#"+ii).append('<td class="text-center">Tagging</td>'); // type
					var essay_id = tagging_list[ii][4];
					var draft = tagging_list[ii][5]; // 1 == true or 0 == false
					var submit = tagging_list[ii][6]; // 1 or 0
					var type = tagging_list[ii][3];
					var id = tagging_list[ii][7];					

					if(draft == 1 && submit == 1){						
						$("tr#"+ii).append('<td><a href="/text/edit/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
					}else if(draft == 1 && submit == 0){
						$("tr#"+ii).append('<td><a href="/text/draft/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else{						
						$("tr#"+ii).append('<td><a href="/text/todo/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-default btn-sm">&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;</button></a></td>');
					}

					if(ii == 9){
						last_num = id;						
					}
					num++;
				}

				// Page Butotn
				var page = Math.ceil(holistic_list_count / 10); // 한 페이지에 보여질 리스트 수				
				var holistic_array = json['list_count'];
				var holistic_array_count = json['list_count'].length;				
				var last_num_array = new Array();						
				
				for(jj; jj <= holistic_array_count-1; jj+=10){
					last_num_array.push(holistic_array[jj]);			
				}							

				if(holistic_array[holistic_array_count-1]['id'] != last_num_array[last_num_array.length-1]['id']){
					last_num_array.push(holistic_array[holistic_array_count-1]);					
				}			
				//console.log(page);
				for(var kk = 0; kk < page; kk++){
					$('#page').append('<button class="btn btn-default" count="'+last_num_array[kk]['id']+'" p_num="'+(kk+1)+'">'+(kk+1)+'</button>');					
					
				}
			}else{				
				for(var ii = 0; ii < holistic_list_count; ii++){ // 전체 리스트가 10개 이하 일때!
					$("<tr id="+ii+">").appendTo('tbody#muse');
					$("tr#"+ii).append('<td class="text-center">'+num+'</td>');
					$("tr#"+ii).append('<td>'+tagging_list[ii][0].replace(/"/g,'')+'</td>'); // title
					$("tr#"+ii).append('<td>'+tagging_list[ii][1]+'</td>'); // kind
					var tagging_time = tagging_list[ii][2].substring(0,16);
					//console.log(tagging_time) ;
					$("tr#"+ii).append('<td>'+tagging_time+'</td>'); // time
					$("tr#"+ii).append('<td class="text-center">Tagging</td>'); // type
					var essay_id = tagging_list[ii][4];
					var draft = tagging_list[ii][5]; // 1 == true or 0 == false
					var submit = tagging_list[ii][6]; // 1 or 0
					var type = tagging_list[ii][3];
					var id = tagging_list[ii][7];
					//console.log(type);

					if(draft == 0 ){
						$("tr#"+ii).append('<td><a href="/text/todo/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-default btn-sm">&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else if(draft == 1 && submit == 0){
						$("tr#"+ii).append('<td><a href="/text/draft/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else if(draft == 1 && submit == 1){
						$("tr#"+ii).append('<td><a href="/text/edit/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
					}

					if((tagging_list_length - 1) == ii){
						last_num = id;
					}
					num++;
				}
			}					
		}
	})// ajax End	
	var page_num = '';

	$('div#page ').delegate('button','click',function(){		
		//var button_classify = $(this).attr('id');	
		var p_num = $(this).attr('p_num');
		p_num = (parseInt(p_num)-1)*10+1;
		
		page_num = $(this).attr('count');	
		
		console.log(page_num);
		var data_num = {
	 		last_num : page_num, //tag_essay = id
	 		pj_id : pj_id,
	 		cate : cate
		}    
		console.log(data_num);

	    $.ajax({
	        url: "/writing/todo_list",
	        type: 'post',		
	        data: data_num,
	        dataType: 'json',
	        success: function(json) {   	        	
	        	//console.log(json['cate']);
	        	//consol // 전체 리스트가 10개 이상이면 처음 10개를 리스트로 보요준다!e.log(json['count']);
	        	//console.log(json['tagging_list']);
	        	$('tbody#muse').empty();
	        	var tagging_list = json['tagging_list'];
				var tagging_list_length = tagging_list.length;
	        	
	        	for(var ii = 0; ii < tagging_list_length; ii++){
					$("<tr id="+ii+">").appendTo('tbody#muse');
					$("tr#"+ii).append('<td class="text-center">'+p_num+'</td>');
					$("tr#"+ii).append('<td>'+tagging_list[ii][0].replace(/"/g,'')+'</td>'); // title
					$("tr#"+ii).append('<td>'+tagging_list[ii][1]+'</td>'); // kind
					var tagging_time = tagging_list[ii][2].substring(0,16);
					//console.log(tagging_time) ;
					$("tr#"+ii).append('<td>'+tagging_time+'</td>'); // time
					$("tr#"+ii).append('<td class="text-center">Tagging</td>'); // type
					var essay_id = tagging_list[ii][4];
					var draft = tagging_list[ii][5]; // 1 == true or 0 == false
					var submit = tagging_list[ii][6]; // 1 or 0
					var type = tagging_list[ii][3];
					var id = tagging_list[ii][7];
					//console.log(type);

					if(draft == 0 ){
						$("tr#"+ii).append('<td><a href="/text/todo/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-default btn-sm">&nbsp;&nbsp;&nbsp;Revise&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else if(draft == 1 && submit == 0){
						$("tr#"+ii).append('<td><a href="/text/draft/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else if(draft == 1 && submit == 1){
						$("tr#"+ii).append('<td><a href="/text/edit/'+essay_id+'/'+type+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
					}

					if((tagging_list_length - 1) == ii){
						page_num = id;
					}
					p_num++;
				}
	        }
	    });	 	    
	});	
	

	$('body').delegate('button#adjust', 'click', function()
	{		
		token = access['data']['token'];
		w_id = result['data']['id'];
		title = result['data']['title'];
		writing = result['data']['writing'];
		critique = result['data']['is_critique'];
		kind = result['data']['kind'];

		var data = {
			token: token,
			w_id: w_id
		};
		console.log(title);
		$.ajax({
				//url: 'http://192.168.0.22:8888/editor/editing/start',
				url:"/writing/write",
				type: 'POST',		
				data: data,
				dataType: 'json',
				success: function(json){
				var	access = JSON.parse(json['result']);			
				console.log(access['status']);									
				if(access['status']){											
				    $('<form action="/text/adjust" method="POST"/>')
			        .append($('<input type="hidden" name="token" value="' + token + '">'))
			        .append($('<input type="hidden" name="w_id" value="' + w_id + '">'))
			        .append($('<input type="hidden" name="title" value="' + title + '">'))
			        .append($('<input type="hidden" name="writing" value="' + writing + '">'))						        
			        .append($('<input type="hidden" name="critique" value="' + critique + '">'))						        
			        .append($('<input type="hidden" name="kind" value="' + kind + '">'))						        
			        .appendTo($(document.body)) //it has to be added somewhere into the <body>
			        .submit();
				}else{
					alert('status false');
				}
			}
		});
	});
});

</script>
