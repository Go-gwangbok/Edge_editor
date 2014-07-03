<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/service">Service</a></li>   
		    <li><a href="/service/export/<?=$month;?>/<?=$year;?>">Export</a></li>   
	    	<li class="akacolor">Error list</li>   	       	
	    </ol>
		<h3 class="text-center"><?=$str_month;?></h3>
	</div>
		<div id="error">		
	    	<!-- ajax -->
		</div>			
	<br><br>	
	<table class="table table-hover">		 		
	  	<thead>
			<tr>
				<th class="text-center"><span></span>No.</th>								
				<th class="text-center">Prompt</th>				
				<th class="text-center">Editor</th>				
				<th class="text-center">Type</th>				
				<th class="text-center">Date</th>								
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
var service_id = '<?=$service_id;?>';
var cate = '<?=$cate;?>';
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var month = '<?=$month;?>';
var year = '<?=$year;?>';


$(document).ready(function(){		
	var values = {
		service_id : service_id,
		month : month,
		year : year,				
		page : page
	}
	console.log(values);			
	//console.log(cate);
	
	url = '/service/service_export_errorlist';

	ajaxPost(url,values);	//page list.				
});

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	console.log(page_num);
	data ={
		service_id : service_id,
		page : page_num,
		list : list,	
		year : year,
		month : month		
	}
	console.log(data);
	url = '/service/service_export_errorlist';
	ajaxPost(url,data); //page list.
});

$('tbody#list').delegate('tr.rowbtn', 'click', function(){	
	window.document.location = $(this).attr('href');
});

function ajaxPost(url,data){
	$.post(url,data,function(json) {						
		cate = json['cate'];				
		page = json['page'];		
		$('div#error').children().remove();
		$('div#error').append('<button class="btn btn-default pull-right" disabled style="border-color:#f15e22;"><font color="red"><b> Total : '+json['data_count']+'</b></font></button>');
		//var list = json['page_list'];
		var datas = json['data_list'];			
		var num = (list * page) - (list-1);
		data_count = json['data_count'];	
		$('tbody#list').children().remove();
		$.each(datas,function(i,values){
			var essay_id = values['essay_id'];
			var prompt = values['prompt'];
			var name = values['name'];
			var kind = values['kind'];
			var date = values['sub_date'];
			var type = values['type'];			

			$('tbody#list').append('<tr class="rowbtn" style="cursor:pointer;" href="/errordata/service_error_edit/'+essay_id+'/'+type+'/'+month+'/'+year+'"><td class="text-center">'+num+'</td><td>'
					+prompt.replace(/"/gi,'')+'</td><td class="text-center">'
					+name+'</td><td class="text-center">'
					+kind+'</td><td style="width:95px;" class="text-center">'					
					+date+'</td><td width="80px;" class="text-center"><button class="btn btn-danger btn-sm">Edit error</button></td>');
			num++;

		}); // Each End		
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