<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/members/info">Members</a></li>   
        <li class="akacolor">Edit</li>   
      </ol>            
      <h3 class="text-center" id="name"></h3>  
      <!-- <h3 class="text-center" style="margin-top:-35px;" id="title">Edit</h3> -->
  </div>  <!-- Nav or Head title End -->
  
  <hr>  
  <div class="row" >
  	<div class="col-md-12">
  		<br>
  		<!-- Task -->
  		<div class="row" style="margin-bottom:10px;">  
  			<label class="col-sm-2 control-label"></label>
		    <label for="inputEmail3" class="col-sm-2 control-label">Task</label>
		    <div class="col-sm-3">		    
		      	<label class="checkbox-inline">
					<input type="checkbox" id="musedata" value="musedata"> Muse data
				</label>
			</div>
			<div class="col-sm-2">		    
				<label class="checkbox-inline">
					<input type="checkbox" id="writing" value="writing"> Writing
				</label>
			</div>	    
    	</div>    
  
  		<!-- Type -->
	  	<div class="row" style="margin-bottom:10px;">  
	  		<label class="col-sm-2 control-label"></label>
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
	    
	    <!-- Option -->
	    <div class="row" style="margin-bottom:10px;">  
	    	<label class="col-sm-2 control-label"></label>
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
console.log(cate);
var usr_id = '<?=$usr_id;?>';
$(document).ready(function(){

	$.post('/setting/info/get_member_edit_data',{usr_id:usr_id},function(json){
		console.log(json['result']);
		var data = json['result'];
		
		var name = data['name'];
		var musedata = data['musedata'];
		var writing = data['writing'];
		var type = data['type'];
		var pay = data['pay'];
		var start = data['start'];
		var end = data['end'];

		$('#name').html(name+' Setup');

		console.log(musedata);	

		if(musedata == '1'){
			$('input#musedata').attr('checked','checked');
		}

		if(writing == '1'){
			$('input#writing').attr('checked','checked');	
		}
			
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
});

var get_musedata = null;
var get_writing = null;
var get_type = null;
var start_t = null;
var end_t = null;
var pay = 0;

$(document).delegate('button#cancel','click',function(){
	window.history.back();
});

$(document).delegate('button#submit','click',function(){
	var submit = true;
	if(!$('input#musedata').is(':checked') && !$('input#writing').is(':checked')){
		submit = false;
		alert('not select task');		
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

	if(submit){	
		if($('input#musedata').is(':checked')){
			get_musedata = $('input#musedata').val();	
		}

		if($('input#writing').is(':checked')){
			get_writing = $('input#writing').val();	
		}	

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
			musedata : get_musedata,
			writing : get_writing,
			type : get_type,
			start : start_t,
			end : end_t,
			pay : pay
		}
		console.log(data);

		$.post('/setting/info/member_edit_save',data,function(json){
			console.log(json['result']);
			window.location.href = '/setting/info'

		});
	}
});
</script>
