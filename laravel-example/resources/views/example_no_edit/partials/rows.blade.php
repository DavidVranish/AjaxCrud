@foreach ($primaryInventoryItems as $primaryInventoryItem)
	<tr class="view-row" data-id="{{ $primaryInventoryItem->id }}">
	    <td data-order="{{ $primaryInventoryItem->stockid }}" class="delete-header delete-body">
	    	{{$primaryInventoryItem->stockid}} ({{$primaryInventoryItem->description}})
    	</td>
	    <td style="vertical-align:middle;text-align:center">
			<a class="label label-danger ads-delete" href="#" data-id="{{ $primaryInventoryItem->id }}" data-original-title="Delete" data-toggle="tooltip"><i class="fa fa-times"></i></a>
	    </td>
	</tr>
@endforeach