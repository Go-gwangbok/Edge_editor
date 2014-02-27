<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
	       	<li><a href="/project/">Project List</a></li>   
    	   	<li><a href="/project/status/<?=$pj_id;?>"><?=$pjName;?></a></li>   
	       	<li class="active">Error List</li>   	       		       	
	    </ol>
	</div>	
	<table class="table table-striped">
		<h4 class="text-center">Error List</h4>				
	  	<thead>
			<tr>
				<th class="text-center">No.</th>				
				<th class="text-center">Prompt</th>				
				<th class="text-center">Editor</th>				
				<th class="text-center">Date</th>				
				<th class="text-center">Task</th>				
				<th class="text-center">Status</th>			
				<th class="text-center" style="width:130px;">Error</th>
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
var editor_id = '';
var history_totalcount = '';
var data = '';
var url = '';
var cate = '<?=$cate;?>';
//console.log(cate);

function ajaxPost(url,data){
	$.post(url,data,function(json) {		
		console.log(json['history_totalcount']);
		//console.log(json['history_list']);
		//console.log(cate);
		page = json['page'];

		$('tbody#list').children().remove();
		var history_list = json['history_list'];	
		var num = (list * page) - (list-1);
		history_totalcount = json['history_totalcount'];

		for(var i = 0; i < json['history_list'].length; i++) {
			var essay_id = history_list[i]['essay_id'];
			var task = history_list[i]['type'];
			//console.log(history_list[i]['submit']);

			if(history_list[i]['draft'] == 0 || history_list[i]['draft'] == 1 ){
				var date = history_list[i]['start_date'];				
			}else if(history_list[i]['draft'] == 1 && history_list[i]['submit'] == 1){
				var date = history_list[i]['sub_date'];
			}
			
			$('tbody#list').append('<tr id='+i+'><td class="text-center">'+num+'</td><td>'
				+history_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
				+history_list[i]['usr_name']+'</td><td>'
				+date+'</td><td>'
				+history_list[i]['type']+'</td>');

			$('tbody#list tr#'+i).last().append('<td class="text-center"><a href="/text/admin_eachdone/'+history_list[i]['editor_id']+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;Error&nbsp;&nbsp;&nbsp;</button></a></td><td class="text-center"><button class="btn btn-sm btn-danger" id="confirm" essay_id="'+essay_id+'">Yes</button> <button class="btn btn-sm btn-danger" id="noterror" essay_id="'+essay_id+'">Return</button></td>');
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
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate
	}
	console.log(data);
	url = '/project/page_list';
	
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
	url = '/project/page_list';
	
	ajaxPost(url,data); //page list.
});

$('tbody#list').delegate('button#confirm','click',function(){
	var essay_id = $(this).attr('essay_id');	
	data = {
		essay_id : essay_id
	}

	$.post('/project/essay_error_confirm',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('DB Error --> error_essay_chk');
		}
	});
});

$('tbody#list').delegate('button#noterror','click',function(){
	var essay_id = $(this).attr('essay_id');	
	data = {
		essay_id : essay_id
	}
	console.log(data);

	$.post('/project/error_return',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('DB Error --> error_return');
		}
	});
});
</script>
