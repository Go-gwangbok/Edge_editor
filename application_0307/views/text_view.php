<div class="container">		
	<h2>Prompt</h2>	
	<div class="div-box-line-promp">
		<dl>
		  	<dt style="margin:0 10px 0 10px">Prompt</dt>			  			
			<dd style="margin:0 15px 0 25px" id="prompt"><?=trim($title);?></dd>
		</dl>   		
	</div>  	
	<br>

	<ul class="nav nav-tabs" id="myTab">
	  
	  <li class="active"><a href="#error" data-toggle="tab">Error detect</a></li>
	  <li><a href="#tagging" data-toggle="tab">Tagging</a></li>
	  
	</ul>

	<div class="tab-content">

		<div class="tab-pane div-box-line active" id="error">
			<div class="col-md-12">				
				<div class="col-md-12" style="margin-top:20px;">
					<?
					if($cate != 'admin'){						
					?>
					<button id="delete" tag="delete" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> DEL</button>
					<button id="modify" tag="modify" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-wrench"></span> Modify</button>
					<button id="insert" tag="insert" type="button" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Insert</button>					
					
					<div class="pull-right" id="history">						
						<button class="btn btn-default btn-sm"  type="button" id = "undodel"><span class="glyphicon glyphicon-refresh"></span> Del clear</button>
						<button class="btn btn-default btn-sm" id = "undomo"><span class="glyphicon glyphicon-refresh"></span> Mod clear</button>
						<button class="btn btn-default btn-sm" id = "undoin"><span class="glyphicon glyphicon-refresh"></span> INS clear</button>
						<button class="btn btn-warning btn-sm" id = "undoAll"><span class="glyphicon glyphicon-refresh"></span> All clear</button>
					</div>
					<?					
					}else{
					?>
					<button id="delete" tag="delete" type="button" class="btn btn-danger" disabled><span class="glyphicon glyphicon-trash"></span> DEL</button>
					<button id="modify" tag="modify" type="button" class="btn btn-primary" disabled><span class="glyphicon glyphicon-wrench"></span> Modify</button>
					<button id="insert" tag="insert" type="button" class="btn btn-success" disabled><span class="glyphicon glyphicon-pencil"></span> Insert</button>					
					
					<div class="pull-right" id="history">						
						<button class="btn btn-default btn-sm" id = "undodel" disabled><span class="glyphicon glyphicon-refresh"></span> Del clear</button>
						<button class="btn btn-default btn-sm" id = "undomo" disabled><span class="glyphicon glyphicon-refresh"></span> Mod clear</button>
						<button class="btn btn-default btn-sm" id = "undoin" disabled><span class="glyphicon glyphicon-refresh"></span> INS clear</button>
						<button class="btn btn-warning btn-sm" id = "undoAll" disabled><span class="glyphicon glyphicon-refresh"></span> All clear</button>
					</div>

					<?
					}
					?>
					
					<!-- <button id="conf_insert" tag="insert" type="button" class="btn btn-primary btn-sm">conf_Insert</button> -->					
				</div>							
				<hr class="text-box-hr">							
				<?
				if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin'){
				?>
				<div class="border text_box" id="writing"><?=trim($edit_writing);?></div>
				<?
				}else{
				?>
				<div class="border text_box" id="writing"><?=nl2br(trim($writing));?></div>
				<input type="hidden" id="raw_writing" value="<?=$writing;?>">
				<?
				}				
				?>
				<hr class="text-box-hr">			
				<div class="panel-group" id="accordion">				  
				  <div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				          Critique
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse in">
				      <div class="panel-body">
				      	<?
				      	if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin'){

				      	?>
							<textarea class="border text_box" id="critique" style="width:100%;" rows="10"><?=nl2br(trim($critique));?></textarea>
						<?
				      	}else{
				      	?>
				      		<textarea class="border text_box" id="critique" style="width:100%;" rows="10"></textarea>		        
				      	<?
				      	}
				      	?>				        
				      </div>
				    </div>
				  </div> 

				   <!--<div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				          History action
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse">
				      <div class="panel-body" >

				        
				      </div>
				    </div>
				  </div>-->
				</div>
				<!-- accordion end -->

			</div>				
		</div>
		
		<div class="tab-pane div-box-line" id="tagging">
	  	<!--<div class="div-box-line">-->
			<div class="col-md-12">						
				<div class="col-md-12" style="margin-top:20px;">
					<button id="tag" tag="TR" type="button" class="btn btn-default btn-sm">&lt;TR&gt;</button>
					<button id="tag" tag="IN" type="button" class="btn btn-info btn-sm">&lt;IN&gt;</button>
					<button id="tag" tag="TS" type="button" class="btn btn-primary btn-sm">&lt;TS&gt;</button>
					<button id="tag" tag="MO1" type="button" class="btn btn-success btn-sm">&lt;MO1&gt;</button>
					<button id="tag" tag="MO2" type="button" class="btn btn-success btn-sm">&lt;MO2&gt;</button>
					<button id="tag" tag="BO1" type="button" class="btn btn-success btn-sm">&lt;BO1&gt;</button>
					<button id="tag" tag="BO2" type="button" class="btn btn-success btn-sm">&lt;BO2&gt;</button>
					<button id="tag" tag="SI1" type="button" class="btn btn-success btn-sm">&lt;SI1&gt;</button>
					<button id="tag" tag="SI2" type="button" class="btn btn-success btn-sm">&lt;SI2&gt;</button>
					<button id="tag" tag="EX" type="button" class="btn btn-success btn-sm">&lt;EX&gt;</button>
					<button id="tag" tag="CO" type="button" class="btn btn-success btn-sm">&lt;CO&gt;</button>
					<button id="tag" tag="MT1" type="button" class="btn btn-success btn-sm">&lt;MI1&gt;</button>
					<button id="tag" tag="MT2" type="button" class="btn btn-success btn-sm">&lt;MI2&gt;</button>
				</div>							
			</div>

			<div class="col-md-12">
				<hr class="text-box-hr">							
				<?
				if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin'){
				?>
				<textarea class="border text_box" id="textarea" style="width:100%;" rows="20"><?=trim($tagging);?>				
				</textarea>			
				<?
				}else{
				?>
				<textarea class="border text_box" id="textarea" style="width:100%;" rows="20"><?=trim($writing);?>				
				</textarea>			
				<?
				}
				?>				
			</div>				
		</div>	  	
	<?
	if($this->session->userdata('classify') == 1 && $cate == 'draft' || $cate == 'todo'){	 		
	?>	
	<button class="btn btn-danger pull-right" id="submit">Submit</button>
	<button class="btn btn-primary" id="draft">Save Draft</button>
	<?	
	}elseif($this->session->userdata('classify') == 1 && $cate == 'mydone'){
	?>
	<button class="btn btn-danger pull-right" id="editSubmit">Edit Submit</button>
	<?
	}elseif($this->session->userdata('classify') == 1 && $cate == 'writing'){
	?>  
	<button class="btn btn-danger pull-right" id="w_submit" disabled>Submit</button>
	<?
	}
	?>		
	</div>	
	
</div>
		
<!-- javascript -->		
		<script src="http://code.jquery.com/jquery-latest.js"></script>		
		<script src="/public/js/bootstrap.js"></script>				
		<script src="/public/js/jquery.selection.js"></script>				
		<script src="/public/js/highlighttextarea.js"></script>								
		<script src="/public/js/jquery.undoable.js"></script>				
		<script type="text/javascript">			

		if (!window.x) {
		    x = {};
		}

		x.Selector = {};
		x.Selector.getSelected = function() {
		    var t = '';
		    if (window.getSelection) {
		        t = window.getSelection();
		    } else if (document.getSelection) {
		        t = document.getSelection();
		    } else if (document.selection) {
		        t = document.selection.createRange().text;
		    }
		    return t;
		}
		$("button#undodel").click(function(){
			    		//$('span#action0').remove();
			    		$("span#action").replaceWith(function() { return $(this).contents(); });
			    	
		});		   	

		$("button#undomo").click(function(){
					    		//$('span#action0').remove();
					    		$("span#mod_action").replaceWith(function() { return $(this).contents(); });
					    		$("b#mod_action").remove();
					    	
		});		      
		$("button#undoin").click(function(){
					    		//$('span#action0').remove();
					    		$("span#ins_action").remove();					    		
					    	
		});		      

		$("button#undoAll").click(function(){
					    		//$('span#action0').remove();
					    		$("span#action").replaceWith(function() { return $(this).contents(); });
					    		$("span#mod_action").replaceWith(function() { return $(this).contents(); });
					    		$("b#mod_action").remove();
					    		$("span#ins_action").remove();					    		
					    	
		});		      

		$(document).ready(function() {	         
		    
		    // $("button#delete").click(function() {
		    //     var mytext = x.Selector.getSelected();
		    //     var div = $("div#writing");
		    //     if(mytext != ''){
		    //     	div.html(div.html().replace(mytext,'<span class="error_del" id = "action">'+mytext+'</span>'));	        	
		        	
		    //     }else{
		    //     	alert('aa');
		    //     }		        
		        
		   	// });		

		    $('button#delete').click(function(){
  				//$('#result').text($.selection());
  				var mytext = $.selection();
  				console.log(mytext);
  				var div = $("div#writing");			
  				div.html(div.html().replace(mytext,'<span class="error_del" id = "action">'+mytext+'</span>'));	        	
			});

		    window.onload=function()
			{
			   	$("button#modify").bind("mouseup",function() {
			

			        var mytext = x.Selector.getSelected();			        
			        var div = $("div#writing");			

			        if(mytext != ''){
			        	var my_text = prompt('before : '+mytext);
				        if(my_text){			        							
				        	div.html(div.html().replace(mytext,'<span class="error_mod" id="mod_action">'+mytext+'</span><b id="mod_action">'+' ( '+my_text+' )</b> '));				        	
				        } 	
			        }else{
			        	alert('bb');
			        }	        					    
			        
			    });

			    $("button#insert").bind("mouseup",function() {
			        var mytext = x.Selector.getSelected();
			        var div = $("div#writing");				        

					if(mytext != ''){
						var my_text = prompt('Enter text insert');
				        if(my_text){			        						
				        	div.html(div.html().replace(mytext,mytext + '<span class="error_in" id="ins_action"> + ' + my_text + ' + </span>'));

				   //      	if(i == 0){
		     //    			$("div#history").prepend('<button class="btn btn-primary" id = "undoin">Undo</button>');		        		
				   //      	}
				   //      	$("button#undoin").click(function(){
					  //   		//$('span#action0').remove();
					  //   		$("span#ins_action").remove();					    		
					    	
							// });		      
					  //   	i++;	
				        } 	
					}else{
						alert('cc');
					}			        
			    });
			}			
		});


			$('button#tag').click(function(){
				var el = $(this).attr('tag');
				//console.log(el);			
				$('#textarea')  
			    .selection('insert', {text: '<'+el+'>', mode: 'before'})			    
			    .selection('insert', {text: '</'+el+'>', mode: 'after'});			    
			    
			});			
			
			
			$("span#mouse").hover(
				function(){
					$(this).addClass("my-hover");
				},
				function(){
					$(this).removeClass("my-hover");
				});							
			

				$('button#draft').click(function()
				{			
					var editing = $('div#writing').html();
					var critique = $('textarea#critique').val();		
					var tagging = $('textarea#textarea').val();				
					var type = '<?=$type;?>';

					var data = {						
						essay_id: <?=$id;?>,						
						editing: editing,
						critique: critique,
						tagging: tagging,
						type: type
					}
					console.log(data);
					
					$.ajax(
					{
						url: '/text/draft_save', // 포스트 보낼 주소
						type: 'POST',					
						data: data,
						dataType: 'json',
						success: function(json)
						{
							if(json['status'] == 'true')
							{
								// 정상적으로 처리됨
								alert('정상적으로 저장되었습니다!');
								window.location.replace('/essaylist'); // 리다이렉트할 주소
							}
							else
							{
								alert('all_list --> draft DB Error');
							}
						}
					});
				});

			
			// 결과 전송

				$('button#submit').click(function()
				{			
					var editing = $('div#writing').html();
					var critique = $('textarea#critique').val();		
					var tagging = $('textarea#textarea').val();				
					var type = '<?=$type;?>';

					var data = {						
						essay_id: <?=$id;?>,						
						editing: editing,
						critique: critique,
						tagging: tagging,
						type: type
					}
					console.log(data);
					
					$.ajax(
					{
						url: '/text/submit', // 포스트 보낼 주소
						type: 'POST',					
						data: data,
						dataType: 'json',
						success: function(json)
						{
							if(json['status'] == 'true')
							{
								// 정상적으로 처리됨
								alert('정상적으로 제출되었습니다!');
								window.location.replace('/essaylist'); // 리다이렉트할 주소
							}
							else
							{
								alert('all_list --> draft DB Error');
							}
						}
					});
				});

				$('button#editSubmit').click(function()
				{			
					var editing = $('div#writing').html();
					var critique = $('textarea#critique').val();		
					var tagging = $('textarea#textarea').val();				
					var type = '<?=$type;?>';

					var data = {						
						essay_id: <?=$id;?>,						
						editing: editing,
						critique: critique,
						tagging: tagging,
						type: type
					}
					console.log(data);
					
					$.ajax(
					{
						url: '/text/editsubmit', // 포스트 보낼 주소
						type: 'POST',					
						data: data,
						dataType: 'json',
						success: function(json)
						{
							if(json['status'] == 'true')
							{
								// 정상적으로 처리됨
								alert('정상적으로 제출되었습니다!');
								window.location.replace('/essaylist/done'); // 리다이렉트할 주소
							}
							else
							{
								alert('all_list --> draft DB Error');
							}
						}
					});
				});
			

// writing js

var conf = '';
var conf_cri = '';

/*$('#editing').on("propertychange input textInput", function() {
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
});*/

$('#critique').on("propertychange input textInput", function() {
    var charLimit = 10;
    var critique = $('textarea#critique').val();	
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
 	
    if (remaining < charLimit && remaining > 0) {       
       $('#w_submit').prop('disabled', true);  	        
       // conf_cri = 'true';       
       // conform_submit();

    } else if (remaining < 0) {        
        $('#w_submit').prop('disabled', false);       
        // conf_cri = 'false';        
        // conform_submit();
    } 
});

// function conform_submit(){
// 	if(conf == 'false' && conf_cri == 'false'){		
// 		$('#w_submit').prop('disabled', false);
// 	}else{
// 		$('#w_submit').prop('disabled', true);  	        
// 	}
//}


$("button#w_submit").click(function(){
	//alert('aa');
	var token = '<?=$token;?>';
	var id = <?=$id;?>;
	var editing = $('div#writing').html();
	var raw_writing = $('input#raw_writing').val();	
	var critique = $('textarea#critique').val();		
	var tagging = $('textarea#textarea').val();
	var kind = '<?=$kind;?>';
	var title = '<?=$title?>';

	var submit_data = {
		token: token,
		w_id: id,
		kind: kind,
		title: title,
		raw_writing: raw_writing,
		editing: editing,
		critique: critique,
		tagging: tagging
	};
	console.log(submit_data);	
	$.ajax({
		//url:"http://192.168.0.22:8888/editor/editing/done",
		//url: '/writing/submit',
		type: 'POST',					
		data: submit_data,
		dataType: 'json',
		success: function(json){
			var	access = JSON.parse(json['result']);			
			console.log(json);									
			if(access['status']){						
				window.location = "/essaylist/done";
			}else if(access == 'localdb'){
				alert('local db insert error');
			}
		}
	});				
});
</script>
