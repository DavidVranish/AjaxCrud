@foreach($carriers as $carrier)
	<tr class="edit-row" data-id="{{ $carrier->id }}">
		<td>
			<div class="form-group">
				<input type="text" name="name" class="form-control focus-field value-field" placeholder="Carrier Name" value="{{ $carrier->name }}" />
			</div>
		</td>
		<td>
			<div class="form-group">
				<input type="text" name="email" class="form-control value-field" placeholder="Carrier Email" value="{{ $carrier->email }}" />
			</div>
		</td>
		<td style="text-align:center">
			<div class="form-group">
				<select name="status" class="select2 value-field" >
					@foreach (['Active', 'Inactive'] as $select)
						<option value="{{$select}}" {{($select == $carrier->status)?'SELECTED':''}}>{{$select}}</option>
					@endforeach
				</select>
			</div>
		</td>
		<td style="vertical-align: middle; text-align: center;">
			<a class="label label-success ads-save-row" href="#" data-original-title="Save" data-toggle="tooltip"><i class="fa fa-save"></i></a>&nbsp;
			<a class="label label-danger ads-cancel-row" href="#" data-original-title="Cancel" data-toggle="tooltip"><i class="fa fa-minus-circle"></i></a>&nbsp;
		</td>
	</tr>
@endforeach