<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/musedata/project/">Project</a></li>   
	    	<li class="akacolor">Export</li> 	
	    </ol>	    
	    <label class="pull-right" style="margin-right:10px;" id="total"></label>				
	    <h3 class="text-center"><?=$pjName;?></h3>	    
	    <!-- Button trigger modal -->	    
	    <div id="error">	    	
			<button class="btn btn-danger pull-right" id="allexport" style="margin-top:-10px; margin-right:15px;" disabled> Export <span class="badge" id="allcount" style="background-color:transparent;">00</span><span class="glyphicon glyphicon-download"></span>
			</button> 										
		</div>				
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
	</div>
	<!-- Loading Modal End -->

<script type="text/javascript">
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var pj_id = '<?=$pj_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = '<?=$cate;?>';
var editor_id = '';
var all_essay_id = '';
var error_id = '';
var done_id = '';

//console.log(cate);

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
		// console.log(json['edi']);
		// console.log(json['page']);
		// console.log(json['history_totalcount'].length);
		// console.log(json['cate']);
		// console.log(json['history_list']);
		// console.log(json['done_id']);
		cate = json['cate'];
		page = json['page'];	

		$('#total').empty();
		$('#total').append('Total : '+ json['export_total'] + '&nbsp;&nbsp;');

		if(cate == 'error_export'){
			$('#myModal').modal('hide');
			$('div#pageblock').children().remove();
			//var pj_id = json['pj_id'];
			$('span#allcount').remove();	
			$('button#allexport').remove();					
			//$('div#error').prepend('<button class="btn btn-primary pull-right" id="back" style="margin-top:-15px; margin-right:1px;">Back</button>');

			history_list = json['history_totalcount'];
			$('tbody#list').children().remove();
			for(var i = 0; i < json['history_totalcount'].length; i++){
				var essay_id = history_list[i]['essay_id'];
				var task = history_list[i]['type'];
				var timer = formatTime(history_list[i]['time']);			

				if(history_list[i]['draft'] == 0 || history_list[i]['draft'] == 1 ){
					var date = history_list[i]['start_date'];				
				}else if(history_list[i]['draft'] == 1 && history_list[i]['submit'] == 1){
					var date = history_list[i]['sub_date'];
				}
				
				$('tbody#list').append('<tr id='+i+'><td>'+(i+1)+'</td><td>'																				 
						+history_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
						+history_list[i]['name']+'</td><td>'
						+history_list[i]['kind']+'</td><td>'
						+date+'</td><td>'
						+history_list[i]['type']+'</td><td>'
						+timer+'</td>');						
				$('tbody#list tr#'+i).last().append('<td><a href="/text_editor/error/'+history_list[i]['usr_id']+'/'+history_list[i]['essay_id']+'/'+task+'/'+pj_id+'" class="no-uline"><button type="button" class="btn btn-danger btn-sm">Error Edit</button></a></td>');
			}

		}else{ // Done
			$('#myModal').modal('hide');
			if(json['history_totalcount'] > 0 && json['cate'] != 'sorting'){											
					$('span#allcount').html(json['history_totalcount']);	
					$('button#allexport').attr('disabled',false);					
					done_id = json['done_id'];
			}else{				
				$('span#memChkCount').html(json['history_totalcount']);	
				$('button#sorting').attr('disabled',false);
			}		

			$('tbody#list').children().remove();
			var history_list = json['history_list'];	
			var num = (list * page) - (list-1);
			history_totalcount = json['history_totalcount'];
			//console.log(history_totalcount);

			if(history_totalcount == 0 ){						
				$('button#sorting').attr('disabled',true);	
			}

			for(var i = 0; i < json['history_list'].length; i++) {
				var essay_id = history_list[i]['essay_id'];
				var task = history_list[i]['type'];
				var timer = formatTime(history_list[i]['time']);			

				if(history_list[i]['draft'] == 0 || history_list[i]['draft'] == 1 ){
					var date = history_list[i]['start_date'];				
				}else if(history_list[i]['draft'] == 1 && history_list[i]['submit'] == 1){
					var date = history_list[i]['sub_date'];
				}
				
				$('tbody#list').append('<tr class="com" id='+i+' href="/text_editor/comp/'+history_list[i]['essay_id']+'/'+task+'"><td>'+num+'</td><td>'																				 
						+history_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
						+history_list[i]['name']+'</td><td>'
						+history_list[i]['kind']+'</td><td>'
						+date+'</td><td>'
						+history_list[i]['type']+'</td><td>'
						+timer+'</td>');						
				$('tbody#list tr#'+i).last().append('<td><b><font color="#0100FF">Completed</font></b></td>');								
				num++;				
				$('div#pageblock').children().remove();
			
				pageBlock(page,list,history_totalcount); // page button.
			}
		}
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
	$('#myModal').modal('show');
	var members = {
		pj_id : pj_id
	}
	console.log(members);	
	$.post('/musedata/project/member_list/all/0',members,function(json){
		//console.log(json['result']);	
		console.log(json['error']);
		//console.log('error_id : ' + json['error_id']);						
		// console.log('done_id : ' + json['done_id']);				
		//console.log('error_slash : '+json['int']);
		if(json['error'] > 0){ // Error			
			$('div#error').append('<a href="/error/export_error/'+pj_id+'"><button class="btn btn-danger pull-left" id="errorbtn" style="margin-top:-10px; margin-left:15px;">Error List<span class="badge" style="background-color:transparent;">'+json['error']+'</span></button></a>');			
			error_id = json['error_id'].toString();			
		}	

		/*** Member list box ***/
		// for(var i = 0; i < json['result'].length; i++){
		// 	$('div#members h5').append('<input type="checkbox" id="member_list" usr_id="'+json['result'][i]['usr_id']+'">&nbsp; '+json['result'][i]['name']+'&nbsp;&nbsp;&nbsp;&nbsp;');				
		// }		

		data = {
			page : page,
			list : list,
			pj_id : pj_id,
			editor_id : editor_id,
			cate : cate,
			done_id : json['done_id'].toString()
		}
		//console.log(data);
		url = '/musedata/project/page_list';

		ajaxPost(url,data);	//page list.			
	});		

	
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

	if(cate == 'sorting') {
		url = '/musedata/project/sorting';
	} else {
		url = '/musedata/project/page_list';	
	}	
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
	var pj_name = '<?=$pjName;?>';	
	data = {
		done : done_id,
		pj_name : pj_name
	}
	console.log(data);
	var download_url  = '/musedata/project/all_export?done='+ done_id +'&pj_name='+pj_name;
	
	$.post('/musedata/project/all_export',data,function(json){
		console.log(json['result']);
		$('#myModal').modal('show');
		
		if(json['result']){
			window.location = '/musedata/project/download?pjname='+pj_name;			
		}else{
			console.log(json['result']);
			alert('This file is not available for download.');
		}		
		$('#myModal').modal('hide');
	});	
});

$('div#error').delegate('button#back', 'click', function(){			
	window.location.reload();
});

$('tbody#list').delegate('tr.com', 'click', function(){		      
    window.document.location = $(this).attr("href");      
});

</script>