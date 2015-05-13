@foreach($carriers as $carrier)
	<tr class="view-row" data-id="{{ $carrier->id }}">
		<td class="delete-header delete-body">{{ $carrier->name }}</td>
		<td>{{ $carrier->email }}</td>
		<td style="text-align:center">{{ $carrier->status }}</td>
		<td style="vertical-align:middle;text-align:center">
			<a class="label label-default ads-edit-row" href="#" data-original-title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
			<!--<a class="label label-danger ads-prep-del-row" href="#" data-original-title="Delete" data-toggle="tooltip"><i class="fa fa-times"></i></a>-->
		</td>
	</tr>
@endforeach