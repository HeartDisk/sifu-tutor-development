@extends('layouts.main')
@section('content')
<div class="nk-content">
   <div class="fluid-container sifu-view-page">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content mb-4">
                     <h2 class="nk-block-title">
                     STUDENT - TUTOR ASSIGNMENTS</h1>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Student Assignment</a></li>
                        </ol>
                     </nav>
                  </div>
               </div>
               <div class="nk-block">
                  <div class="card">
                    <div class="card-body">
                     <form action="{{route('studentAssignments')}}" method="GET">
                     <div class="row justify-content-between tableper-row">
                        <!--<div class="col-md-3">-->
                        <!--   <div class="input-group  input-group-md">-->
                        <!--      <label class="input-group-text" for="inputGroupSelect01">PIC</label>-->
                        <!--      <select name="status" class="form-select" id="inputGroupSelect01">-->
                        <!--         <option value="0" selected=""> All</option>-->
                        <!--         <option value="46"> Amira</option>-->
                        <!--         <option value="45">  Sorfina</option>-->
                        <!--         <option value="8"> Fazira</option>-->
                        <!--         <option value="77"> ADIB AZMI</option>-->
                        <!--         <option value="34"> Aidid </option>-->
                        <!--         <option value="57"> Alif Naquiddin</option>-->
                        <!--         <option value="96"> AMNI</option>-->
                        <!--         <option value="24"> Amin</option>-->
                        <!--         <option value="97"> Amirul </option>-->
                        <!--         <option value="81"> 'Arisya Sofea </option>-->
                        <!--         <option value="83"> Assayidatun Najihah </option>-->
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
                        <!--         <option value="105"> Nur Izzati </option>-->
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
                        <!--         <option value="33"> Siti </option>-->
                        <!--         <option value="92"> Cahaya</option>-->
                        <!--         <option value="15"> Syaza</option>-->
                        <!--         <option value="4"> Adda</option>-->
                        <!--         <option value="29"> Sitinor</option>-->
                        <!--         <option value="78"> SYARIFAH</option>-->
                        <!--         <option value="11"> Tutor Coordinator</option>-->
                        <!--         <option value="85"> Syafiqa Ismail</option>-->
                        <!--         <option value="56"> Wazeerah Azmi </option>-->
                        <!--         <option value="17"> Izyan</option>-->
                        <!--         <option value="7"> Zam</option>-->
                        <!--      </select>-->
                        <!--   </div>-->
                        <!--</div>-->
                        <div class="col-md-5">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Status</label>
                              <!--<select name="status" class="form-select" id="inputGroupSelect01">-->
                              <!--   <option value=""> All</option>-->
                              <!--   <option value="Pending"> Pending</option>-->
                              <!--   <option value="Complete"> Complete</option>-->
                              <!--   <option value="Incomplete"> Incomplete</option>-->
                              <!--   <option value="Undercharge"> Undercharge</option>-->
                              <!--</select>-->
                               <select name="status" class="status form-select" id="inputGroupSelect01">
                                   <option value=""> All</option>
                                    <option value="Pending" {{ Request::get('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Complete" {{ Request::get('status') == 'Complete' ? 'selected' : '' }}>Complete</option>
                                    <option value="Incomplete" {{ Request::get('status') == 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                                    <option value="Undercharge" {{ Request::get('status') == 'Undercharge' ? 'selected' : '' }}>Undercharge</option>
                                </select>
                           </div>
                        </div>
                        <div class="col-md-5">
                           <div class="input-group input-group-md">
                              <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                              <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Student Name" value="{{ Request::get('search') }}">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <input type="submit" class="btn btn-primary" style="background-color:#304bd4;" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
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
                              <th>Student Id</th>
                              <th>Fullname</th>
                              <th>Staff In Charge</th>
                              <th>Subject</th>
                              <th>Tutor</th>
                           </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$dt)
                            @php
                            $job_ticket=DB::table("job_tickets")->where("id",$dt->ticket_id)->first();
                            $staff=DB::table("staffs")->where("id",$job_ticket->admin_charge)->first();

                            @endphp
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    <span class="fa fa-user user-active" title="Individual"></span>
                                    {{$dt->student_id}}
                                </td>
                                <td>{{$dt->student_name}}</td>
                                <td>{{$staff->full_name}}</td>
                                <td>{{$dt->product_name}}</td>
                                <td>{{$dt->tutor_name}}</td>
                            </tr>
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
</div>
@endsection
