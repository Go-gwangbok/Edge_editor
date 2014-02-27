<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/project/">Project List</a></li>   
	    	<li class="active">Export</li>   	       	
	    </ol>
	    <!-- Button trigger modal -->
		<button class="btn btn-danger pull-right" id="allexport" data-toggle="modal" data-target="#myModal" style="margin-top:-15px; margin-right:15px;" disabled>
		  All Export <span class="badge" id="allcount" style="background-color:transparent;">00</span>
		</button> 									
	</div>			
	<h4 class="text-center">Export (<?=$pjName;?>)</h4>				
	<br>
	<button class="btn btn-primary btn-sm pull-right" id="sorting" disabled>
		MemChk Export <span class="badge" id="memChkCount" style="background-color:transparent;">00</span>
	</button> 									
	<div id="members">		
		<h5>Members&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
	</div>
	
	
	<table class="table table-striped">		 		
	  	<thead>
			<tr>
				<th class="text-center"><span></span><label class="checkbox"><input type="checkbox" id="checkAll">No.</label></th>								
				<th class="text-center">Prompt</th>				
				<th class="text-center">Editor</th>				
				<th class="text-center">Type</th>				
				<th class="text-center">Date</th>				
				<th class="text-center">Task</th>				
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

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <br><br><br><br><br>
      	<div class="modal-body" id="dismodal">	        
    		<center><img src="/public/img/loading.gif"/></center>	  	
      	</div>	  
	</div><!-- /.modal -->
	<!-- Loading Modal End -->
</div>
<script>
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var pj_id = '<?=$pj_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = '<?=$cate;?>';
var editor_id = '';
console.log(cate);

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
		// console.log(json['page']);
		//console.log(json['history_totalcount']);
		// console.log(cate);
		page = json['page'];
		if(json['history_totalcount'] > 0 && json['cate'] != 'sorting'){
			$('span#allcount').html(json['history_totalcount']);	
			$('button#allexport').attr('disabled',false);
		}else{
			$('span#memChkCount').html(json['history_totalcount']);	
			$('button#sorting').attr('disabled',false);
		}		

		$('tbody#list').children().remove();
		var history_list = json['history_list'];	
		var num = (list * page) - (list-1);
		history_totalcount = json['history_totalcount'];
		
		for(var i = 0; i < json['history_list'].length; i++) {
			var essay_id = history_list[i]['essay_id'];
			var task = history_list[i]['type'];
			var timer = formatTime(history_list[i]['time']);			

			if(history_list[i]['draft'] == 0 || history_list[i]['draft'] == 1 ){
				var date = history_list[i]['start_date'];				
			}else if(history_list[i]['draft'] == 1 && history_list[i]['submit'] == 1){
				var date = history_list[i]['sub_date'];
			}
			
			$('tbody#list').append('<tr id='+i+'><td><label class="checkbox"><input class="box" type="checkbox" value="'+history_list[i]['essay_id']+'">'+num+'</label></td><td>'																				 
				+history_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
				+history_list[i]['name']+'</td><td>'
				+history_list[i]['kind']+'</td><td>'
				+date+'</td><td>'
				+history_list[i]['type']+'</td><td>'
				+timer+'</td>');		
			
			$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+history_list[i]['usr_id']+'/'+history_list[i]['essay_id']+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');	
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
	var members = {
		pj_id : pj_id
	}
	console.log(members);	
	$.post('/project/member_list',members,function(json){
		//console.log(json['result']);
		for(var i = 0; i < json['result'].length; i++){
			$('div#members h5').append('<input type="checkbox" id="member_list" usr_id="'+json['result'][i]['usr_id']+'">&nbsp; '+json['result'][i]['name']+'&nbsp;&nbsp;&nbsp;&nbsp;');				
		}		
	});		

	data = {
		page : page,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate

	}
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

$('button#allexport').click(function(){
	console.log('all export');
});

 
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
		url = '/project/sorting';
		
	}else{
		$('span#memChkCount').html('00');	
		$('button#sorting').attr('disabled',true);
		data = {
			page : page,
			list : list,
			pj_id : pj_id,
			editor_id : editor_id,
			cate : cate
		}
		url = '/project/page_list';
	}
	ajaxPost(url,data);	//page list.
});

</script>