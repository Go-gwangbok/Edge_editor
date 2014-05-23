<div class="container" style="margin-top:-15px;">	
	<div class="row">
		<ol class="breadcrumb" style="background:white;">
	    	<li><a href="/">Home</a></li>
	    	<li><a href="/musedata/project">Project</a></li>   
	    	<li class="akacolor">New Project</li>   
	    </ol>
	    <h3 class="text-center">New Project</h3>
   	</div> <!-- Div row -->	
	<br> 
	<br> 
	<div class="row" style="margin-bottom:10px;">  
	    <label for="inputEmail3" class="col-sm-2 control-label">Project Type</label>
	    <div class="col-sm-3">
	      <select class="form-control input-sm" id="selectBox">
	      	<option>Null</option>
	      	<?
	      	foreach ($data_type as $value) {
	      		$kind = $value->kind;
	      		$kind_id = $value->id;
	      	?>
	      	<option id="<?=$kind_id;?>"><?=strtoupper($kind);?></option>
	      	<? } ?>
	      </select>
	    </div>
    </div>    
	
	<div class="row" style="margin-bottom:10px;">  
	    <label for="inputEmail3" class="col-sm-2 control-label">Project Name</label>
	    <div class="col-sm-7">
	      <input type="text" class="form-control" id="name" placeholder="Project Name">
	    </div>
    </div>    
  
  	<div class="row" style="margin-bottom:10px;">  
	    <label for="inputPassword3" class="col-sm-2 control-label">Discription</label>
	    <div class="col-sm-9">
	      <textarea class="form-control" rows="4" id="disc" placeholder="Discription"></textarea>
	    </div>
    </div>

    <div class="row" style="margin-bottom:10px;">  
	    <label for="inputPassword3" class="col-sm-2 control-label">Members</label>
	    <div class="col-sm-9">
	     <?
	     foreach ($mem_list as $rows) {
	     	$name = $rows->name;
	     	$id = $rows->id;
	     ?>
	     <input type="checkbox" class="mem_check" value="<?=$id;?>"> <?=$name;?>
	     <?
	     }
	     ?>
	     </div>
    </div>
  
    <div class="row" style="margin-bottom:10px;">  
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-danger" id="submit" disabled>Create Project</button>
	    </div>
	</div>
</div>

<script>


$('input.mem_check').click(function(){
	var isChecked = false;
	$(':checkbox:checked').each(function(i){
	  isChecked = true;
	});

	if(isChecked)
	{
		console.log('abled');
		$('button#submit').removeAttr('disabled');
	}
	else
	{
		console.log('disabled');
		$('button#submit').attr('disabled', 'disabled');	
	}
});   



$("button#submit").click(function(){
	var selVal = $('#selectBox option:selected').attr('id');
	var name = $("input#name").val();	
	var disc = $("textarea#disc").val();
	var mem_list = $('input:checkbox:checked.mem_check').map(function ()
	{
		return this.value;
	}).get();		
	
	if(name == '' || disc == '' || selVal == 'null'){
		alert('Please enter all the values in the blanks!');
	}else{
		var data = {
			name: name,			
			disc: disc,
			kind: selVal,
			mem_list: mem_list.toString()
		};
		console.log(data);

		$.ajax(
		{
			url: '/musedata/project/create_pj', // 포스트 보낼 주소
			type: 'POST',					
			data: data,
			dataType: 'json',
			success: function(json)
			{				
				if(json['result'])
				{
					// 정상적으로 처리됨					
					alert('New project has been created!');
					window.location.replace("/setting/info/templet/1/"+selVal); // 리다이렉트할 주소
				}
				else{
					alert('all_list_db ==> create_pj Error');
				}			
			}
		});
	}
});
</script>