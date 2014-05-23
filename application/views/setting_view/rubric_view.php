<div class="container" style="margin-top:-15px;">   
  	<div class="row">    
      	<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>
        	<li><a href="/setting/info">Setting</a></li>   
        	<li class="akacolor">Rubric setting</li>   
    	</ol>                        
  	</div>  <!-- Nav or Head title End -->  
  	<h3 class="text-center" id="title"></h3>
  	<br>
  	<hr>
  	<br>  	  	
  	<div class="col-md-12">
            <h4>Tag Button Setting
            	<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" style="margin-top:-10px;">Add Tag</button> 
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
            	<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#scomyModal" style="margin-top:-10px;">Add Score</button> 
            </h4>
	</div>   
    <div class="row">  
	    <div class="col-md-12" id="scores">
	    	<!-- Ajax -->
	    </div>  
	</div>
	<br><br><br><br>
	<div class="col-md-12 text-center">
		<button class="btn btn-default btn-lg" id="savecancel"> Cancel </button>
    	<button class="btn btn-danger btn-lg" id="save"> SAVE </button>
    </div>	  
    <!-- Tag Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel" style="color:black;">Add Tag</h4>
	      </div>
	      <div class="modal-body" id="dismodal">
	      	<h5 style="color:black; margin-left:15px;"><strong>추가할 태그를 선택하세요!</strong></h5>
	      	<br>
	      	<div class="row">
		      	<?
		      	foreach ($add_tag_data as $value) {
		      		$tag_id = $value->id;
		      		$tag = $value->tag;
		      	?>
		      	<div class="col-md-3">
		      	<input type="checkbox" class="add_tags" id="a<?=$tag_id;?>">	      	
		      		<span style="color:black;">&lt;<?=strtoupper($tag);?>&gt;</span>
		      	</div>
		      	<?
		      	}
		      	?>	        
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="add" disabled>Add tag</button>
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
	      	<h5 style="color:black; margin-left:15px;"><strong>추가할 스코어를 선택하세요!</strong></h5>
	      	<br>
	      	<div class="row">
		      	<?
		      	foreach ($add_score_data as $value) {
		      		$sco_id = $value->id;
		      		$sco_name = $value->name;
		      	?>
		      	<div class="col-md-4">
		      	<input type="checkbox" class="add_score" id="sc<?=$sco_id;?>">	      	
		      		<span style="color:black;"> <?=strtoupper($sco_name);?> </span>
		      	</div>
		      	<?
		      	}
		      	?>	        
	      	</div>	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="scocancel" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="scoadd" disabled>Add score</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal --> 				
</div>
<script type="text/javascript">
var kind_id = <?=$kind_id;?>;

function firstLowstring(value){
	var str = value.substring(0,1).toUpperCase() + value.substring(1);
	return str;
}

$(document).ready(function(){
	$.post('/setting/info/get_rubric_val',{kind_id : kind_id},function(json){				
		var tags = json['get_setup_tag'];
		var scores = json['scores'];		
		var kind_name = json['kind_name'];	
		
		$('#title').text(kind_name.toUpperCase()+' Rubric setting');
		
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
		tag_id : tagid,
		kind_id : kind_id		
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
		scoid : scoid,
		kind_id : kind_id
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
	console.log(checked_tagVals);	
	console.log(checked_scoreVals);	

	var data = {
		kind_id : kind_id,		
		tags_val : checked_tagVals.toString(),
		scores_val : checked_scoreVals.toString()		
	}
	console.log(data);
	$.post('/setting/info/rubricSaveSetting',data,function(json){
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
$('button#cancel').click(function(){
	$(':checkbox:checked.add_tags').prop('checked',false);
	$('#add').prop('disabled', true);
});


//SCORE
$('button#scocancel').click(function(){
	$(':checkbox:checked.add_score').prop('checked',false);
	$('#scoadd').prop('disabled', true);
});


$('input.add_tags').click(function(){
	var isChecked = false;
	$(':checkbox:checked.add_tags').each(function(i){
	  isChecked = true;
	});

	if(isChecked){		
		$('button#add').removeAttr('disabled');
	}else{		
		$('button#add').attr('disabled', true);	
	}
});

$('input.add_score').click(function(){
	var isChecked = false;
	$(':checkbox:checked.add_score').each(function(i){
	  isChecked = true;
	});

	if(isChecked){		
		$('button#scoadd').removeAttr('disabled');
	}else{		
		$('button#scoadd').attr('disabled', true);	
	}
});


// Add Tag Button
$('button#add').click(function(){
	var alltagVals = $('input:checkbox:checked.add_tags').map(function() {
    	return $(this).attr('id').substr(1);
	}).get();
	console.log(alltagVals);
	
	var data = {
		kind_id : kind_id,
		add_tags_id : alltagVals.toString()		
	}
	console.log(data);
	
	$.post('/setting/info/add_tag',data,function(json){
		if(json['result']){
			window.location.reload();
		}else{
			alert('Create Tag Error');
		}
	});
	
});

// Add Score Button
$('button#scoadd').click(function(){
	var allscoVals = $('input:checkbox:checked.add_score').map(function() {
    	return $(this).attr('id').substr(2);
	}).get();
	
	var data = {
		kind_id : kind_id,
		add_sco_id : allscoVals.toString()		
	}	
	console.log(data);

	$.post('/setting/info/add_sco',data,function(json){
		if(json['result']){
			window.location.reload();
		}else{
			alert('Create Score Error');
		}
	});
	
});

$('#savecancel').click(function(){
	window.history.back(-1);
});
</script>