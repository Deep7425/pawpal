
<div class="savepermissions-block">
	<table class="table table-bordered">
		    <tbody>
		    <tr>
		      <td>

		        @foreach($modules as $module)
											<label class="lable-tab-section">
							        <input type="checkbox" name="module_id" value="{{$module->id}}" @if(!empty($permissions)) @if(in_array($module->id, explode(',',$permissions))) checked="checked" @endif @endif><span>{{$module->module_name}}</span></label>
						@endforeach
						</label>
		      </td>
		    </tr>
		    </tbody>
		</table>
		<div class="savepermissions-btn">
		<button type="submit" id="saveNow" class="btn btn-success submit">Save</button>
	</div>
</div>
