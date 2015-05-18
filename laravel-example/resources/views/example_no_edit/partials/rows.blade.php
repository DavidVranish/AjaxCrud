@foreach ($rows as $row)
	<tr class="view-row" data-id="{{ $row->id }}">
	    <td class="delete-header delete-body">
	    	$row->data1
    	</td>
    	<td>$row->data2</td>
    	<td>$row->data3</td>
	    <td style="vertical-align: middle; text-align: center;">
			<a class="label label-danger ads-delete" href="#" data-id="{{ $row->id }}" data-original-title="Delete" data-toggle="tooltip"><i class="fa fa-times"></i></a>
	    </td>
	</tr>
@endforeach