<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/musedata/project/">Project List</a></li>   
		    <li><a href="/musedata/project/export/<?=$pj_id;?>">Export</a></li>   
	    	<li class="akacolor">Error list</li>   	       	
	    </ol>
	    
	    <!-- Button trigger modal -->	    
	    <div id="error">		
	    	<!-- ajax -->
		</div>			
		
		<h3 class="text-center"><?=$pjName;?></h3>
	</div>
	<br>	
	<table class="table table-hover">		 		
	  	<thead>
			<tr>
				<th class="text-center"><span></span>No.</th>								
				<th class="text-center">Prompt</th>				
				<th class="text-center">Editor</th>				
				<th class="text-center">Type</th>				
				<th class="text-center">Date</th>				
				<th class="text-center">Task</th>								
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
var pj_id = '<?=$pj_id;?>';
var cate = '<?=$cate;?>';
var page = '<?=$page;?>';
var list = '<?=$list;?>';

function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json['page']);		
		console.log(json['data_count']);						
		// cate = json['cate'];				
		page = json['page'];		
		$('div#error').children().remove();
		$('div#error').prepend('<button class="btn btn-default pull-right" style="margin-right:10px;" disabled><font color="red"> Total : '+json['data_count']+'</font></button>');
		//var list = json['page_list'];
		var data_list = json['data_list'];			
		var num = (list * page) - (list-1);
		data_count = json['data_count'];	
		$('tbody#list').children().remove();
		for(var i = 0; i < json['data_list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];			
			
			$('tbody#list').append('<tr id='+i+' class="rowbtn" href="/text_editor/error/'+data_list[i]['usr_id']+'/'+data_list[i]['essay_id']+'/'+data_list[i]['type']+'/'+pj_id+'"><td class="text-center">'+num+'</td><td>'																				 
					+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td class="text-center">'
					+data_list[i]['name']+'</td><td class="text-center">'
					+data_list[i]['kind']+'</td><td style="width:95px;" class="text-center">'
					+data_list[i]['sub_date']+'</td><td>'
					+data_list[i]['type']+'</td><td width="80px;" class="text-center"><font color="red">Error Edit</font></a></td>');					
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

$(document).ready(function(){		
	var values = {
		pj_id : pj_id,
		cate : cate,
		page : page
	}
	console.log(values);			
	
	url = '/musedata/project/get_error_data';

	ajaxPost(url,values);	//page list.				
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
		list : list,
		pj_id : pj_id,
		editor_id : mems.toString(),
		cate : cate		
	}
	console.log(data);	
	url = '/musedata/project/get_error_data';	
	ajaxPost(url,data); //page list.
});

$('tbody#list').delegate('tr.rowbtn', 'click', function(){	
	window.document.location = $(this).attr('href');
});

</script>