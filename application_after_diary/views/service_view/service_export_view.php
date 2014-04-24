<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/service/">Service</a></li>   		    
	    	<li class="akacolor">Export</li> 	
	    </ol>	    
	    <label class="pull-right" style="margin-right:10px;" id="total">Total : <?=$total;?>&nbsp;&nbsp;</label>				
	    <h3 class="text-center"><?=$str_month;?></h3>	    
	    <a href="/errordata/service_export_error/<?=$month;?>/<?=$year;?>"><button class="btn btn-danger pull-left" id="errorbtn" style="margin-top:-10px; margin-left:15px;">Error List<span class="badge" style="background-color:transparent;"><?=$total-$export_count;?></span></button></a>
		<button class="btn btn-danger pull-right" id="allexport" style="margin-top:-10px; margin-right:15px;"> Export <span class="badge" id="allcount" style="background-color:transparent;"><?=$export_count;?></span><span class="glyphicon glyphicon-download"></span></button>		
	</div> <!-- Div row -->
	<br>

	<!-- 
		멤버 체크별로 sorting 가능 v2.1에 적용.
		<button class="btn btn-primary btn-sm pull-right" id="sorting" data-toggle="modal" data-target="#myModal" disabled>
			MemChk Export <span class="badge" id="memChkCount" style="background-color:transparent;">00</span>
		</button> 									 -->
	<!-- <div id="members">		
		<h5>Members&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
	</div> -->	
	<table class="table table-hover">		 		
	  	<thead>
			<tr>
				<th class="text-center"><span></span>No.</th>								
				<th class="text-center">Prompt</th>				
				<th class="text-center" width="85px;">Editor</th>				
				<th class="text-center">Type</th>				
				<th class="text-center" width="105px;">Date</th>				
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

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <br><br><br><br><br>
      	<div class="modal-body" id="dismodal">	        
    		<center><img src="/public/img/loading.gif"/></center>	  	
      	</div>	  
	</div>
	<!-- Loading Modal End -->

<script type="text/javascript">
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var data = '';
var url = '';
var cate = '<?=$cate;?>';
var year = '<?=$year?>';
var month = '<?=$month?>';
var str_month = '<?=$str_month;?>';
console.log(cate);

function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json['year']);
		console.log(json['data_count']);		
		page = json['page'];			

		$('tbody#list').children().remove();
		var data_list = json['data_list'];	
		var num = (list * page) - (list-1);
		data_count = json['data_count'];						

		for(var i = 0; i < json['data_list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];			

			if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
				var date = data_list[i]['start_date'];				
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
				var date = data_list[i]['sub_date'];
			}
			
			$('tbody#list').append('<tr class="com" id='+i+' style="cursor:pointer;" href="/text_editor/service_comp/export/'+essay_id+'/'+task+'/'+month+'/'+year+'"><td>'+num+'</td><td>'																				 
					+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
					+data_list[i]['name']+'</td><td>'
					+data_list[i]['kind']+'</td><td>'
					+date+'</td>');						
			$('tbody#list tr#'+i).last().append('<td><button class="btn btn-success btn-sm">Completed</button></td>');								
			num++;				
			$('div#pageblock').children().remove();
		
			pageBlock(page,list,data_count); // page button.
		}		
	});
}

function pageBlock(page,list,data_count){

	var b_pageNum_list = 10; //블럭에 나타낼 페이지 번호 갯수
	var block = Math.ceil(page/b_pageNum_list); //현재 리스트의 블럭 구하기
    var b_start_page = ( (block - 1) * b_pageNum_list ) + 1; //현재 블럭에서 시작페이지 번호    
    var b_end_page = b_start_page + b_pageNum_list - 1; //현재 블럭에서 마지막 페이지 번호		 	
    var total_page =  Math.ceil(data_count/list); //총 페이지 수

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
		year : year,	
		month : month
	}
	console.log(data);
	url = '/service/get_export_data';
	ajaxPost(url,data);	//page list.				
});

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	console.log(page_num);
	var mems = $("input#member_list:checked").map(function() {
    	return $(this).attr('usr_id');
	}).get();
	console.log(mems);

	data ={
		page : page_num,		
		year : year,	
		month : month		
	}
	console.log(data);

	url = '/service/get_export_data';
	ajaxPost(url,data); //page list.
});
 
//멤버 체크별로 sorting 가능 v2.1에 적용.
$(document).on('click', "input[type=checkbox]#member_list", function () {    	     
	// 체크 되어 있는 값 추출
    var mems = $("input#member_list:checked").map(function() {
    	return $(this).attr('usr_id');
	}).get();
	
	if(mems.length > 0){
		data = {
			page : page,
			list : list,
			pj_id : pj_id,
			editor_id : mems.toString(),
			cate : 'sorting'
		}		
		console.log(data);
		url = '/musedata/project/sorting';
		
	}else{
		console.log('a');
		$('span#memChkCount').html('00');	
		$('button#sorting').attr('disabled',true);
		data = {
			page : page,
			list : list,
			pj_id : pj_id,
			editor_id : editor_id,
			cate : 'export'
		}		
		url = '/musedata/project/page_list';			
	}
	ajaxPost(url,data);	//page list.
});

// 멤버 체크별로 sorting 가능 v2.1에 적용.
$('button#sorting').click(function(){	
	// var mems = $("input[type=checkbox].box").map(function() {
 //    	return $(this).val();¡™¡™
	// }).get();
	console.log(all_essay_id);
	data = {				
		essay_id : all_essay_id
		//essay_id : 10900
		//essay_id : 11631
		//essay_id : 11556
	}		
	console.log(data);
	url = '/musedata/project/memchkExport';

	$.post(url,data,function(json){
		if(json['update'] == 'error'){
			alert('DB-->Update error');
		}
		console.log(json['post_ids']);
		console.log('error_id : '+json['error_id']);
		console.log('count : ' + json['error']);
		console.log(json['err_utag']);
		console.log(json['tag_count']);	
		console.log(json['content']);				
		console.log(json['done_data']);	

		if(json['error'] > 0){
			$('#myModal').modal('hide');
			
			$('div#members').remove();
			$('table').remove();
			$('button').remove();
			$('div#error').append('Error count : '+json['error']+'<button id="errorlist" class="btn btn-danger" style="margin-top:-15px; margin-right:15px;">Export error list</button><input type="hidden" id="error_id" value="'+json['error_id'].toString()+'">');			
		}else{
			alert('ok');
		}						
	});
});

$('button#allexport').click(function(){		
	
	data = {		
		year : year,
		month : month
	}
	console.log(data);
	
	$.post('/service//all_export',data,function(json){
		console.log(json['result']);
		// console.log(json['month']);
		// console.log(json['year']);

		if(json['result']){
			window.location = '/service/download?year='+year+'&month='+month;			
		}else{
			console.log(json['result']);
			alert('This file is not available for download.');
		}				
	});	
});


$('tbody#list').delegate('tr.com', 'click', function(){		      
    window.document.location = $(this).attr("href");      
});

</script>