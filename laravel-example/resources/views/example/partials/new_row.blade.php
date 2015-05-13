<tr class="edit-row">
	<td>
		<div class="form-group">
			<input type="text" name="name" class="form-control focus-field value-field" placeholder="Carrier Name" />
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text" name="email" class="form-control value-field" placeholder="Carrier Email" />
		</div>
	</td>
	@if (empty($modal) || !$modal)
		<td style="text-align:center">
			<div class="form-group">
				<select name="status" class="select2 value-field" disabled="disabled">
					@foreach (['Active', 'Inactive'] as $select)
						<option value="{{$select}}">{{$select}}</option>
					@endforeach
				</select>
			</div>
		</td>
		<td style="vertical-align: middle; text-align: center;">
			<a class="label label-danger ads-del-new-row" href="#" data-original-title="Delete" data-toggle="tooltip"><i class="fa fa-times"></i></a>&nbsp;
		</td>
	@endif
</tr>