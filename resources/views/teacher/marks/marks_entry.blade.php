@extends('layouts.backend.app')
@push('css')

@endpush
@section('content')
<style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
        <div class="row">
        	<div class="span4" ></div>
        	<div class="span6" >
        		<b>Select Course : </b>
        		<select onchange="select_course()" id="mySelect">
            	<option>--------</option>
            	@if(count($courses)>0)
            	  @foreach($courses as $course)
            	    <option value="{{$course->id}}">{{$course->name}}</option>
            	  @endforeach
            	@endif
            </select>
        	</div>
        </div>
            

	<div class="row-fluid sortable">	
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon th-large"></i><span class="break"></span>Enter Student Marks</h2>
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
								  <th>Id</th>
								  <th>Name</th>
                  <th>Assignment</th>
                  <th>Presentation</th>
                  <th>class Test</th>
                  <th>Attendance</th>
                  <th>Mid</th>
                  <th>Final</th>
                  <th>GT</th>
                  <th>GRD</th>
                  <th>GP</th>
								  <th>Action</th>
							  </tr>
						  </thead>   

						  <tbody id="change_it">
											
						  </tbody>
					  </table>  
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
			<script type="text/javascript">
				function select_course(){
               var c_id = document.getElementById("mySelect").value;
               var change_it = document.getElementById("change_it");
               if (c_id != '') {

               $.ajax({
                    type: 'POST',
                    url: 'student_with_marks',
                    data: {
                     "_token" : $('meta[name=_token]').attr('content'), 
                      c_id: c_id 
                    },   
                    success: function (msg) { 
                    	change_it.innerHTML=msg;
                    }
                });
               }


      }
      function myFunction(id){

          var assignment = document.getElementById('assignment'+id).value;
          var presentation = document.getElementById('presentation'+id).value;
          var class_test = document.getElementById('class_test'+id).value;
          var attendance = document.getElementById('attendance'+id).value;
          var mid = document.getElementById('mid'+id).value;
          var final = document.getElementById('final'+id).value;
          var GT = document.getElementById('GT'+id);
          var GRD = document.getElementById('GRD'+id);
          var GP = document.getElementById('GP'+id);
         var total=parseFloat(assignment)+parseFloat(presentation)+parseFloat(class_test)+parseFloat(attendance)+parseFloat(mid)+parseFloat(final);
         GT.innerHTML=total;
         if (total<40) {GRD.innerHTML='F';GP.innerHTML=0.00;}
         else if (total>=40 && total <45) {GRD.innerHTML='D';GP.innerHTML=2.00;}
         else if (total>=45 && total <50) {GRD.innerHTML='C';GP.innerHTML=2.25;}
         else if (total>=50 && total <55) {GRD.innerHTML='C+';GP.innerHTML=2.50;}
         else if (total>=55 && total <60) {GRD.innerHTML='B-';GP.innerHTML=2.75;}
         else if (total>=60 && total <65) {GRD.innerHTML='B';GP.innerHTML=3.00;}
         else if (total>=65 && total <70) {GRD.innerHTML='B+';GP.innerHTML=3.25;}
         else if (total>=70 && total <75) {GRD.innerHTML='A-';GP.innerHTML=3.50;}
         else if (total>=75 && total <80) {GRD.innerHTML='A';GP.innerHTML=3.75;}
         else if (total>=80 ) {GRD.innerHTML='A+';GP.innerHTML=4.00;}
      }

      function update_marks(id){
          var course_name = document.getElementById('course_name').value;
          var assignment = document.getElementById('assignment'+id).value;
          var presentation = document.getElementById('presentation'+id).value;
          var class_test = document.getElementById('class_test'+id).value;
          var attendance = document.getElementById('attendance'+id).value;
          var mid = document.getElementById('mid'+id).value;
          var final = document.getElementById('final'+id).value;
          var GT = document.getElementById('GT'+id).innerHTML;
          var GRD = document.getElementById('GRD'+id).innerHTML;
          var GP = document.getElementById('GP'+id).innerHTML;
            $.ajax({
                    type: 'POST',
                    url: 'update_marks',
                    data: {
                     "_token" : $('meta[name=_token]').attr('content'), 
                      course_name: course_name, 
                      assignment: assignment, 
                      presentation: presentation ,
                      class_test: class_test ,
                      attendance: attendance ,
                      mid: mid ,
                      final: final ,
                      GT: GT ,
                      GRD: GRD ,
                      GP: GP ,
                      s_id:id,
                    },   
                    success: function (msg) { 
                     alert(msg);
                    }
                });
      }
    
  			</script>
@endsection
