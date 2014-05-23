<div class="container">		
	<h2>Prompt</h2>
		
	<div class="div-box-line-promp">
		<dl>
		  <dt style="margin:0 10px 0 10px">Prompt</dt>			  
		  <dd style="margin:0 15px 0 25px"><?=trim($title);?></dd>		  
		</dl>   		
	</div> 

	<div class="tab-pane div-box-line active">
		<div class="col-md-12">			
			<textarea class="border text_box" id="textarea" style="width:100%;" rows="18" readonly><?=trim($writing);?>
			</textarea>			
		</div>				
		<hr class="text-box-hr">			

		<div class="panel-group" id="accordion">
		  
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
		          Editing window
		        </a>
		      </h4>
		    </div>
		    <div id="collapseOne" class="panel-collapse collapse in">
		      <div class="panel-body">
		        <textarea class="border text_box" id="editing" style="width:100%;" rows="10"></textarea>		        
		      </div>
		    </div>
		  </div> 
		  
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
		          Critique
		        </a>
		      </h4>
		    </div>
		    <div id="collapseTwo" class="panel-collapse collapse">
		      <div class="panel-body">
		      	<textarea class="border text_box" id="critique" style="width:100%;" rows="10"></textarea>		        		        
		      </div>
		    </div>
		  </div>		  	  
		  
		</div>
	</div> 	
	<button class="btn btn-danger pull-right" id="submit" disabled>Submit</button>
</div>

<script src="http://code.jquery.com/jquery-1.8.0.js"></script>		
<script src="/public/js/bootstrap.js"></script>	
<script> 	
	var conf = '';
	var conf_cri = '';

$('#editing').on("propertychange input textInput", function() {
    var charLimit = 10;
    var editing = $('textarea#editing').val();
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;    
 	
    if (remaining < charLimit && remaining > 0) {
       // No characters entered so disable the button
       //$('#submit').prop('disabled', true);  	        
       conf = 'true';
       console.log(conf);       
       conform_submit();
    } else if (remaining < 0) {        
        //$('#submit').prop('disabled', false);       
        conf = 'false';
        console.log(conf);
        conform_submit();
    } 
});

$('#critique').on("propertychange input textInput", function() {
    var charLimit = 10;
    var critique = $('textarea#critique').val();	
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
 	
    if (remaining < charLimit && remaining > 0) {       
       //$('#submit').prop('disabled', true);  	        
       conf_cri = 'true';       
       conform_submit();

    } else if (remaining < 0) {        
        //$('#submit').prop('disabled', false);       
        conf_cri = 'false';        
        conform_submit();
    } 
});

function conform_submit(){
	if(conf == 'false' && conf_cri == 'false'){		
		$('#submit').prop('disabled', false);
	}else{
		$('#submit').prop('disabled', true);  	        
	}
}

$("button#submit").click(function(){
	//alert('aa');
	var token = "<?=$token;?>";
	var w_id = <?=$w_id;?>;
	var editing = $('textarea#editing').val();
	var critique = $('textarea#critique').val();		

	var data = {
		token: token,
		w_id: w_id,
		editing: editing,
		critique: critique
	};
	console.log(data);	
	$.ajax({
		//url:"http://192.168.0.22:8888/editor/editing/done",
		url:"/writing/submit",
		Type: 'POST',
		data: data,
		dataType: 'Json',
		success: function(json){
			var	access = JSON.parse(json['result']);			
			console.log(access['status']);									
			if(access['status']){						
				window.location = "/essaylist/done";
			}else{
				alert('submit error');
			}
		}
	});
});


</script>