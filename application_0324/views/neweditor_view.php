<div class="container">

	<h4 class="text-center">New Editor List</h4>
	<br>
	<table class="table table-hover">
  	<thead>
			<tr>
				<th class="text-center">Num</th>
				<th class="text-center">Name</th>
				<th class="text-center">Email</th>
				<th class="text-center">Date</th>
				<th class="text-center">Action</th>
			</tr>
	</thead>
	
	<tbody>			
		<?
		$num = 1;
		foreach ($get_editor as $value) {
			$name = $value->name;
			$email = $value->email;
			$date = $value->date;
			$id = $value->id;
		?>
		<tr>
		<td class="text-center"><?=$num;?></td>
		<td class="text-center"><?=$name?></td>
		<td class="text-center"><?=$email;?></td>
		<td class="text-center"><?=substr($date,0,-3)?></td>
		<td class="text-center">
			<a href="/neweditor/conf/<?=$id;?>" type="button" class="btn btn-primary btn-md">Accept</a>
			<a href="/neweditor/del/<?=$id;?>" type="button" class="btn btn-danger btn-md">Decline</a>
		</td>
		</tr>
		<?$num++; } ?>		
	</tbody>
	</table>

</div>