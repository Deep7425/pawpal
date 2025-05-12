<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Manage Support</h4>
		</div>
		<div class="modal-body">
		<table class="table table-bordered table-hover">
		  <tr>
			<th>S.No.</th>
			<th>Type</th>
			<th style="width:310px;">Note</th>
			<th>FollowUp Date</th>
			<th>Created By</th>
			<th>Created At</th>
		  </tr>
		  @forelse($queries as $i => $raw)
		  <tr>
		    <td>{{$i + 1}}</td>
			<td>@if($raw->type == '1') Won @elseif($raw->type == '2') Call Established @elseif($raw->type == '3') Not Reachable/Switched Off @elseif($raw->type == '4') Follow Up @elseif($raw->type == '5') Lost @endif</td>
			<td>{{$raw->note}}</td>
			<td>@if(!empty($raw->followUpDate)){{date('d-m-Y',strtotime($raw->followUpDate))}}@endif</td>
			<td>{{getNameByLoginId($raw->createBy)}}</td>
			<td>{{date('d-m-Y g:i A',strtotime($raw->created_at))}}</td>
		  </tr>
		  @empty
			<tr>
				<td colspan="6">No record found...</td>
			</tr>
		  @endforelse
	    </table>
		{!! Form::open(array('id' => 'manageSupportSystem','name'=>'manageSupportSystem')) !!}
		<input type="hidden" name="pkey" id="pkey" value="{{base64_encode($pkey)}}"/>
		<input type="hidden" name="r_from" id="r_from" value="{{$r_from}}"/>
		<div class="form-group">
		  <label><input type="radio" name="typ" value="2"/>Call Established</label>
		  <label><input type="radio" name="typ" value="3"/>Not Reachable/Switched Off</label>
		  <label><input type="radio" name="typ" value="4"/>Follow Up</label>
		  <label><input type="radio" name="typ" value="5"/>Lost</label>	
		  <label><input type="radio" name="typ" value="1"/>Won</label>
		   <span class="help-block"></span>
		</div>
		<div class="form-group FollowUpDate" style="display:none;">
		  <label>Follow Up Date</label>
		   <input type="text" autocomplete="off" class="form-control fromFollowupDate" name="followUpDate"/>
		   <span class="input-group-addon fromfollowup_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
		   </span>
		   <span class="help-block"></span>
		</div>
		<div class="form-group noteSection">
		  <label>Note</label>
		  <textarea type="text" name="note" rows="5" class="form-control" placeholder="Write Note..."></textarea>
		  <span class="help-block"></span>
		</div>
		<div class="reset-button">
		   <button type="reset" class="btn btn-warning">Reset</button>
		   <button type="submit" class="btn btn-success submitSpFrm">Save</button>
		</div>
	   {!! Form::close() !!}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
<script>
$("#manageSupportSystem input[name='typ']").click(function(){
    if($('input:radio[name=typ]:checked').val() == "4"){
       $(".FollowUpDate").show();
    }
	else{
		$(".FollowUpDate").hide();
	}
});
$(".fromFollowupDate").datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat: 'dd-mm-yy',
	  minDate: new Date(),
});
jQuery('.fromfollowup_cal').click(function () {
	jQuery('.fromFollowupDate').datepicker('show');
});
</script>