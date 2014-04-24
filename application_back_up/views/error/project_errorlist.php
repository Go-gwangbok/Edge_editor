<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
	       	<li><a href="/musedata/project/">Project</a></li>   
    	   	<li><a href="/musedata/project/members/<?=$pj_id;?>">Members</a></li>   
	       	<li class="akacolor">Error List</li>   	       		       	
	    </ol>
	</div>	
	<table class="table table-striped">
		<h4 class="text-center">Error List</h4>				
	  	<thead>
			<tr>
				<th class="text-center">No.</th>				
				<th class="text-center">Prompt</th>				
				<th class="text-center">Editor</th>				
				<th class="text-center" style="width:105px;">Date</th>				
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
		console.log(json['data_count']);				
		page = json['page'];

		$('tbody#list').children().remove();
		var data_list = json['data_list'];	
		var num = (list * page) - (list-1);
		data_count = json['data_count'];

		for(var i = 0; i < json['data_list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];
			//console.log(data_list[i]['submit']);

			if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
				var date = data_list[i]['start_date'];				
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
				var date = data_list[i]['sub_date'];
			}
			
			$('tbody#list').append('<tr id='+i+'><td class="text-center">'+num+'</td><td>'
				+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
				+data_list[i]['name']+'</td><td>'
				+date+'</td><td>'
				+data_list[i]['type']+'</td>');

			$('tbody#list tr#'+i).last().append('<td class="text-center"><a href="/text_editor/error/'+essay_id+'/'+task+'/'+pj_id+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;Error&nbsp;&nbsp;&nbsp;</button></a></td><td class="text-center"><button class="btn btn-sm btn-danger" id="yes" essay_id="'+essay_id+'">Yes</button> <button class="btn btn-sm btn-danger" id="not_error" essay_id="'+essay_id+'" essay_type="'+data_list[i]['type']+'">Return</button></td>');
			num++;
		}				
		//console.log(page,list,data_count);
		$('div#pageblock').children().remove();
		
		pageBlock(page,list,data_count); // page button.
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
	url = '/error/get_error_data';
	
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
	url = '/error/get_error_data';
	
	ajaxPost(url,data); //page list.
});

// Yes Button
$('tbody#list').delegate('button#yes','click',function(){
	var essay_id = $(this).attr('essay_id');		
	data = {
		essay_id : essay_id	
	}
	console.log(data);
	$.post('/error/error_yes',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('DB Error --> error_essay_chk');
		}
	});
});

$('tbody#list').delegate('button#not_error','click',function(){
	var essay_id = $(this).attr('essay_id');	
	var type = $(this).attr('essay_type');	
	data = {
		essay_id : essay_id,
		type : type
	}
	console.log(data);

	$.post('/error/error_return',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('DB Error --> error_return');
		}
	});
});
</script>
