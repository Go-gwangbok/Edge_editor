<div class="container" style="margin-top:-15px;">
	<div class="row">    
      <ol class="breadcrumb" style="background:white;">        
        <li><a href="/">Home</a></li>
        <li><a href="/notice">Notice</a></li>
        <li class="akacolor"><?=$list->title;?></li>   
      </ol>
  	</div>
	
	<div class="text-right" style="padding-bottom:10px; padding-right:5px;">
		<span><b>Date : <?=$list->date;?></b></span>
	</div>	
	<div class="panel panel-info">
		<div class="panel-heading">
			<b>Title : </b><?=$list->title;?>
		</div>
  		
  		<div class="panel-body">  	
    	<p>
    		<?=nl2br($list->contents);?>
    	</p>
    	</div>
  		
  	</div>
  	<!-- del button -->
  	<?php
  	if($this->session->userdata('classify') == 0){
  	?>  	
  	<div class="pull-right">
  		<!-- Button trigger modal -->
		<button class="btn btn-danger btn-md" data-toggle="modal" data-target="#myModal">
		  <span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;DEL
		</button>

		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel" style="color:black;">Notice Del</h4>
		      </div>
		      <div class="modal-body" style="color:black;">
		        Are you sure you want to delete?
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		        <a href="/notice/notice_del/<?=$list->id;?>" type="button" class="btn btn-primary">Yes</a>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>
		<!-- /.modal End -->
  	</div>
  	<?php
  	}
  	?>
</div>

