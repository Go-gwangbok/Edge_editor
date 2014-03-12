<div class="container" style="margin-top:-15px;">
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	        	<li><a href="/">Home</a></li>	        	
	        	<li class="akacolor">Notice </li>   
	    </ol>
		<?
		if($this->session->userdata('classify') == 0){
		?>
			<div>
				<h4 class="text-center">Notices</h4>
				<a href="/notice" class="btn btn-default btn-sm pull-right" id="write" style="margin-right:15px; border-color:#6799FF; color:#6799FF; margin-right:15px;">&nbsp;<span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Write&nbsp;&nbsp;</a>
			</div>
		<?
		}
		?>
	</div>
	<br>	
		<table class="table table-hover">
	  	<thead>
			<tr>
				<th class="text-center">No.</th>
				<th class="text-center">Title</th>								
				<th class="text-center">Date</th>								
			</tr>
		</thead>
		
		<tbody>			
			<?
			$num = 1;
			foreach ($list as $value) {
				$title = $value->title;
				$cont = $value->contents;
				$date = $value->date;
				$id = $value->id;
			?>
			<tr>
			<td class="text-center"><?=$num;?></td>
			<td><a href="/notice/contents/<?=$id;?>"><?=$title?></a></td>
			<td class="text-center"><?=substr($date,0,-3)?></td>
			</tr>
			<?$num++; } ?>		
		</tbody>
		</table>
	
</div>
<script>
$("a#write").mouseover(function(){
  $(this).css("border-color","#4374D9");
  $(this).css("color","#4374D9");
  $(this).css("background","white");
});

$("a#write").mouseout(function(){
  $(this).css("border-color","#6799FF");
  $(this).css("color","#6799FF");
  $(this).css("background","white");
});
</script>
