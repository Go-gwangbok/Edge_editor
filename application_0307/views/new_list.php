<div class="container" style="margin-top:-15px;">			
	<div class="row">
		<ol class="breadcrumb" style="background:white;">
     		<li><a href="/">Home</a></li>
    		<li><a href="/project">Project List</a></li>   
    		<li class="active">Distribute </li>   
  		</ol>		
  		<h4 class="text-center"><?=$pj_name->name;?></h4>
		<div class="col-md-6 pull-left">					    			
			<?
			if($cate == 'import'){
			?>
			<form action="/upload/import_upload" method="post" enctype="multipart/form-data">
				<input type="file" name="userfile" id="input" size="20" />	<br />
				<input type="hidden" name="pj_id" id="pj_id" value="<?=$pj_id?>">
				<button class="btn btn-danger btn-xs" type="submit" value="upload" />Upload</button>				
				<span class="text-danger" style="margin-left:10px;"><?=$error;?></span>			
			</form>
			<?
			}else{
			?>
			<form action="/upload/do_upload" method="post" enctype="multipart/form-data">
				<input type="file" name="userfile" id="input" size="20" />	<br />
				<input type="hidden" name="pj_id" id="pj_id" value="<?=$pj_id?>">
				<button class="btn btn-danger btn-xs" type="submit" value="upload" />Upload</button>				
				<span class="text-danger" style="margin-left:10px;"><?=$error;?></span>			
			</form>
			<?
			}
			?>
		</div>

		<div class="col-md-6 pull-right">					
			<p class="text-danger pull-right"><strong>Essay Count : </strong><?=$cou = $count->count;?>&nbsp;&nbsp;&nbsp;
			<?
			if($cou == 0){
			?>
			<button type="button"  class="btn btn-danger btn-sm" disabled>Equal Distribute</button>
			<?
			}else{
			?>
			<button type="button" id="distribute" class="btn btn-danger btn-sm">Equal Distribute</button>
			<?
			}
			?>				
			</p>			
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="col-md-12">					    			
		<table class="table table-striped">				
			<thead>
				<tr>
					<th class="text-center">						
						<input type="checkbox" id="checkAll" conf="true">
    				</th>		
					<th class="text-center">
						<!-- Button trigger modal -->
						<button class="btn btn-primary pull-left" id="dis_btn" data-toggle="modal" data-target="#myModal" disabled>
						  Distribute
						</button> 
						Prompt
					</th>
					<th class="text-center">TYPE</th>				
					<th class="text-center">Date</th>			
				</tr>
			</thead>
			<tbody>	
				<?
				$i = 1;
				foreach ($list as $value) {
					$prompt = $value->prompt;				
					$date = $value->date;
					$type = $value->type;
					$id = $value->id;
				?>
				
				<tr>
					<td>						
						<label class="checkbox">
    					  <input type="checkbox" name="box" class="selec_sentence" id="<?=$i;?>" value = "<?=$id;?>"><?=$i;?>
    					</label>    					
					</td>										
					<td><?=$prompt;?></td>				
					<td class="text-center"><?=$type;?></td>
					<td><?=$date;?></td>
				</tr>	
				<?
				$i++;
				}
				?>
			</tbody>
		</table>		
	</div>

		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel" style="color:black;">Distribute</h4>
		      </div>
		      <div class="modal-body" id="dismodal">
		        <dl>						                	
                	<dt></dt>
				  	<dd style="color:black;"><strong>Please select an editor!</strong></dd>					  	
				  	<?
				  	foreach ($edi as $rows) {
				  		$usr_id = $rows->usr_id;
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
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        <button type="button" class="btn btn-primary" id="dis_submit" disabled>Distribute</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</div><!-- div row -->
	<!-- Loading Modal -->
	<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<br><br><br>
		<br>
	  	<div class="modal-body">
	    	<center><img src="/public/img/loading.gif"/></center>
	  	</div>	  
	</div>
	<!-- Loading Modal End -->
</div><!-- container -->
<script src="http://malsup.github.com/jquery.form.js"></script>
<script type="text/javascript">	
var url = '';
// modal member select 
$('.other').change(function () {
    var c = this.checked ? false : true;    
});

$(document).ready(function () {
    var maxaids = "1";    
    $(document).on('click', "input[type=checkbox]#sel_mem", function () {    	
        var bol = $("input:checked#sel_mem").length >= maxaids;
        $("input[type=checkbox]#sel_mem").not(":checked").attr("disabled", bol);        
        var conf = $(this).attr('conf');
        console.log(conf);           
        if(conf == 'true'){
        	$('button#dis_submit').removeAttr('disabled');	
        	console.log($(this).val()); 
        	$(this).attr('conf','false');
        }else{
        	$('button#dis_submit').attr('disabled', 'disabled');		
        	$(this).attr('conf','true');
        }        
    });
});
 
// 체크 박스 모두 체크
$( function(){
    $("#checkAll").click(function() {
    	var v = $(this).attr('conf');
    	console.log(v);

    	if(v == 'true'){
    		$('button#dis_btn').removeAttr('disabled');	
    		$(this).attr('conf','false')
    	}else{
    		$('button#dis_btn').attr('disabled', 'disabled');	
    		$(this).attr('conf','true')
    	}
    	
        var num = 0; // check all start num
        for(num; num <= 50; num++){ //check all limit num
        	$("input#"+num).prop('checked', $(this).is(":checked"));
        }      

        // 체크 되어 있는 값 추출
        $("input[name=box]:checked").each(function() {
			var test = $(this).val();
			console.log(test);
		});
    });
});


$('input.selec_sentence').click(function(){	
	var isChecked = false;
	$(':checkbox:checked').each(function(i){
	  	isChecked = true;
	});

	if(isChecked)
	{
		console.log('abled');
		$('button#dis_btn').removeAttr('disabled');
	}
	else
	{
		console.log('disabled');
		$('button#dis_btn').attr('disabled', 'disabled');	
	}
});   

$('button#dis_submit').click(function()
{	
	var sentence_chk = $('input:checkbox:checked.selec_sentence').map(function ()
	{
		return this.value;
	}).get();	
	var select_mem = $('input:checkbox:checked.other').val();

	$('#myModal').children().remove();
	$('<br><br><br><br><div class="modal-body"><center><img src="/public/img/loading.gif"/></center></div>').appendTo('#myModal');
	
	if(cate == 'import'){
		url = '/distri/import_mem_dis_proc';
	}else{
		url = '/distri/mem_dis_proc';
	}
	
	var pj_id = $('input#pj_id').val();
	var form_data = {
		 sentences: sentence_chk.toString(),
		 select_mem: select_mem,
		 pj_id: pj_id	
	};				
	console.log(form_data);	
	$.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: 'json',
        success: function(json) {
        	console.log(json['result']);	            
        	console.log(json['pj_id']);	            
            if(json['result']) {
            	//alert('It’s been successfully processed!');
            	window.location.replace('/project/import/'+json['pj_id']); // 리다이렉트할 주소
            }
            else
            {
            	alert("DB -> mem_sentence Error");	            	
            }                            
        }
    });			
});


var cate = '<?=$cate;?>';
$("button#distribute").click(function(){
	var cou = '<?=$cou;?>';
	var pj_id = $('input#pj_id').val();
	var data = {
		cou: cou,
		pj_id: pj_id
	};
	console.log(data);

	$('#modal').modal('show');	
	
	if(cate == 'import'){
		url = '/distri/import_dis_proc';
	}else{
		url = '/distri/dis_proc';
	}
	//console.log(url);
	$.ajax(
	{
		url: url, // 포스트 보낼 주소
		type: 'POST',					
		data: data,
		dataType: 'json',
		success: function(json)
		{
			if(json['status'] == 'true')
			{				
				//alert('It’s been successfully processed!');
				window.location.replace('/project/distribute/'+json['pj_id']); // 리다이렉트할 주소
			}
			else if(json['status'] == 1)
			{
				alert('1');
			}
			else if(json['status'] == 2)
			{
				alert('2');
			}
			else if(json['status'] == 3)
			{
				alert('3');
			}
			else if(json['status'] == 4)
			{
				alert('4');
			}
			else if(json['status'] == 5)
			{
				alert('5');
			}
			else if(json['status'] == 5)
			{
				alert('6');
			}
		}
	});
});

</script>