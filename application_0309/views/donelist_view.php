<div class="container" style="margin-top:-15px;">		
	<table class="table table-striped">						
		<div class="row">		
			<ol class="breadcrumb" style="background:white;">
	        	<li><a href="/">Home</a></li>	        	
	        	<li class="active">Completed</li>   
		    </ol>
		</div>		
		<h4 class="text-center">Completed List</h4>			
		<br/>	
		<thead>
			<tr>
				<th class="text-center">Num</th>
				<th class="text-center">Title</th>								
				<th class="text-center">Type</th>			
				<th class="text-center">Task</th>			
				<th class="text-center">Completion</th>						
				<th class="text-center">Status</th>			
			</tr>
		</thead>
		<tbody id='list'>	
			<!-- ajax -->
		</tbody>		
	</table>	
	<div class="text-center" id="pageblock">
				<!-- ajax list -->
	</div>	
</div>
<script>
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var editor_id = '<?=$editor_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = 'done';

function ajaxPost(url,data){
	$.post(url,data,function(json) {		
		console.log(json['page']);
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

			if(history_list[i]['draft'] == 0){ // new
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

$(document).ready(function(){	
	data = {
		page : page,
		list : list,		
		editor_id : editor_id,
		cate : cate
	}
	console.log(data);
	url = '/status/page_list';
	
	ajaxPost(url,data);	//page list.
});

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
</script>
