<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       	
	       	<li class="active">Todo List</li>   	       	
	    </ol>
	</div>
	<br>	
	<!-- Nav tabs -->
	<ul class="nav nav-tabs">
	  <li class="active"><a href="#writing" data-toggle="tab">EDGE Writing</a></li>
	  <li><a href="#list" data-toggle="tab">MUSE</a></li>	  
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">

		<div class="tab-pane active" id="writing">
			<table class="table table-striped">	  		
				<thead>
					<tr>
						<!-- <th class="text-center">No.</th> -->
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
		
		<div class="tab-pane" id="list">
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
				<tbody id="list">										
					<!-- table view ajax -->
				</tbody>				
			</table>
			<div class="text-center" id="pageblock">
				<!-- ajax list -->
			</div>	
		</div> <!-- tab Muse -->			  

	</div> <!-- tab content -->					
</div><!-- div container -->
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
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var editor_id = '<?=$editor_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = 'edi_todo';

$(document).ready(function(){		
	var email = "<?=$this->session->userdata('email');?>";	
	var secret_data = {	
		email : email		
	};
	//console.log(secret_data);	

	$.ajax({		
		url: '/writing/get_list',		
		type: 'post',		
		data: secret_data,
		dataType: 'json',
		success: function(json){						
			result = JSON.parse(json['result']);						
			access = JSON.parse(json['access']); //delegate에서 data token 필요하기 때문에 access 필요!				
			
			if(result['status']){				
				//num = 1; // No. 번호
				$("<tr id='trow'>").appendTo('tbody#writing');				
				//$("tr#trow").append('<td class="text-center">1</td>');
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
			}else{ // 새로운 내용이 없을때				
				//console.log(access['status']);
				if(!access['status']){
					$("<tr id='trow'>").appendTo('tbody#writing');
					$("tr#trow").append('<td class="text-center" colspan="6">No authorization for Edge writing service!</td>');					
				}else{
					$("<tr id='trow'>").appendTo('tbody#writing');
					$("tr#trow").append('<td class="text-center" colspan="6">No new posts!</td>');					
				}				
			}						
		}
	})// ajax End		

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
		
		$.ajax({				
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

	function ajaxPost(url,data){
		$.post(url,data,function(json) {				
			page = json['page'];

			$('tbody#list').children().remove();
			var history_list = json['history_list'];	
			var num = (list * page) - (list-1);
			history_totalcount = json['history_totalcount'];
			
			for(var i = 0; i < json['history_list'].length; i++) {
				var essay_id = history_list[i]['essay_id'];
				var task = history_list[i]['type'];

				$('tbody#list').append('<tr id='+i+'><td>'+num+'</td><td>'
					+history_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
					+history_list[i]['kind']+'</td><td>'
					+history_list[i]['start_date']+'</td><td>'
					+history_list[i]['type']+'</td>');

				if(history_list[i]['draft'] == 0){
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).append('<td><a href="/text/todo/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-default btn-sm">&nbsp;&nbsp;Revise&nbsp;&nbsp;</button></a></td></tr>');
					}else{
						$('tbody#list tr#'+i).append('<td><a href="#" class="no-uline"><button type="button" class="btn btn-default btn-sm" disabled>&nbsp;&nbsp;Revise&nbsp;&nbsp;</button></a></td></tr>');
					}					
				}else if(history_list[i]['draft'] == 1 && history_list[i]['submit'] == 0){ //draft
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).last().append('<td><a href="/text/draft/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else{
						$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}					
				}else if(history_list[i]['submit'] == 1){ //submit
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).last().append('<td><a href="/text/edit/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
					}else{
						$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');	
					}					
				}
				num++;
			}
			//console.log(page,list,history_totalcount);
			$('div#pageblock').children().remove();
			
			pageBlock(page,list,history_totalcount); // page button.
		});

	}

	function pageBlock(page,list,history_totalcount){

		var b_pageNum_list = 10; //블럭에 나타낼 페이지 번호 갯수
		var block = Math.ceil(page/b_pageNum_list); //현재 리스트의 블럭 구하기
	    var b_start_page = ( (block - 1) * b_pageNum_list ) + 1; //현재 블럭에서 시작페이지 번호    
	    var b_end_page = b_start_page + b_pageNum_list - 1; //현재 블럭에서 마지막 페이지 번호		 	
	    var total_page =  Math.ceil(history_totalcount/list); //총 페이지 수

	    if (b_end_page > total_page) {
	    	b_end_page = total_page;
	    }    

	    if(page <= 1){     	     
	        $('div#pageblock').append('<button class="btn btn-default" disabled>&laquo;</button>');
	    }else{
	        $('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="1">&laquo;</button>');
	    }

	    if(block <=1) {
	        //<font> </font>
	    }else{ 
	        $('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+(b_start_page-1)+'">Prev</button>');
	    } 

	    for(var j = b_start_page; j <= b_end_page; j++) {
	        if(page == j) {
	        	$('div#pageblock').append('<button class="btn btn-default active" id="p_button" page_num="1">'+j+'</button>');
	        }else{
	        	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+j+'">'+j+'</button>');            
			}              
	    }

	 	var total_block = Math.ceil(total_page/b_pageNum_list);
	 
	    if(block >= total_block) {
	    	//<font> </font>
	    }else{
	    	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+(b_end_page+1)+'">Next</button>');        
	    } 
	    
	    if(page >= total_page){
	    	$('div#pageblock').append('<button class="btn btn-default" disabled>&raquo;</button>');        
	    }else{ 
	    	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+total_page+'">&raquo;</button>');        
	    }
	}

	data = {
		page : page,
		list : list,		
		editor_id : editor_id,
		cate : cate
	}
	console.log(data);
	url = '/status/page_list';
	
	ajaxPost(url,data);	//page list.

	$('div#pageblock').delegate('button#p_button', 'click', function(){	
		var page_num = $(this).attr('page_num');
		
		data ={
			page : page_num,
			list : list,		
			editor_id : editor_id,
			cate : cate		
		}
		console.log(data);
		url = '/status/page_list';
		
		ajaxPost(url,data); //page list.
	});
});

</script>
