<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title">View Health Manager</h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bd lobidrag">
                <div class="panel-heading">
                    
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <thead class="success">
                            <tr>
                                <th>S.No.</th>
                                <th>Type</th>
                                <th>Update Content</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;  $testName = array(1 => 'Fasting', 2 => 'Before Breakfast', 3 => 'After Breakfast', 4 => 'Before Lunch', 5 => 'After Lunch', 6 => 'Before Dinner', 7 => 'After Dinner', 8 => 'Before Sleep', 9 => 'Other'); ?>
                        @if(isset($healthmanage['ManageBpRecords']))
                          @foreach($healthmanage['ManageBpRecords'] as $index => $row)
                            <tr class="tbrow">
                                <td><label>{{$i}}</label></td>
                                <td><strong>BP Record</strong></td>
                                <td>{{$row->bp_systolic}}/{{$row->bp_diastolic}}mmHg <br>{{$row->pulse_rate}}bpm</td>
                                <td>{{date('d-m-Y',strtotime($row->date))}} / {{date('g:i A',strtotime($row->time))}}</td>
                            </tr>
                            <?php $i++; ?>
                          @endforeach
                        @endif
                        @if(isset($healthmanage['ManageDiabetesRecords']))
                          @foreach($healthmanage['ManageDiabetesRecords'] as $index => $row)
                            <tr class="tbrow">
                                <td><label>{{$i}}</label></td>
                                <td><strong>Diabetes Record</strong></td>
                                <td>{{$row->sugar_level}}(mmol/L) <br> {{$testName[$row->test_id]}}</td>
                                <td>{{date('d-m-Y',strtotime($row->date))}} / {{date('g:i A',strtotime($row->time))}}</td>
                            </tr>
                            <?php $i++; ?>
                          @endforeach
                        @endif
                        @if(isset($healthmanage['ManageWeightRecords']))
                          @foreach($healthmanage['ManageWeightRecords'] as $index => $row)
            
                            <tr class="tbrow">
                                <td><label>{{$i}}</label></td>
                                <td> <strong>Weight Record</strong> </td>
                                <td>{{$row->weight}}(KG) </td>
                                <td>{{date('d-m-Y',strtotime($row->date))}} / {{date('g:i A',strtotime($row->time))}}</td>
                            </tr>
                            <?php $i++; ?>
                          @endforeach
                        @endif
                        @if(isset($healthmanage['ManageTemperatureRecords']))
                          @foreach($healthmanage['ManageTemperatureRecords'] as $index => $row)
                            <tr class="tbrow">
                                <td><label>{{$i}}</label></td>
                                <td><strong>Temperature Record</strong></td>
                                <td>{{$row->temp}}@if($row->temp_type == 1)F&#176; @else C&#176; @endif</td>
                                <td>{{date('d-m-Y',strtotime($row->date))}} / {{date('g:i A',strtotime($row->time))}}</td>
                            </tr>
                            <?php $i++; ?>
                          @endforeach
                        @endif
                            
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

    </div>