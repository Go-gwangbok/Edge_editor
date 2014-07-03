<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/service">Service</a></li>   		    
		    <li><a href="/service/serviceType/<?=$service_name;?>"><?=ucfirst($service_name);?></a></li>   
		    <li><a href="/service/enter/<?=$service_name;?>/<?=$int_month;?>/<?=$year;?>"><?=$year.' - '.$str_month?></a></li>   		    
	    	<li class="akacolor">Completeds</li>   	       	
	    </ol>		
	</div>	
	<h3 class="text-center"><?=$name;?></h3>
	<button class="btn btn-default pull-right" style="border-color:#f15e22; margin-top:-40px;" id="total" disabled></button> 
	<br>	
	<table class="table table-hover">		 		
	  	<thead>
			<tr>
				<th class="text-center"><span></span>No.</th>								
				<th class="text-center">Prompt</th>								
				<th class="text-center">Type</th>
				<th class="text-center">Price Kind</th>
				<th class="text-center" style="width:105px;">Submit</th>				
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
<script type="text/javascript">
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var month = '<?=$int_month?>';
var year = '<?=$year?>';
var usr_id = '<?=$usr_id?>';
var service_id = '<?=$service_id;?>';
var service_name = '<?=$service_name;?>';

$(document).ready(function(){		
	var values = {				
		page : page,
		month : month,
		year : year,
		usr_id : usr_id,
		service_id : service_id
	}
	console.log(values);				
	url = '/service/get_service_member_comp';
	ajaxPost(url,values);	//page list.				
});

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	console.log(page_num);

	var data = {		
		page : page_num,
		month : month,
		year : year,
		usr_id : usr_id,
		service_id : service_id			
	}
	//console.log(data);	
	url = '/service/get_service_member_comp';
	ajaxPost(url,data); //page list.
});

$('tbody#list').delegate('tr.rowbtn', 'click', function(){	
	window.document.location = $(this).attr('href');
});

function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json['page']);		
		console.log(json['data_count']);						
		cate = json['cate'];				
		page = json['page'];				
		
		var data_list = json['data_list'];			
		var num = (list * page) - (list-1);
		data_count = json['data_count'];	

		$('button#total').empty();
		$('button#total').append('<font color="red">Count : '+data_count+'</font>');

		$('tbody#list').children().remove();
		for(var i = 0; i < json['data_list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var kind = data_list[i]['kind'];
			var id = data_list[i]['id'];
			var price_kind = data_list[i]['price_kind'];
			
			$('tbody#list').append('<tr id='+i+'  style="cursor:pointer;" class="rowbtn" href="/text_editor/service_comp/'+service_name+'/'+id+'/'+month+'/'+year+'"><td class="text-center">'+num+'</td><td>'																				 
				+essay_id+"::"+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td class="text-center">'				
				+kind.toUpperCase()+'</td><td class="text-center">'				
				+price_kind+'</td><td style="width:95px;" class="text-center">'
				+data_list[i]['sub_date']+'</td><td width="80px;" class="text-center"><button class="btn btn-success btn-sm">Completed</button></td>');					
			num++;			
		}								
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
</script>