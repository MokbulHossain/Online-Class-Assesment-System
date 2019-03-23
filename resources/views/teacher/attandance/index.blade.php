@extends('layouts.backend.app')
@push('css')

@endpush
@section('content')


	<div class="row-fluid sortable">	
	
			<a href="{{route('teacher.todays')}}" class="btn btn-large btn-info">Today's Attandance</a>	

             




				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon th-large"></i><span class="break"></span> Attandance</h2>


						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Id</th>
								  <th>Name</th>
								  <th>Course Name</th>
								  <th>Total Attandance</th>
							  </tr>
						  </thead>  
						  <tfoot>
							  <tr>
								  <th>Id</th>
								  <th>Name</th>
								  <th>Course Name</th>
								  <th>Total Attandance</th>

							  </tr>
						  </tfoot>  
						  <tbody>
						  	@if(count($courses)>0)
						  	 @foreach($courses as $course)
						  	    @if(count($course->students)>0)
						  	       @foreach($course->students as $student) 
						  	          @php $count=0; @endphp
                                       <tr>
                           	     <td>{{$student->id}}</td>
                           	     <td>{{$student->name}}</td>
                           	     <td>{{$course->name}}</td>
                           	       @if(count($student->attandance)>0)
                           	        @foreach($student->attandance as $attandance)
                                       @if($attandance->student_id == $student->id && $attandance->teacher_id == Auth::user()->id &&  $attandance->course_name ==  $course->name && $attandance->is_attend == 1)
                                          @php $count++; @endphp
                                       @endif
                           	        @endforeach
                           	       @endif
                           	     <td>{{$count}}</td>
                                </tr>
						  	        @endforeach
						  	     @endif
						  	  
						  	 @endforeach
						  	@endif
                           
						  	
								
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
@endsection
