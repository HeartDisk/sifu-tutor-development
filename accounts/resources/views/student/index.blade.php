x`@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                     Students List</h1>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Student List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Student</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        @can("student-add")
                        <li><a href="{{route('addStudent')}}"
                           class="btn btn-md d-md-none btn-primary"><em
                           class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{route('addStudent')}}"
                           class="btn btn-primary d-none d-md-inline-flex"><em
                           class="icon ni ni-plus"></em><span>Add Student</span></a></li>
                        @endcan
                     </ul>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                   <div class="card-body">
                      <form action="{{route('Students')}}" method="GET">
                         @csrf
                         <div class="row justify-content-between tableper-row">
                            <input name="studentSearch" value="1" type="hidden">
                            <!--<div class="col-md-3">-->
                            <!--   <div class="input-group  input-group-md">-->
                            <!--      <label class="input-group-text" for="inputGroupSelect01">PIC</label>-->
                            <!--      <select name="" class="form-select" id="inputGroupSelect01">-->
                            <!--         <option value="0" selected=""> All</option>-->
                            <!--         <option value="46"> Amira</option>-->
                            <!--         <option value="45"> Sorfina</option>-->
                            <!--         <option value="8"> Fazira</option>-->
                            <!--         <option value="77"> ADIB AZMI</option>-->
                            <!--         <option value="34"> Aidid</option>-->
                            <!--         <option value="57"> Alif Naquiddin</option>-->
                            <!--         <option value="96"> AMNI</option>-->
                            <!--         <option value="24"> Amin</option>-->
                            <!--         <option value="97"> Amirul</option>-->
                            <!--         <option value="81"> 'Arisya Sofea</option>-->
                            <!--         <option value="83"> Assayidatun Najihah</option>-->
                            <!--         <option value="74"> AZFAHSHAZ ZULKEPLI</option>-->
                            <!--         <option value="62"> Customer Service (Test)</option>-->
                            <!--         <option value="100"> Hanim</option>-->
                            <!--         <option value="104"> Iwani</option>-->
                            <!--         <option value="94"> Farah Nadhirah</option>-->
                            <!--         <option value="30"> Faris</option>-->
                            <!--         <option value="47"> Farisd</option>-->
                            <!--         <option value="13"> Gaya</option>-->
                            <!--         <option value="3"> Hafiz</option>-->
                            <!--         <option value="9"> Haizuran</option>-->
                            <!--         <option value="61"> Hariz Irfan</option>-->
                            <!--         <option value="106"> HAZIQ</option>-->
                            <!--         <option value="10"> Juliza</option>-->
                            <!--         <option value="89"> Lieza</option>-->
                            <!--         <option value="21"> Mas</option>-->
                            <!--         <option value="75"> BADRUL HISYAM</option>-->
                            <!--         <option value="51"> Najmy</option>-->
                            <!--         <option value="101"> Ruzaini</option>-->
                            <!--         <option value="73"> Afiq Noromi</option>-->
                            <!--         <option value="32"> Firdaus</option>-->
                            <!--         <option value="2"> Syamil</option>-->
                            <!--         <option value="53"> Afif</option>-->
                            <!--         <option value="67"> Monisha A/p Chandran</option>-->
                            <!--         <option value="14"> Asyraf</option>-->
                            <!--         <option value="12"> Ameer</option>-->
                            <!--         <option value="52"> Amirul</option>-->
                            <!--         <option value="42"> Azreen</option>-->
                            <!--         <option value="16"> Harith</option>-->
                            <!--         <option value="59"> HAZMAN SHAHRILL</option>-->
                            <!--         <option value="18"> Imran</option>-->
                            <!--         <option value="41"> Irfan</option>-->
                            <!--         <option value="36"> KHAIRUL</option>-->
                            <!--         <option value="70"> Muhammad Syafi Amin Bin Mohd Fadzil</option>-->
                            <!--         <option value="58"> Syafiq Syazwan</option>-->
                            <!--         <option value="28"> AREEP</option>-->
                            <!--         <option value="23"> Husna</option>-->
                            <!--         <option value="91"> NAYLI</option>-->
                            <!--         <option value="40"> Batrisyia</option>-->
                            <!--         <option value="26"> Nazira</option>-->
                            <!--         <option value="38"> EEZA</option>-->
                            <!--         <option value="102"> NOORNAZURA</option>-->
                            <!--         <option value="72"> Nor Asyiqin Binti Toni</option>-->
                            <!--         <option value="88"> Azzuwin Azman</option>-->
                            <!--         <option value="6"> Nadia</option>-->
                            <!--         <option value="37"> SHAHIRAH</option>-->
                            <!--         <option value="50"> Shahirah</option>-->
                            <!--         <option value="19"> Rina</option>-->
                            <!--         <option value="35"> Fazira</option>-->
                            <!--         <option value="54"> Syelis</option>-->
                            <!--         <option value="93"> Aliah</option>-->
                            <!--         <option value="99"> Amirah Kadir</option>-->
                            <!--         <option value="65"> ATHIRAH SOLIHEN</option>-->
                            <!--         <option value="22"> ATIRAH</option>-->
                            <!--         <option value="79"> Fatihah Roslan</option>-->
                            <!--         <option value="5"> Azera</option>-->
                            <!--         <option value="44"> Hafizah</option>-->
                            <!--         <option value="86"> HANNANI HAMZAH</option>-->
                            <!--         <option value="98"> Hidayah</option>-->
                            <!--         <option value="105"> Nur Izzati</option>-->
                            <!--         <option value="43"> AMIR</option>-->
                            <!--         <option value="103"> Najiihah</option>-->
                            <!--         <option value="20"> Shahirah</option>-->
                            <!--         <option value="25"> Suhaila</option>-->
                            <!--         <option value="1"> Suziani</option>-->
                            <!--         <option value="69"> Nur Syafiqah Radhuan</option>-->
                            <!--         <option value="87"> AINI KHALIM</option>-->
                            <!--         <option value="55"> Nurfatin Munirah Binti Mohd Azman</option>-->
                            <!--         <option value="76"> Safwah Shaharin</option>-->
                            <!--         <option value="49"> Nurizan</option>-->
                            <!--         <option value="64"> Nurul Azuha Binti Nazmi</option>-->
                            <!--         <option value="60"> Azwani Sulaiman</option>-->
                            <!--         <option value="90"> DIAYANA HAMDAN</option>-->
                            <!--         <option value="82"> Tehah</option>-->
                            <!--         <option value="68"> Nurul Hasmida Azmi</option>-->
                            <!--         <option value="31"> Syida</option>-->
                            <!--         <option value="39"> Aqila</option>-->
                            <!--         <option value="71"> QAISARAH</option>-->
                            <!--         <option value="84"> RIA</option>-->
                            <!--         <option value="80"> Ros Nabilah</option>-->
                            <!--         <option value="27"> Shafiqa</option>-->
                            <!--         <option value="33"> Siti</option>-->
                            <!--         <option value="92"> Cahaya</option>-->
                            <!--         <option value="15"> Syaza</option>-->
                            <!--         <option value="4"> Adda</option>-->
                            <!--         <option value="29"> Sitinor</option>-->
                            <!--         <option value="78"> SYARIFAH</option>-->
                            <!--         <option value="11"> Tutor Coordinator</option>-->
                            <!--         <option value="85"> Syafiqa Ismail</option>-->
                            <!--         <option value="56"> Wazeerah Azmi</option>-->
                            <!--         <option value="17"> Izyan</option>-->
                            <!--         <option value="7"> Zam</option>-->
                            <!--      </select>-->
                            <!--   </div>-->
                            <!--</div>-->
                            <div class="col-md-5">
                               <div class="input-group  input-group-md">
                                  <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                  <!--<select name="status" class="form-select" id="inputGroupSelect01">-->
                                  <!--   <option value="{{ Request::get('status')}}"-->
                                  <!--      selected=""> {{ Request::get('status')}} </option>-->
                                  <!--   <option value="active"> Active</option>-->
                                  <!--   <option value="inactive"> Inactive</option>-->
                                  <!--   <option value="pending"> Pending</option>-->
                                  <!--</select>-->
                                    <select name="status" class="status form-select" id="inputGroupSelect01">
                                        <option value="Active" {{ Request::get('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ Request::get('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Pending" {{ Request::get('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                               </div>
                            </div>
                            <div class="col-md-5">
                               <div class="input-group input-group-md">
                                  <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                  <input name="search" type="text" class="form-control"
                                     aria-label="Sizing example input"
                                     aria-describedby="inputGroup-sizing-sm"
                                     value="{{ Request::get('search')}}" placeholder="Student Name">
                               </div>
                            </div>
                            <div class="col-md-2">
                               <div class="input-group input-group-md">
                                  <input type="submit" class="btn btn-primary btn-md"
                                     aria-label="Sizing example input" value="Search"
                                     aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                         </div>
                      </form>
                      @if (\Session::has('success'))
                      <div class="alert alert-success">
                         <ul>
                            <li>{!! \Session::get('success') !!}</li>
                         </ul>
                      </div>
                      @endif
                      @if (\Session::has('update'))
                      <div class="alert alert-primary">
                         <ul>
                            <li>{!! \Session::get('update') !!}</li>
                         </ul>
                      </div>
                      @endif
                      <table class="datatable-init table" data-nk-container="table-responsive">
                         <thead>
                            <tr>
                               <th>#</th>
                               <th>Student ID</th>
                               <th>Parent Name</th>
                               <th>Full Name</th>
                               <th>Gender</th>
                               <th>Age</th>
                               <th>Status</th>
                               <th>Registration Date</th>
                               <th>Action</th>
                            </tr>
                         </thead>
                         <tbody id="studentListAjaxCallBody">
                           
                            @foreach($students as $key=>$rowStudents)
                            @if($rowStudents->full_name != '')
                            @if($rowStudents->is_deleted == 0)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td><i class="fa fa-user"></i> {{$rowStudents->student_id}}</td>
                               <td>
                                  @php
                                  $customers = DB::table('customers')->where('id','=',$rowStudents->customer_id)->get();
                                  @endphp
                                  @if(!$customers->isEmpty())
                                  {{$customers[0]->full_name}}
                                  @endif
                               </td>
                               <td>{{$rowStudents->full_name}}</td>
                               <td>
                                  {{$rowStudents->gender}}
                               </td>
                               <td>{{$rowStudents->age}} Years</td>
                               <td>
                                  @if($rowStudents->status == "inactive")
                                  <p class="dtable-status-inactive">{{$rowStudents->status}}</p>
                                  @elseif($rowStudents->status == "pending")
                                  <p class="dtable-status-pending">{{$rowStudents->status}}</p>
                                  @elseif($rowStudents->status == "active")
                                  <p class="dtable-status-active">{{$rowStudents->status}}</p>
                                  @endif
                               </td>
                               <td>{{$rowStudents->register_date}}</td>
                               <td>
                                  <a class="dtable-cbtn bt-dashboard dtb-tooltip" dtb-tooltip="Student Dashboard" href="{{route('studentDashboard',$rowStudents->id)}}"><i class="fa fa-dashboard"></i> </a>
                                  @can("student-detail")
                                  <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Student Detail" href="{{route('viewStudent',$rowStudents->id)}}"><i class="fa fa-eye"></i> </a>
                                  @endcan
                                  @can("student-edit")
                                  <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Student" href="{{route('editStudent',$rowStudents->id)}}"><i class="fa fa-edit"></i> </a>
                                  @endcan
                                  @can("student-delete")
                                  <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Student" onclick="return confirm('Are you sure you want to delete this student?');" href="{{route('deleteStudent',$rowStudents->id)}}"><i class="fa fa-trash"></i> </a>
                                  @endcan
                               </td>
                            </tr>
                            @endif
                            @endif
                            @endforeach
                         </tbody>
                      </table>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script>
       $(document).ready(function(){
           $("#studentTable").submit(function(event){
               event.preventDefault();
               var formValues = $(this).serialize();
               var studentStatus = $(".status").val();
               var ajaxCall = "studentList";
               console.log(ajaxCall);
   
                       $.ajax({
                        type:'POST',
                        url:'{{route("ajaxCall")}}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data){
                           //$("#result").html(data);
                           $("#"+ajaxCall+"AjaxCallBody").hide();
                           console.log(data);
                        }
                     });
           });
       });
   </script -->
@endsection
