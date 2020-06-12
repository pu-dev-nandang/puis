<div id="detail-log">
	<div class="info-general">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <?php 
                    $today = date("Y-m-d");
                    $birthDate = $employee->DateOfBirth;
                    $diff = date_diff(date_create($birthDate), date_create($today));
                    $myAge = $diff->format('%y');
                    ?>
                    <div class="col-sm-1 text-center">
                        <img class="img-thumbnail" id="ProfilePicture" style="max-width: 100px;width: 100%;" src="data:image/png;base64,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fzkCKieEB5dt8Lp19+h/Vm0YUjPHghUAAkAACAASeuhMxcn88f6NBkDBfStWef264YEdGyxXASAABIAAICkXwuAt0256+RcM+3Sx1/AWPPrRiTCg9W3LVQAIAAEgAEjoC2grV93y8i+4c11F/t4BXs8bFD1e9o7T8yxWASAABIAAIBmj5h/Mf57f3QCIDdk0PfpMu8bregMe3LzFUhUAAkAACACSutvf+egGP6/1yPIvGLz1rfD47DNe3+t932JBdRjYPM1SFQACQAAIABJ64tyni3p0+V++a+D21/JfKvQad+3OE4ssVAEgAASAACChJ859tKsoy/8vdw18Kf8wIa/15z28fqdlKgAEgAAQACRjzOzTod/uF4saALG+uyeFkQuqvOaF133emTCw8T3LVAAIAAEgAEjGHRtnFH35X7518N5no9OG3V73+COXI0stUgEgAASAALAQEvr2+bL1JVv+V941ML7LYFl/5LJmryUqAASAABAAAiChG8/MPZb/F3mpA+DPxocHlq8tzwcsVZwLgxqmW6ICQAAIAAEgAJL4yV9tGLT1Twkt/7+4Z/Wysnvt76laYYEKAAEgAASAAEhoCUWLN+nlX3DX2gX5O+GVw+s+csUBy1MACAABIAAEQEJLaOG+8LXK8akJgNgdGz7M34go06cus8+HwRc+tDwFgAAQAAJAACTw+fOsc6H/zimpWv4Ft2+elv/zZfW1v3fvpxanABAAAkAACICE7joXPaQnjcu/YOC2N8KYOaey94yFZYdD/7a3LE4BIAAEgAAQAAncde7jrale/gX9d7wS/ULheHaO/mfVhiE1sy1NASAABIAAEAAJPHBmTnXou+f5XhEA+VsHR3cmHDX/UCZe+/t3rrcwBYAAEAACQAAk4/bN7/Wa5X/5roF7ngsjFlX27nstLD4eBrS+bWEKAAEgAASAAEjgX6Ar1vS65X/5roF7J4bhS7b3ztc++mnjHWfmWZYCQAAIAAEgAEpv9PzD0a13n+m1AZAX/WTxwWUbet9tlrdssSgFgAAQAAJAACRxt7/zYeD213v38r/CfStX9p7vXCysDgObp1mUAkAACAABIABKb9inH2Vm+RfE/z/1ip9bnlxoSQoAASAABIAASOBpc9Ejd7O2/AuGrp+Tf5ZBan9uuWGHBSkABIAAEAACoPTGzD4T+u2anNkAiA3ZND36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ANy1doHFm6aj//pXLRAEgAAQAAKguAEwfPEOSzdNR/8nJ1keCAABIAAEQHEDYMycU6Hv7hcs3rQ4MCE6+vekPwSAABAAAqDIATBk03RLN023+73wisWBABAAAkAAFDcAHli+1tJN09H/8ectDQSAABAAAqC4ATB63pHQZ+9EizctqqIn/bU4+kcACAABIACKGABjZ9aGgdvesHTTdPR/foqFgQAQAAJAABQ3AIat+djSTdNv/o89Z1kgAASAABAAxQ2AEYv2hq9Vjrd4U/Okv+jov2mqZYEAEAACQAAULwAen3029Nv1kqWbpqP/cy9ZFAgAASAABEBxA2Do+jmWbpqO/o88a0kgAASAABAAxQ2Ahz7eYumm7ei/8XVLAgEgAASAACheADxWcTLctud5SzdNv/k/M9mCQAAIAAEgAIoZABfC4C3TLN00Hf0fnhj6t3nSHwJAAAgAAVDEALhv5SpLN21P+rv4muWAABAAAkAAFC8ARs0/GPpUTrB003T0f+pFiwEBIAAEgAAoXgCMnVUTBux4zdJN09H/wWcc/SMABIAAEADFDYC71y60dNN29F//qqWAABAAAkAAFC8Ahi/eaeGm7ei/epKFgAAQAAJAABQvAMbMOR367n7R0k2TAxNC/1ZH/wgAASAABEARA2DIxg8s3LTd7vfCK5YBAkAACAABULwAeOCTdRZu2o7+TzxvESAABIAAEADFC4DR846G2/ZOtHTTpGp86N/yhkWAABAAAkAAFCcAxs6sDYO2/snCTdvRf+3LlgACQAAIAAFQvAC4Z/UyCzdtv/k/9pwFgAAQAAJAAHQ/AN7f19rl8h+5cF/4WuV4SzdlR//9mqdaAJS933SsFgACQAB0NwCWHm27avk/Putc6L9zioWbtqP/mpdc/CHyh85NAkAACIDuBkBNcy48MfPzAXDnugoLN21H/0eedeGHS1blTggAASAAuhsA8fx+fdPl5f/wx1st3LTZHx39N77uwg+R/9QxN3SGnAAQAAKgJwLgQksuPLmwITxWcTL03fO8hZu2o/+zk134IXJn+7SwM1fTreudABAAAuALU93YGb67Z5GFm7aj/8MTPekPIo+0fxA25c50+1onAASAAOhi2nOdYWb97vCTEzPD8INTwl1Vk0jYvY1vh/va34OyNDxa+k92fBRe79wdGkN7j1znBIAAEADGGFOG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">
                    </div>
                    <div class="col-sm-4">
                        <div class="profile-info">
                            <h3><?=(!empty($employee->TitleAhead) ? $employee->TitleAhead.' ' : '').$employee->Name.(!empty($employee->TitleBehind) ? ', '.$employee->TitleBehind : '')?></h3>
                            <h3><?=$employee->NIP?></h3>
                                                        
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="profile-info">
                            <h3>Department <?=$employee->DivisionMain.'-'.$employee->PositionMain?></h3>
                            <h3><?=$employee->EmailPU?></h3>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="text-right">
                            <p><span class="bgx <?=(($employee->StatusEmployeeID == 2) ? 'green': ( ($employee->StatusEmployeeID == 1) ? 'blue':'red' ) )?>">
                            <i class="fa fa-handshake-o"></i> <?=strtoupper($employee->EmpStatus)?>
                            </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-filter"></i> Filter</h4>
				</div>
				<div class="panel-body">
					<form id="form-filter-log" action="" method="post" autocomplete="off">
						<div class="form-group">
							<label>Type</label>
							<select class="form-control" name="type">
								<option value="">-choose one-</option>
							</select>
						</div>
						<div class="form-group">
							<label>Question</label>
							<input type="text" name="question" class="form-control">
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
							       <label>Date</label>
							       <input type="text" name="startDate" id="startDate" class="form-control" placeholder="Start Date">
						        </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
							       <label>Date</label>
							       <input type="text" name="endDate" id="endDate" class="form-control" placeholder="until end date">
						        </div>
							</div>
						</div>
						<button class="btn btn-block btn-primary" type="button"><i class="fa fa-search"></i> Search</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List history</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table-log-emp">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Type</th>
									<th>Question</th>
									<th>Last Time read</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4">No data available in table</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$("#startDate,#endDate").datepicker({
			//
		});

	});
</script>