<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>
	       	<li><a href="/service/">Service</a></li>	       		       	
    	   	<li class="akacolor">T.B.D</li>
	    </ol> <!-- Navi end -->
	</div>					
	<h3 class="text-center"> <?=$service_name;?> T.B.D </h3>	
	<button class="btn btn-default pull-right" style="border-color:#f15e22; margin-top:-40px;" id="total" disabled></button> 
	<br>
	<table class="table table-hover">					
		
	  	<thead>
			<tr>
				<th class="text-center">No.</th>
				<th class="text-center">Prompt</th>							
				<th class="text-center">Editor</th>				
				<th class="text-center" style="width:105px;">Date</th>								
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
var month = '<?=$int_month;?>';
var year = '<?=$year;?>';
var service_id = '<?=$service_id;?>';
var service_name = '<?=$service_name;?>';
var url = '';

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
		//console.log(json['total_count']);
		page = json['page'];

		$('tbody#list').children().remove();
		var data_list = json['list'];	
		var num = (list * page) - (list-1);
		total_count = json['total_count'];
		$('button#total').empty();
		$('button#total').append('<font color="red">Count : '+total_count+'</font>');
		//$('li#tab_history').next().append('<p>');	

		for(var i = 0; i < json['list'].length; i++) {
			var id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];
			var timer = formatTime(data_list[i]['time']);	
			var prompt = data_list[i]['prompt'];
			var raw_txt = data_list[i]['raw_txt'];
			var kind_name = data_list[i]['kind'];
			var kind = data_list[i]['kind'];

			if($.isNumeric(prompt)){
				prompt = prompt+raw_txt.substr(0,120);
			}

			if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
				var date = data_list[i]['start_date'];				
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
				var date = data_list[i]['sub_date'];
			}
			
			$('tbody#list').append('<tr id='+i+' class="clickableRow" style="cursor:pointer;" href="/text_editor/service_comp/'+service_name+'/'+id+'/'+month+'/'+year+'"><td class="text-center">'+num+'</td><td>'																				 
				+prompt.replace(/"/gi,'')+'</td><td class="text-center">'
				+kind_name.toUpperCase()+'</td><td class="text-center">'
				+date+'</td>'
				+data_list[i]['type']+'</td><td class="text-center"><button class="btn btn-warning btn-sm">&nbsp;&nbsp;T.B.D&nbsp;&nbsp;</button></td>');				
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
	data = {
		page : page,
		list : list,
		month : month,
		year : year,
		service_id : service_id
	}
	console.log(data);
	url = '/service/get_service_tbd';
	
	ajaxPost(url,data);	//page list.
});

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	
	data = {
		page : page,
		list : list,
		month : month,
		year : year,
		service_id : service_id
	}
	console.log(data);
	url = '/service/get_service_tbd';
	
	ajaxPost(url,data); //page list.
});

$('tbody#list').delegate('tr.clickableRow', 'click', function(){		      
    window.document.location = $(this).attr("href");      
});
</script>
