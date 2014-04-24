<div class="container" style="margin-top:-15px;">   
  	<div class="row">    
      	<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>
        	<li><a href="/setting/info">Setting</a></li>   
        	<li class="akacolor">Templet setting</li>   
    	</ol>                        
  	</div>  <!-- Nav or Head title End -->  
  	<h3 class="text-center" id="title"><?=strtoupper($kind);?> Templet setting</h3>
  	<hr>
  	<br>
  	<div class="col-md-12">
            <h4>Active Tab Setting</h4>
	</div>   
	<div class="row">    
    	<div class="col-md-12" style="height:37px;" id="tabs">    		
    		<!-- Ajax -->       			
		</div>  
	</div>       
    <br><br><br>
  	<div class="col-md-12">
            <h4>Tag Button Setting
            	<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" style="margin-top:-10px;">Create Tag</button> 
        	</h4>
	</div>   
	<div class="row">    
    	<div class="col-md-12" style="height:37px;" id="tags">    		
    		<!-- Ajax -->       			
		</div>  
	</div>       
    <br><br><br>
    <div class="col-md-12">
            <h4>Score Setting
            	<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#scomyModal" style="margin-top:-10px;">Create Score</button> 
            </h4>
	</div>   
    <div class="row">  
	    <div class="col-md-12" id="scores">
	    	<!-- Ajax -->
	    </div>  
	</div>
	<br>
    <div class="col-md-12">
    	<button class="btn btn-primary btn-sm pull-right" id="save">SAVE</button>
    </div>	  
    <!-- Tag Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel" style="color:black;">Create Tag</h4>
	      </div>
	      <div class="modal-body" id="dismodal">
	        <dl>						                	
            	<dt></dt>
			  	<dd style="color:black;"><strong>Tag 이름을 입력하세요!(2글자 이상 입력하세요!)</strong></dd>					  	
			  	<br>
			  	<label class="text" style="color:black;">
					  <input type="text" class="newtag" conf="true">
			  	</label>    					
			  	
			 </dl>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="create" disabled>Create</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal --> 

	<!-- score Modal -->
	<div class="modal fade" id="scomyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel" style="color:black;">Create Score</h4>
	      </div>
	      <div class="modal-body" id="dismodal">
	        <dl>						                	
            	<dt></dt>
			  	<dd style="color:black;"><strong>Score 이름을 입력하세요!(2글자 이상 입력하세요!)</strong></dd>					  	
			  	<br>
			  	<label class="text" style="color:black;">
					  <input type="text" class="newsco" conf="true">
			  	</label>    					
			  	
			 </dl>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="scocancel" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="scocreate" disabled>Create</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal --> 				
</div>
<script type="text/javascript">
var kind_id = <?=$kind_id;?>;
$(document).ready(function(){
	$.post('/setting/info/get_typevals',{kind_id : kind_id},function(json){				
		var tags = json['get_setup_tag'];
		var scores = json['scores'];
		var tabs = json['tabs'];
		console.log(tabs);	
		console.log(json['get_setup_tag']);		
		console.log(json['scores']);		

		$.each(tabs,function(i,values){			
			var element_id = values['element_id'];
			var element = values['element'];
			var view_element = values['view_ele'];
			var active = values['active']

			if(active == 0){
				$('div#tabs').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="tabs" id="b'+element_id+'" checked="checked"><br>'
										+'<span id="tab'+element_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+view_element.toUpperCase()+'</span>'										
										+'</div>')				
			}else{
				$('div#tabs').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="tabs" id="b'+element_id+'"><br>'
										+'<span id="tab'+element_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+view_element.toUpperCase()+'</span>'										
										+'</div>')								
			}
		}); // each end	
		
		$.each(tags,function(i,values){
			var chk = values['chk'];
			var id = values['tags_id']; // tag_id
			var tag = values['tag'];

			if(chk == 'Y'){
				$('div#tags').append('<div class="col-md-2" style="margin-top:12px;">'
    								+'<input type="checkbox" class="tags_chk" style="margin-left:22px;" id="t'+id+'" checked="checked" tagname="'+tag.toLowerCase()+'"><br>'
									+'<button class="btn btn-success btn-sm t'+id+'" style="margin-top:2px; " status="true"> &lt; '+tag.toUpperCase()+' &gt; </button>'
									+'<button id="tag_del" tagid="t'+id+'" class="btn btn-danger btn-sm " style="margin-right:5px; margin-top:2px; " status="true">&times;</button>'
									+'</div>');

			}else{
				$('div#tags').append('<div class="col-md-2" style="margin-top:12px;">'
    								+'<input type="checkbox" class="tags_chk" style="margin-left:22px;" id="t'+id+'" tagname="'+tag.toLowerCase()+'"><br>'
									+'<button class="btn btn-success btn-sm t'+id+'" style="margin-top:2px; " status="true" disabled> &lt; '+tag.toUpperCase()+' &gt; </button>'
									+'<button id="tag_del" tagid="t'+id+'" class="btn btn-danger btn-sm" style="margin-right:5px; margin-top:2px; " status="true">&times;</button>'									
									+'</div>');
			}			
		}); // each end
		
		$.each(scores,function(i,values){
			var sco_id = values['score_id'];
			var sco_chk = values['chk'];
			var sco_type = values['name'];

			if(sco_chk == 'Y'){
				$('div#scores').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="score" id="s'+sco_id+'" checked="checked" scorename="'+sco_type.toLowerCase()+'"><br>'
										+'<span id="s'+sco_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+sco_type.toUpperCase()+'</span>'
										+'<button id="sco_del" scoid="s'+sco_id+'" class="btn btn-danger btn-sm " style="margin-right:5px; margin-top:2px; " status="true">&times;</button>'
										+'</div>');

			}else{
				$('div#scores').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="score" id="s'+sco_id+'" scorename="'+sco_type.toLowerCase()+'"><br>'
										+'<span id="s'+sco_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+sco_type.toUpperCase()+'</span>'
										+'<button id="sco_del" scoid="s'+sco_id+'" class="btn btn-danger btn-sm " style="margin-right:5px; margin-top:2px; " status="true">&times;</button>'
										+'</div>');
			}
		}); // each end
		
	});  //post end
});

//Tag Del button
$(document).delegate('button#tag_del','click',function(){		
	var tagid = $(this).attr('tagid');
	var data = {		
		tag_id : tagid
	}
	console.log(data);
	$.post('/setting/info/tag_del',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('Tag_del Error');
		}
	});
});

//Score Del button
$(document).delegate('button#sco_del','click',function(){		
	var scoid = $(this).attr('scoid');
	var data = {		
		scoid : scoid
	}
	console.log(data);
	$.post('/setting/info/sco_del',data,function(json){
		console.log(json['result']);
		if(json['result']){
			window.location.reload();
		}else{
			alert('Tag_del Error');
		}
	});
});

$(document).delegate('input[type=checkbox]','click',function(){	
	var conf = $(this).is(':checked');
	var tag_id = $(this).attr('id'); // t1,t2,t3...
	console.log(conf);
	if(conf){		
		$('div').find('button.'+tag_id).attr('disabled',false);		
	}else{
		$('div').find('button.'+tag_id).attr('disabled',true);		
	}	
});

$('button#save').click(function(){
	var checked_tagVals = $('input.tags_chk:checkbox:checked').map(function() {
    	return $(this).attr('id');
	}).get();

	var checked_scoreVals = $('input.score:checkbox:checked').map(function() {
    	return $(this).attr('id');
	}).get();

	var checked_tabVals = $('input.tabs:checkbox:checked').map(function() {
    	return $(this).attr('id');
	}).get();
	console.log(checked_tabVals);	
	console.log(checked_tagVals);	
	console.log(checked_scoreVals);	
	

	var data = {
		kind_id : kind_id,
		tabs_val : checked_tabVals.toString(),
		tags_val : checked_tagVals.toString(),
		scores_val : checked_scoreVals.toString()		
	}
	console.log(data);
	$.post('/setting/info/saveSetting',data,function(json){
		console.log(json['result']);
		if(json['result']){
			alert('저장되었습니다');	
			window.location.href = '/setting/info';
		}else{
			alert('Error');
		}
	});
})

// TAG
$('.newtag').on("propertychange input textInput", function() {
	var charLimit = 1;    
    var critique = $('input.newtag').val();  
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
  
    if (remaining < charLimit && remaining > 0) {       
       $('#create').prop('disabled', true);                  

    } else if (remaining < 0) {        
        $('#create').prop('disabled', false);               
    } 
});

$('button#cancel').click(function(){
	$('.newtag').val('');
	$('#create').prop('disabled', true);
});


//SCORE
$('.newsco').on("propertychange input textInput", function() {
	var charLimit = 1;    
    var critique = $('input.newsco').val();  
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
  
    if (remaining < charLimit && remaining > 0) {       
       $('#scocreate').prop('disabled', true);                  

    } else if (remaining < 0) {        
        $('#scocreate').prop('disabled', false);               
    } 
});

$('button#scocancel').click(function(){
	$('.newsco').val('');
	$('#scocreate').prop('disabled', true);
});

// Create Tag Button
$('button#create').click(function(){
	var new_tag_name = $('.newtag').val();

	var alltagVals = $('input:checkbox').map(function() {
    	return $(this).attr('tagname');
	}).get();
	console.log(alltagVals);
	console.log(new_tag_name.toLowerCase());
	var inarray_conf = $.inArray(new_tag_name.toLowerCase(),alltagVals);
	if(inarray_conf != -1){ // 값이 있으면 0을 리턴 없으면 -1 리
		// 중복된 값이 있을때.		
		alert('중복된 값 있음.');

	}else{
		var data = {
			kind_id : kind_id,
			tag_name : new_tag_name
		}
		
		$.post('/setting/info/create_tag',data,function(json){
			if(json['result']){
				window.location.reload();
			}else{
				alert('Create Tag Error');
			}
		});
	}	
});

// Create Score Button
$('button#scocreate').click(function(){
	var new_soc_name = $('.newsco').val();
	
	var allscoVals = $('input.score').map(function() {
    	return $(this).attr('scorename').toLowerCase();
	}).get();
	console.log(allscoVals);
	console.log(new_soc_name.toLowerCase());
	
	var inarray_conf = $.inArray(new_soc_name.toLowerCase(),allscoVals);
	if(inarray_conf != -1){ // 값이 있으면 0을 리턴 없으면 -1 리
		// 중복된 값이 있을때.
		//console.log('ok'); 
		alert('중복된 값 있음.');

	}else{
		var data = {
			kind_id : kind_id,
			sco_name : new_soc_name
		}	
		console.log(new_soc_name);

		$.post('/setting/info/create_sco',data,function(json){
			if(json['result']){
				window.location.reload();
			}else{
				alert('Create Score Error');
			}
		});
	}	
});

</script>