<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
       		<li><a href="/musedata/project/">Project</a></li>   
       		<li class="akacolor"><?=$pjName;?></li> 
	    </ol> <!-- Navi end -->
	</div>	
	<h3 class="text-center"><?=$pjName;?></h3>	
	<br>
	<ul class="nav nav-tabs" id="pjboard">
	  <li class="tab_action" id="tab_todo"><a href="#todo" data-toggle="tab">To do</a></li>
	  <li class="tab_action" id="tab_com"><a href="#com" data-toggle="tab">Completed</a></li>
	  <li class="tab_action" id="tab_tbd"><a href="#tbd" data-toggle="tab">T.B.D</a></li>
	  <li class="tab_action" id="tab_history"><a href="#history" data-toggle="tab">History</a></li>
	</ul>

	<div class="tab-content">
    	<div class="tab-pane" id="todo">
    		
    	</div>
    	<div class="tab-pane" id="com">
    		
    	</div>
    	<div class="tab-pane" id="tbd">
    		
    	</div>
    	<div class="tab-pane" id="history">
    		
    	</div>
    </div>

	<table class="table table-hover">					
	  	<thead>
			<tr>
				<th class="text-center">No.</th>
				<th class="text-center">Prompt</th>				
				<th class="text-center">Type</th>				
				<th class="text-center">Date</th>				
				<th class="text-center">Timer</th>				
				<th class="text-center">Status</th>			
			</tr>
		</thead>		
		<tbody id="list">
		<!-- ajax list -->
		</tbody>	
	</table>	
	<div class="text-center" id="pageblock">
		<!-- ajax list -->
	</div>		
</div>
<script>
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var pj_id = '<?=$pj_id;?>';
var editor_id = '<?=$editor_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = '<?=$cate;?>';

function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json['list']);
		page = json['page'];

		$('tbody#list').children().remove();
		var data_list = json['list'];	
		var num = (list * page) - (list-1);
		total_count = json['total_count'];
		$('li#count').remove();
		$('ul#pjboard').last().append('<li class="pull-right" id="count"><button class="btn btn-default disabled" style="border-color:#f15e22;">Count : '+total_count+'</button></li>');		

		// todo,com,tbd,history
		for(var i = 0; i < json['list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];
			var timer = formatTime(data_list[i]['time']);			
			var prompt = data_list[i]['prompt'];
			var raw_txt = data_list[i]['raw_txt'];
			var kind_name = data_list[i]['kind_name'];

			if($.isNumeric(prompt)){
				prompt = prompt+raw_txt.substr(0,120);
			}

			if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
				var date = data_list[i]['start_date'];				
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
				var date = data_list[i]['sub_date'];
			}

			if(data_list[i]['draft'] == 0 && data_list[i]['discuss'] == 'Y'){ // new
				if(json['classify'] == 1){ // Editor
					var href = '/text_editor/todo/';
					var color = 'red';
					var status = 'New';
				}else{ // Admin
					var href = '/text_editor/todo/';
					var color = 'red';
					var status = 'New';
				}					
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 0 && data_list[i]['discuss'] == 'Y'){ //draft
				if(json['classify'] == 1){ // Editor					
					var href = '/text_editor/draft/';					
					var status = '<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>';
				}else{ // Admin					
					var href = '/text_editor/admin_eachdone/';					
					var status = '<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>';
				}					
			}else if(data_list[i]['submit'] == 1){ //submit
				if(json['classify'] == 1){ // Editor					
					var href = '/text_editor/completed/';					
					var status = '<button class="btn btn-success btn-sm">Completed</button>';
				}else{ // Admin					
					var href = '/text_editor/admin_eachdone/';					
					var status = '<button class="btn btn-success btn-sm">Completed</button>';
				}					
			}else if(data_list[i]['discuss'] == 'N'){ // T.B.D
				if(json['classify'] == 1){ // Editor					
					var href = '/text_editor/tbd/';					
					var status = '<button class="btn btn-warning btn-sm">&nbsp;&nbsp;&nbsp;T.B.D&nbsp;&nbsp;&nbsp;</button>';
				}else{ // Admin					
					var href = '/text_editor/admin_eachdone/';					
					var status = '<button class="btn btn-warning btn-sm">&nbsp;&nbsp;&nbsp;T.B.D&nbsp;&nbsp;&nbsp;</button>';
				}					
			}
						
			$('tbody#list').append('<tr id='+i+' style="cursor:pointer;" class="clickableRow" href="'+href+essay_id+'/'+task+'/'+pj_id+'"><td class="text-center">'+num+'</td><td>'
				+prompt.replace(/"/gi,'')+'</td><td class="text-center">'
				+kind_name.toUpperCase()+'</td><td style="width:90px;">'
				+date+'</td><td class="text-center">'				
				+timer+'<td class="text-center">'+status+'</td></tr>');				
			num++;
		}		
		$('div#pageblock').children().remove();
		
		pageBlock(page,list,total_count); // page button.
	});

}

function pageBlock(page,list,total_count){

	var b_pageNum_list = 10; //블럭에 나타낼 페이지 번호 갯수
	var block = Math.ceil(page/b_pageNum_list); //현재 리스트의 블럭 구하기
    var b_start_page = ( (block - 1) * b_pageNum_list ) + 1; //현재 블럭에서 시작페이지 번호    
    var b_end_page = b_start_page + b_pageNum_list - 1; //현재 블럭에서 마지막 페이지 번호		 	
    var total_page =  Math.ceil(total_count/list); //총 페이지 수

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

$(document).ready(function(){	
	if(cate == 'todo' || cate == 'draft'){
		$('li#tab_todo').addClass("active");
		$('div#todo').addClass("active");
	}else if(cate == 'com'){
		$('li#tab_com').addClass("active");
		$('div#com').addClass("active");
	}else if(cate == 'tbd'){
		$('li#tab_tbd').addClass("active");
		$('div#tbd').addClass("active");		
	}else if(cate == 'history'){
		$('li#tab_history').addClass("active");
		$('div#history').addClass("active");		
	}
	data = {
		page : page,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate
	}
	console.log(data);
	url = '/musedata/project/page_list';
	
	ajaxPost(url,data);	//page list.
});

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	
	data ={
		page : page_num,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate		
	}
	console.log(data);
	url = '/musedata/project/page_list';
	
	ajaxPost(url,data); //page list.
});

$('ul.nav').delegate('li.tab_action', 'click', function(){		
	var tab = $(this).attr('id');
	cate = tab.substr(4);	
	console.log(cate);
	data ={
		page : 1,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate		
	}
	
	url = '/musedata/project/page_list';	
	ajaxPost(url,data); //page list.
});

//<tbody id="list">
$('tbody#list').delegate('tr.clickableRow', 'click', function(){		      
    window.document.location = $(this).attr("href");      
});
</script>
