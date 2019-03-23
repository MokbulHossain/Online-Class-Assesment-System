@extends('layouts.backend.app')
@push('css')

@endpush
@section('content')

<a href="{{route('teacher.attandance')}}" class="btn btn-large btn-info">Back</a><br><br>
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
						<h2><i class="halflings-icon th-large"></i><span class="break"></span>Today's Attendance</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form method="post" action="{{route('teacher.submit_attandance')}}">
							@csrf
						<table class="table table-striped table-bordered ">
						  <thead>
							  <tr>
								  <th>Id</th>
								  <th>Name</th>
								  <th>Attendance</th>
							  </tr>
						  </thead>   

						  <tbody id="change_it">
											
						  </tbody>
					  </table>
					  <div class="row">
					  <div class="span6"></div>
					  <div class="span6"><input type="submit" class='btn btn-primary'> </div>
					   </div>
					  </form>	  
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
                    url: 'select_student',
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
			</script>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.js"></script>
<script type="text/javascript">
        function add(id){
                   event.preventDefault();
                   document.getElementById('add-form-'+id).submit();
                }

            function remove(id){
                event.preventDefault();
                document.getElementById('remove-form-'+id).submit();
            }

            
    </script>

@endpush