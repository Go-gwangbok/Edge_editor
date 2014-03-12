<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/project/">Project List</a></li>   
		    <li><a href="/project/export/<?=$pj_id;?>">Export</a></li>   
	    	<li class="active">Error_list</li>   	       	
	    </ol>
	    
	    <!-- Button trigger modal -->	    
	    <div id="error">	    	
			
		</div>			
	<h4 class="text-center">Error list (<?=$pjName;?>)</h4>					
	<br>
	
	<table class="table table-striped">		 		
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
</div>
<script type="text/javascript">
var pj_id = '<?=$pj_id;?>';
var cate = '<?=$cate;?>';
var page = '<?=$page;?>';

function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json['page']);		
		console.log(json['page_list']);				
		cate = json['cate'];
		page = json['page'];	

		var page_list = json['page_list'];
		var data_count = json['data_count'];

		//console.log(page_list);	

		$('div#error').prepend('<h4 class="text-right"><font color="red"> Total : '+json['data_count']+'</font></h4>');
		//console.log(JSON.parse(json['list']));
		var data_list = json['data_list'];
		//console.log(JSON.parse(data_list));
		
		
		for(var i = 0; i < json['data_count']; i++){			
			console.log(data_list[i]['prompt']);

			// $('tbody#list').append('<tr id='+i+'><td>'+(i+1)+'</td><td>'
			// 	+data[i]['prompt']+'</td><td>'
			// 	+data[i]['name']+'</td><td>'
			// 	+data[i]['kind']+'</td><td>'					
			// 	+data[i]['sub_date']+'</td><td>'					
			// 	+data[i]['type']+'</td><td><a href="/text/error/'+data[i]['usr_id']+'/'+data[i]['essay_id']+'/'+data[i]['type']+'/'+pj_id+'" class="no-uline"><button type="button" class="btn btn-danger btn-sm">Error Edit</button></a></td>');					
		}

		$('div#pageblock').children().remove();			
			
		pageBlock(page,page_list,data_count); // page button.	
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
	
	url = '/project/get_error_data';

	ajaxPost(url,values);	//page list.				
});

</script>