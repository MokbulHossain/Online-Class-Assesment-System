@extends('layouts.backend.app')
@section('content')
<div class="row-fluid sortable">	
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon th-large"></i><span class="break"></span> Your Marks</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					<div class="box-content">
              <style type="text/css">
                input{width: 75%;}
              </style>
						<table class="table table-striped table-bordered ">
						  <thead>
							  <tr>
								 <th>Course Name</th>
				                  <th>Assignment</th>
				                  <th>Presentation</th>
				                  <th>class Test</th>
				                  <th>Attendance</th>
				                  <th>Mid</th>
				                  <th>Final</th>
				                  <th>GT</th>
				                  <th>GRD</th>
				                  <th>GP</th>
							  </tr>
						  </thead>   

						  <tbody id="change_it">
						 @if(count($marks)>0)
						  @foreach($marks as $mark)
						  <tr>
						  	<td>{{$mark->course_name}}</td>
						  	<td>{{$mark->assignment}}</td>
						  	<td>{{$mark->presentation}}</td>
						  	<td>{{$mark->class_test}}</td>
						  	<td>{{$mark->attendance}}</td>
						  	<td>{{$mark->mid}}</td>
						  	<td>{{$mark->final}}</td>
						  	<td>{{$mark->GT}}</td>
						  	<td>{{$mark->GRD}}</td>
						  	<td>{{$mark->GP}}</td>
						  </tr>
						  @endforeach
						 @endif				
						  </tbody>
					  </table>  
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
@endsection
