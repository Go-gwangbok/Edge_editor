<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       	
	       	<?
				if($this->session->userdata('classify') == 0) { //Admin
			?>
		       	<li><a href="/project/">Project</a></li>   
	    	   	<li><a href="/project/status/<?=$pj_id;?>"><?=$pjName;?></a></li>   
	       	<? }else{  // Editor ?> 
	       		<li><a href="/musedata/project/">Project</a></li>   
	       		<li class="akacolor"><?=$pjName;?></li>   	       		       	
	       	<? } ?>
	    </ol> <!-- Navi end -->
	</div>		
	
	<h4 class="text-center"><?=$pjName;?></h4>	
	<ul class="nav nav-tabs" id="pjboard">
	  <li class="tab_action" id="tab_todo"><a href="#todo" data-toggle="tab">To do</a></li>
	  <li class="tab_action" id="tab_com"><a href="#com" data-toggle="tab">Completed</a></li>
	  <li class="tab_action" id="tab_tbd"><a href="#tbd" data-toggle="tab">T.B.D</a></li>
	  <li class="tab_action" id="tab_history"><a href="#history" data-toggle="tab">History</a></li>
	  
	</ul>

	<div class="tab-content">
    	<div class="tab-pane" id="todo">
    		
    	</div>
    	<div class="tab-pane" id="com">
    		
    	</div>
    	<div class="tab-pane" id="tbd">
    		
    	</div>
    	<div class="tab-pane" id="history">
    		
    	</div>
    </div>

	<table class="table table-hover">					
	  	<thead>
			<tr>
				<?
				if($cate == 'share'){
				?>
					<th class="text-center"><span></span><label class="checkbox"><input type="checkbox" id="checkAll">No.</label></th>
				<?
				}else{
				?>
					<th class="text-center">No.</th>
				<?
				}
				?>				
				<th class="text-center">
					<?
					if($cate == 'share'){
					?>
						<!-- Button trigger modal -->
						<button class="btn btn-primary pull-left" id="dis_btn" data-toggle="modal" data-target="#myModal" disabled>
						  Share
						</button> 
					<?
					}
					?>					
					Prompt
				</th>				
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
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel" style="color:black;">Share</h4>
	      </div>
	      <div class="modal-body" id="dismodal">
	        <dl>						                	
            	<dt></dt>
			  	<dd style="color:black;"><strong>Please select an editor!</strong></dd>					  	
			  	<?
			  	foreach ($add_users as $rows) {
			  		$usr_id = $rows->id;
			  		$name = $rows->name;
			  	?>
			  	<label class="checkbox" style="color:black;">
					  <input type="checkbox" id="sel_mem" class="other" value = "<?=$usr_id;?>" conf="true"><?=$name;?>			  	
			  	</label>    					
			  	<?
			  	}
			  	?>
			 </dl>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="share" disabled>Share</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Loading Modal -->
	<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<br><br><br>
		<br>
	  	<div class="modal-body">
	    	<center><img src="/public/img/loading.gif"/></center>
	  	</div>	  
	</div>
	<!-- Loading Modal End -->

</div>
<script>
var page = '<?=$page;?>';
var list = '<?=$list;?>';
var pj_id = '<?=$pj_id;?>';
var editor_id = '<?=$editor_id;?>';
var history_totalcount = '';
var data = '';
var url = '';
var cate = '<?=$cate;?>';

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
		$('li#count').remove();
		$('ul#pjboard').last().append('<li class="pull-right" id="count"><button class="btn btn-default disabled" style="border-color:#f15e22;">Count : '+total_count+'</button></li>');
		//$('li#tab_history').next().append('<p>');

		if(cate == 'share'){
			for(var i = 0; i < json['list'].length; i++) {
				var essay_id = data_list[i]['essay_id'];
				var task = data_list[i]['type'];
				var timer = formatTime(data_list[i]['time']);			

				if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
					var date = data_list[i]['start_date'];				
				}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
					var date = data_list[i]['sub_date'];
				}
				
				$('tbody#list').append('<tr id='+i+'><td><label class="checkbox"><input class="box" type="checkbox" value="'+data_list[i]['essay_id']+'">'+num+'</label></td><td>'																				 
					+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td>'
					+data_list[i]['kind']+'</td><td>'
					+date+'</td><td>'
					+data_list[i]['type']+'</td><td>'
					+timer+'</td>');

				if(data_list[i]['draft'] == 0){ // new
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).append('<td><a href="/text/todo/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-default btn-sm">&nbsp;&nbsp;Revise&nbsp;&nbsp;</button></a></td></tr>');
					}else{
						$('tbody#list tr#'+i).append('<td><a href="#" class="no-uline"><button type="button" class="btn btn-default btn-sm" disabled>&nbsp;&nbsp;Revise&nbsp;&nbsp;</button></a></td></tr>');
					}					
				}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 0){ //draft
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).last().append('<td><a href="/text/draft/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}else{
						$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
					}					
				}else if(data_list[i]['submit'] == 1){ //submit
					if(json['classify'] == 1){
						$('tbody#list tr#'+i).last().append('<td><a href="/text/edit/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
					}else{
						$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');	
					}					
				}
				num++;
			}
		}else{ // todo,com,tbd,history
			for(var i = 0; i < json['list'].length; i++) {
				var essay_id = data_list[i]['essay_id'];
				var task = data_list[i]['type'];
				var timer = formatTime(data_list[i]['time']);			

				if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
					var date = data_list[i]['start_date'];				
				}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
					var date = data_list[i]['sub_date'];
				}

				if(data_list[i]['draft'] == 0 && data_list[i]['discuss'] == 'Y'){ // new
					if(json['classify'] == 1){ // Editor
						var href = '/text_editor/todo/';
						var color = 'red';
						var status = 'New';
					}else{ // Admin
						var href = '/text_editor/todo/';
						var color = 'red';
						var status = 'New';
					}					
				}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 0 && data_list[i]['discuss'] == 'Y'){ //draft
					if(json['classify'] == 1){ // Editor
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/draft/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
						var href = '/text_editor/draft/';
						var color = '#0100FF';
						var status = 'Edit';
					}else{ // Admin
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>');
						var href = '/text_editor/admin_eachdone/';
						var color = '#0100FF';
						var status = 'Edit';
					}					
				}else if(data_list[i]['submit'] == 1){ //submit
					if(json['classify'] == 1){ // Editor
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/edit/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
						var href = '/text_editor/completed/';
						var color = '#1DDB16';
						var status = 'Completed';
					}else{ // Admin
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');	
						var href = '/text_editor/admin_eachdone/';
						var color = '#1DDB16';
						var status = 'Completed';
					}					
				}else if(data_list[i]['discuss'] == 'N'){ // T.B.D
					if(json['classify'] == 1){ // Editor
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/edit/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');
						var href = '/text_editor/tbd/';
						var color = '#FFBB00';
						var status = 'T.B.D';
					}else{ // Admin
						//$('tbody#list tr#'+i).last().append('<td><a href="/text/admin_eachdone/'+editor_id+'/'+essay_id+'/'+task+'" class="no-uline"><button type="button" class="btn btn-success btn-sm">Completed</button></a></td>');	
						var href = '/text_editor/admin_eachdone/';
						var color = '#FFBB00';
						var status = 'T.B.D';
					}					
				}
				
				$('tbody#list').append('<tr id='+i+' class="clickableRow" href="'+href+essay_id+'/'+task+'/'+pj_id+'"><td class="text-center">'+num+'</td><td>'
					+data_list[i]['prompt'].replace(/"/gi,'')+'</td><td class="text-center">'
					+data_list[i]['kind']+'</td><td style="width:90px;">'
					+date+'</td><td class="text-center">'
					+data_list[i]['type']+'</td><td class="text-center">'
					+timer+'<td class="text-center"><h5><font color="'+color+'">'+status+'</font></h5></td></tr>');				
				num++;
			}
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
	if(cate == 'todo' || cate == 'draft'){
		$('li#tab_todo').addClass("active");
		$('div#todo').addClass("active");
	}else if(cate == 'com'){
		$('li#tab_com').addClass("active");
		$('div#com').addClass("active");
	}else if(cate == 'tbd'){
		$('li#tab_tbd').addClass("active");
		$('div#tbd').addClass("active");		
	}else if(cate == 'history'){
		$('li#tab_history').addClass("active");
		$('div#history').addClass("active");		
	}
	data = {
		page : page,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate
	}
	console.log(data);
	url = '/musedata/project/page_list';
	
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
	url = '/musedata/project/page_list';
	
	ajaxPost(url,data); //page list.
});

$('ul.nav').delegate('li.tab_action', 'click', function(){		
	var tab = $(this).attr('id');
	cate = tab.substr(4);	
	console.log(cate);
	data ={
		page : 1,
		list : list,
		pj_id : pj_id,
		editor_id : editor_id,
		cate : cate		
	}
	
	url = '/musedata/project/page_list';	
	ajaxPost(url,data); //page list.
});

//<tbody id="list">
$('tbody#list').delegate('tr.clickableRow', 'click', function(){		      
    window.document.location = $(this).attr("href");      
});


// Share
$("#checkAll").click(function() {
	$('input.box').not(this).prop('checked', this.checked);
    //체크 되어 있는 값 추출
    var checkedVals = $('.box:checkbox:checked').map(function() {
    	return this.value;
	}).get();
	console.log(checkedVals);
    
	var isChecked = false;
	$(':checkbox:checked').each(function(i){
	  isChecked = true;
	});

	if(isChecked){
		console.log('abled');
		$('button#dis_btn').removeAttr('disabled');
	}else{
		console.log('disabled');
		$('button#dis_btn').attr('disabled', 'disabled');	
	}
});


$('tbody#list').delegate('input.box', 'click', function(){		
	var isChecked = false;
	$(':checkbox:checked').each(function(i){
	  isChecked = true;
	});

	if(isChecked){
		console.log('abled');
		$('button#dis_btn').removeAttr('disabled');
	}else{
		console.log('disabled');
		$('button#dis_btn').attr('disabled', 'disabled');	
	}
});   

$('.other').change(function () {
    var c = this.checked ? false : true;    
});


var maxaids = "1";    
$(document).on('click', "input[type=checkbox]#sel_mem", function () {    	
    var bol = $("input:checked#sel_mem").length >= maxaids;
    $("input[type=checkbox]#sel_mem").not(":checked").attr("disabled", bol);        
    var conf = $(this).attr('conf');
    //console.log(conf);           
    if(conf == 'true'){
    	$('button#share').removeAttr('disabled');	
    	console.log($(this).val()); 
    	$(this).attr('conf','false');
    }else{
    	$('button#share').attr('disabled', 'disabled');		
    	$(this).attr('conf','true');
    }        
});

$('#share').click(function(){
	var checkedVals = $('.box:checkbox:checked').map(function() {
    	return this.value;
	}).get();
	console.log(checkedVals);
	var select_mem = $('input:checkbox:checked.other').val();
	console.log(select_mem);

	var data = {
		share_data : checkedVals.toString(),
		select_mem : select_mem,
		editor_id : editor_id,
		pj_id : pj_id
	}
	console.log(data);
	$('#myModal').children().remove();
	$('<br><br><br><br><div class="modal-body"><center><img src="/public/img/loading.gif"/></center></div>').appendTo('#myModal');

	$.post('/project/share',data,function(json){
		var result = json['result'];
		console.log(result);
		if(result){
			window.location.reload(); // 리다이렉트할 주소
        }else{
        	alert("DB -> share Error");	            	
        }                            
	});
});
</script>
