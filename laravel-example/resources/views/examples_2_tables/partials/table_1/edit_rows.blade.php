@foreach($table1Examples as $example)
	<tr class="edit-row" data-id="{{ $example->id }}">
		<td>
			<div class="form-group">
				<input type="text" name="field1" class="form-control focus-field value-field" value="{{ $example->field1 }}" />
			</div>
		</td>
		<td>
			<div class="form-group">
				<input type="text" name="field2" class="form-control value-field" value="{{ $example->field2 }}" />
			</div>
		</td>
		<td style="text-align:center">
			<div class="form-group">
				<input type="text" name="field3" class="form-control value-field" value="{{ $example->field3 }}" />
			</div>
		</td>
		<td style="vertical-align: middle; text-align: center;">
			<a class="label label-success ads-save-row" href="#" data-original-title="Save" data-toggle="tooltip"><i class="fa fa-save"></i></a>&nbsp;
			<a class="label label-danger ads-cancel-row" href="#" data-original-title="Cancel" data-toggle="tooltip"><i class="fa fa-minus-circle"></i></a>&nbsp;
		</td>
	</tr>
@endforeach