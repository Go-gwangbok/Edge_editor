<div class="container" style="margin-top:-15px;">   
  	<div class="row">    
      	<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>
        	<li><a href="/setting/info">Setting</a></li>   
        	<li class="akacolor">Setup</li>   
      	</ol>            
    	<h3 class="text-center" id="name"></h3>        
  	</div>  <!-- Nav or Head title End -->
  
  	<hr>  
  	<div class="row" >
	  	<div class="col-md-12">
	  		<br>
	  		<!-- Task -->
	  		<div class="row" style="margin-bottom:10px;">  
	  			<label class="col-sm-1 control-label"></label>
			    <label for="inputEmail3" class="col-sm-2 control-label">Task</label>
			    <?php
			    foreach ($task_list as $value) {
			    	$task_name = $value->name;
			    	$task_id = $value->id;
			    ?>
			    <div class="col-sm-2">		    
			      	<label class="checkbox-inline">
						<input type="checkbox" class="task" id="t<?=$task_id;?>" value="<?=$task_id;?>" task="<?=$task_name;?>"> <?=ucfirst($task_name);?>
					</label>
				</div>
			    <?php } ?>			
	    	</div>  
	    	
	    	<div class="row" id="desc" style="margin-bottom:10px; display:none">  
	    		<textarea id="text_desc" rows="5" cols="150" style="margin-left:75px; resize:none;" autofocus placeholder="Describe editor with 140 words..."></textarea>  
	    	</div>

	  		<br>
	  		<!-- Type -->
		  	<div class="row" style="margin-bottom:10px;">  
		  		<label class="col-sm-1 control-label"></label>
			    <label class="col-sm-2 control-label">Type</label>
			    <div class="col-sm-3">
			      	<label class="checkbox-inline">
						<input type="checkbox" id="part_time" value="partTime" > Part-time
					</label>
				</div>
				<div class="col-sm-2">
					<label class="checkbox-inline">
						<input type="checkbox" id="case" value="case" > Case by Case
					</label>
			    </div>
		    </div>
		    <br>
		    <!-- Option -->
		    <div class="row" style="margin-bottom:10px;">  
		    	<label class="col-sm-1 control-label"></label>
			    <label class="col-sm-1 control-label">Option</label>
			    <div class="col-sm-3">
			    	<span style="margin-left:10px;">Start Time &nbsp;&nbsp;&nbsp;&nbsp;<input type="time" id="start" disabled></span>
			    	<br><br>
			    	<span style="margin-left:12px;">End Time &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" id="end" disabled></span>
			    	<br><br>
			    	<span style="margin-left:13px;">Pay(US) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" id="pay" style="width:112px;" disabled><br></span>
			     </div>
			     <div class="col-sm-4">
			     	<span style="margin-left:13px;">Pay(US) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" id="case_pay" style="width:112px;" disabled><br></span>
			     </div>
		    </div>
	  		<br><br>
		    <div class="row" style="margin-bottom:10px;">  
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-danger pull-right" id="submit" >&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;</button>
			      <button class="btn btn-default pull-right" id="cancel" style="margin-right:5px;">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
			    </div>
			</div>			
		</div> <!-- col-md-12 -->
  	</div> <!-- Row End -->	
</div>
<script type="text/javascript">
var cate = '<?=$cate?>';
var description = '';
var email = '';
console.log(cate);
var usr_id = '<?=$usr_id;?>';
$(document).ready(function(){
	$.post('/setting/info/get_member_edit_data',{usr_id:usr_id},function(json){
		console.log(json['result']);
		console.log(json['task']);
		var data = json['result'];
		var task = json['task'];
		description = json['result']['description'];
		email = json['result']['email'];

		// Task Mapping
		$.each(task,function(i,values){
			task_id = values['task_id'];
			$('div').find('input#t'+task_id).attr('checked','checked');
		});
		
		if($('input#t2').is(':checked')){			
			$('div#desc').css('display','inline');
			$('textarea#text_desc').html(description);
		}

		var name = data['name'];		
		var type = data['type'];
		var pay = data['pay'];
		var start = data['start'];
		var end = data['end'];

		$('#name').html(name+' Setup');	
			
		if(type == 'partTime'){
			$('input#part_time').attr('checked','checked');	
			$('input#start').removeAttr('disabled');
			$('input#end').removeAttr('disabled');	
			$('input#pay').removeAttr('disabled');		
			$('input#start').val(start);
			$('input#end').val(end);
			$('input#pay').val(pay);			
		}else if(type == 'case'){	
			$('input#case').attr('checked','checked');			
			$('input#case_pay').removeAttr('disabled');	
			$('input#case_pay').val(pay);
		}
	}); // Post End
}); // Document

$(document).delegate('input[type=checkbox]','click',function(){		
	if(!$('input#part_time').is(':checked')){
		$('input#start').attr ( "disabled" , true );
		$('input#end').attr ( "disabled" , true );	
		$('input#pay').attr ( "disabled" , true );			
	}else if($('input#part_time').is(':checked')){
		$('input#start').removeAttr('disabled');		
		$('input#end').removeAttr('disabled');		
		$('input#pay').removeAttr('disabled');
	}

	if($('input#case').is(':checked')){		
		$('input#case_pay').removeAttr('disabled');
		$('input#start').attr( "disabled" , true );				
		$('input#end').attr( "disabled" , true );				
		$('input#pay').attr( "disabled" , true );					
		$('input#part_time').attr('checked',false);		
	}else if(!$('input#case').is(':checked')){	
	 	$('input#case_pay').attr( "disabled" , true );				
	}

	if($(this).attr('task') == 'writing'){
		if($(this).is(':checked')){
			$('div#desc').css('display','inline');
			$('textarea#text_desc').focus().html(description);
		}else{
			$('div#desc').css('display','none');
		}	
	}
});

var get_type = null;
var start_t = null;
var end_t = null;
var pay = 0;

$(document).delegate('button#cancel','click',function(){
	window.history.back();
});

$(document).delegate('button#submit','click',function(){
	var task_ids = $('input:checkbox:checked.task').map(function() {        
		    return this.value;
		}).get();  	
	editor_desc = $('textarea#text_desc').val();	
	var submit = true;
	if(!$('input.task').is(':checked')){
		submit = false;
		alert('Not select task');		
	}else if($('input#part_time').is(':checked')){
		if($('input#start').val() == "" || $('input#end').val() == "" || $('input#pay').val() == 0 ){
			submit = false;
			alert('input value null');
		}
	}else if($('input#case').is(':checked')){
		if($('input#case_pay').val() == 0 ){
			submit = false;
			alert('input value null');
		}
	}

	if($.inArray('2',task_ids) != '-1' && editor_desc.length == 0){
		alert('에디터에 대한 소개를 작성해 주세여!');
	}else{
		if(submit){
			if($('input#part_time').is(':checked')){
				get_type = $('input#part_time').val();
				start_t = $('input#start').val();
				end_t = $('input#end').val();
				pay = $('input#pay').val();
			}else if($('input#case').is(':checked')){
				get_type = $('input#case').val();
				pay = $('input#case_pay').val();
				start_t = null;
				end_t = null;
			}	

			var data = {
				usr_id : usr_id,
				task_ids : task_ids.toString(),
				type : get_type,
				start : start_t,
				end : end_t,
				pay : pay,
				editor_desc : editor_desc,
				email : email
			}
			console.log(data);

			$.post('/setting/info/member_edit_save',data,function(json){
				console.log(json['result']);
				if(json['result']){
					window.location.href = '/setting/info';	
				}else{
					alert('Curl Error');
				}
				
			});
		}	
	}		
});
</script>
