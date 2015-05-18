@foreach($table2Examples as $example)
	<tr class="view-row" data-id="{{ $example->id }}">
		<td class="delete-header delete-body">{{ $example->field1 }}</td>
		<td>{{ $example->field2 }}</td>
		<td style="text-align:center">{{ $example->field3 }}</td>
		<td style="vertical-align:middle;text-align:center">
			<a class="label label-default ads-edit-row" href="#" data-original-title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
			<a class="label label-danger ads-prep-del-row" href="#" data-original-title="Delete" data-toggle="tooltip"><i class="fa fa-times"></i></a>
		</td>
	</tr>
@endforeach