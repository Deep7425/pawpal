<div class="modal-dialog modal-lg">
<div class="modal-content ">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Prescription</h4>
	</div>
	<div class="modal-body">
	<div class="panel panel-bd lobidrag">
	<div class="panel-heading HealthGennieOrder"></div>
	<div class="panel-body">
		
	
		<div class="prescription-upload">
			<h2>Upload Your Prescription</h2>
			<p>Please upload images of valid prescription.</p>
			<div>
			<form action="{{route('uploadPrescription')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="file btn btn-lg btn-primary FileBtn">
				<img width="20" src="img/PrescriptionUploadicon.png"/>Select Prescription
				<input type="file" name="document" required class="_PsDF" accept="image/jpeg,image/png,image/jpg,application/pdf" onchange="openFileProfile(event)"/>
			</div>
				<button class="SaveFile" type="submit">Upload</button>
			</form>
		</div>
		</div>

	<div class="prescription-guide">
		<div class="row prescription-uploded">
			<h2>Prescription Uploaded by You</h2>
			@forelse($pres as $raw)
				<div class="doc-img"> 
				@if($raw->file_ext == 'pdf')
					<img src="<?=url("/")."/img/pdf.png/"?>" width="50" height="50" pdfSrc="<?=url("/")."/public/medicine-files/".$raw->prescription?>" class="viewPDFPresOwn"/>
					<div class="close-icon removePres" rawId="{{$raw->id}}"><i class="fa fa-times" aria-hidden="true"></i></div>
				@else	
					<img src="<?=url("/")."/public/medicine-files/".$raw->prescription?>" width="50" height="50" class="viewPresFile"/>
					<div class="close-icon removePres" rawId="{{$raw->id}}"><i class="fa fa-times" aria-hidden="true"></i></div>
				@endif
				</div>
			@empty
			<p>Not Found..</p>
			@endforelse
		</div>
		
        <!--<h2 class="PrescriptionGuide">Prescription Guide</h2>
			<p>Image should be sharp and contain below mentioned 4 points</p>
			<div class="row">
			<div class="col-left">
				<h5>Dr ABC</h5>
				<p>Hospital/Clinic Name</p>
				<p>Hospital/Clinic Address</p>
				<p>Rxg No</p>
			</div>
			<div class="col-right">
				<img src="img/doctor12.png" />
				<h4>Doctor & Clinic Details</h4>
			</div>
		</div>
			<div class="row">
			<div class="col-left">
				<h5>Patient Name</h5>
				<p>Patient Address</p>
				<p>Patient Age</p>
				<p>Date of Visit</p>
			</div>
			<div class="col-right">
				<img src="img/appointment-pres12.png" />
				<h4>Patient & Visit Details</h4>
			</div>
		</div>
			<div class="row medicine-detail">
			<div class="col-left">
				<h5><img src="img/rx-pres2.png" /></h5>
				<p>Lab Name</p>
			</div>
			<div class="col-right">
				<img src="img/medicines12.png" />
				<h4>Lab Details</h4>
			</div>
		</div> 
			<div class="row">
			<div class="col-left  ">
				<img src="img/signature.png" />
			</div>
			<div class="col-right small	">
				<img src="img/stamp12.png" />
				<h4>Doctor Sign & Stamp</h4>
			</div>
		</div> -->
        
	</div>
	</div>
	</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</div>
</div>