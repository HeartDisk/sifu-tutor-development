@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Add Notification
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Notification List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Notification</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>

            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
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
                     <div class="bio-block">
                        <form method="POST" action="{{route('submitNotification')}}">
                        @csrf
                        <div class="row g-3">
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="notificationType" class="form-label">Notification Type</label>
                                 <div class="form-control-wrap">
                                    <select required class="form-control notificationType" name="notificationType" id="notificationType">
                                       <option value="">--- Select ---</option>
                                       <option value="Schedule Class">Schedule Class</option>
                                       <!--<option value="Submit Evaluation Report">Submit Evaluation Report</option>-->
                                       <option value="Submit Progress Report">Submit Progress Report</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 progressReportMonth d-none">
                              <div class="form-group">
                                 <label for="progressReportMonth" class="form-label">Progress Report Month</label>
                                 <div class="form-control-wrap">
                                    <select class="form-control" name="progressReportMonth" id="progressReportMonth">
                                       <option value="">Please select month</option>
                                       <option value="January">January</option>
                                       <option value="February">February</option>
                                       <option value="March">March</option>
                                       <option value="April">April</option>
                                       <option value="May">May</option>
                                       <option value="June">June</option>
                                       <option value="July">July</option>
                                       <option value="August">August</option>
                                       <option value="September">September</option>
                                       <option value="October">October</option>
                                       <option value="November">November</option>
                                       <option value="December">December</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 tutor d-none">
                              <div class="form-group">
                                 <label for="tutorID" class="form-label">Tutor</label>
                                 <div class="form-control-wrap">
                                    <select class="js-select" data-search="true" data-sort="false" name="tutorID" id="tutorID">
                                       <option value=""></option>
                                       @foreach($tutors as $rowTutor)
                                       <option value="{{$rowTutor->id}}"> ({{$rowTutor->tutor_id}}) - {{$rowTutor->full_name}} - {{$rowTutor->id}} </option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 student d-none">
                              <div class="form-group">
                                 <label for="studentID" class="form-label">Student</label>
                                 <div class="form-control-wrap">
                                    <select class="" data-search="true" data-sort="false" name="studentID" id="studentID">
                                       <option value=""></option>
                                       @foreach($students as $rowStudent)
                                       <option value="{{$rowStudent->id}}"> ({{$rowStudent->student_id}}) - {{$rowStudent->full_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 subject d-none">
                              <div class="form-group">
                                 <label for="subjectID" class="form-label">Subject</label>
                                 <div class="form-control-wrap">
                                    <select class="" data-search="true" data-sort="false" name="subjectID" id="subjectID">
                                        <option value=""></option>
                                       @foreach($subjects as $rowSubject)
                                       <option value="{{$rowSubject->id}}"> {{$rowSubject->name}} </option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-2"><button class="btn btn-primary" type="submit">Submit</button></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
//   document.addEventListener('DOMContentLoaded', function () {
//       const notificationType = document.getElementById('notificationType');
//       const tutorField = document.querySelector('.tutor');
//       const studentField = document.querySelector('.student');
//       const subjectField = document.querySelector('.subject');
//       const progressReportMonthField = document.querySelector('.progressReportMonth');

//       function toggleFields() {
//          const type = notificationType.value;
         
//          // Hide all fields initially
//          tutorField.classList.add('d-none');
//          studentField.classList.add('d-none');
//          subjectField.classList.add('d-none');
//          progressReportMonthField.classList.add('d-none');

//          // Show fields based on selected type
//          if (type === 'Schedule Class') {
//             tutorField.classList.remove('d-none');
//             studentField.classList.remove('d-none');
//             subjectField.classList.remove('d-none');
//          } else if (type === 'Submit Evaluation Report') {
//             tutorField.classList.remove('d-none');
//             studentField.classList.remove('d-none');
//             subjectField.classList.remove('d-none');
//          } else if (type === 'Submit Progress Report') {
//             tutorField.classList.remove('d-none');
//             studentField.classList.remove('d-none');
//             subjectField.classList.remove('d-none');
//             progressReportMonthField.classList.remove('d-none');
//          }
//       }

//       // Trigger the toggleFields function on load and change event
//       toggleFields(); // Initial load
//       notificationType.addEventListener('change', toggleFields);
//   });

document.addEventListener('DOMContentLoaded', function () {
    const notificationType = document.getElementById('notificationType');
    const tutorField = document.querySelector('.tutor');
    const studentField = document.querySelector('.student');
    const subjectField = document.querySelector('.subject');
    const progressReportMonthField = document.querySelector('.progressReportMonth');
    const tutorSelect = document.getElementById('tutorID');
    const studentSelect = document.getElementById('studentID');
    const subjectSelect = document.getElementById('subjectID');

    function toggleFields() {
        const type = notificationType.value;

        // Hide all fields initially
        tutorField.classList.add('d-none');
        studentField.classList.add('d-none');
        subjectField.classList.add('d-none');
        progressReportMonthField.classList.add('d-none');

        // Show fields based on selected type
        if (type === 'Schedule Class' || type === 'Submit Evaluation Report') {
            tutorField.classList.remove('d-none');
            studentField.classList.remove('d-none');
            subjectField.classList.remove('d-none');
        } else if (type === 'Submit Progress Report') {
            tutorField.classList.remove('d-none');
            studentField.classList.remove('d-none');
            subjectField.classList.remove('d-none');
            progressReportMonthField.classList.remove('d-none');
        }
    }

    function fetchStudentSubjects(studentID, tutorID) {
        const subjectSelect = document.getElementById('subjectID'); 
        if (!subjectSelect) {
            console.error("Dropdown element with id 'subjectID' not found.");
            return;
        }

        fetch("{{ route('getStudentSubjects') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ studentID: studentID, tutorID: tutorID })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data); 

            if (window.choicesInstanceSubjects) {
                // Clear existing choices
                window.choicesInstanceSubjects.clearChoices();
            } else {
                // If choicesInstance does not exist, initialize it
                window.choicesInstanceSubjects = new Choices(subjectSelect, {
                    searchEnabled: true,
                    itemSelectText: 'Select Subject',
                    allowHTML: true // Set allowHTML to true
                });
            }

            // Prepare new choices
            const newChoices = data.map(subject => ({
                value: subject.subject_id,
                label: subject.subject_name,
                selected: false,
                disabled: false
            }));

            // Set new choices all at once
            window.choicesInstanceSubjects.setChoices(newChoices, 'value', 'label', true);
        })
        .catch(error => console.error('Error fetching subjects:', error));
    }
    
    function fetchTutorStudents(tutorID) {
        const studentSelect = document.getElementById('studentID'); 
        if (!studentSelect) {
            console.error("Dropdown element with id 'studentID' not found.");
            return;
        }
    
        fetch("{{ route('getTutorStudents') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tutorID: tutorID })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received data for students:', data); 
    
            // Check if the Choices instance is already initialized
            if (!window.choicesInstanceStudents) {
                window.choicesInstanceStudents = new Choices(studentSelect, {
                    searchEnabled: true,
                    itemSelectText: 'Select Student',
                    allowHTML: true // Set allowHTML to true
                });
            } else {
                // Clear the current choices without re-initializing
                window.choicesInstanceStudents.clearChoices();
            }
    
            const newChoices = data.map(student => ({
                value: student.studentID,
                label: student.studentName, // Ensure you use the correct property
                selected: false,
                disabled: false
            }));
    
            // Update the Choices instance with the new choices
            window.choicesInstanceStudents.setChoices(newChoices, 'value', 'label', true);
        })
        .catch(error => console.error('Error fetching students:', error));
    }


    // Trigger field visibility based on notification type
    toggleFields(); // Initial load
    notificationType.addEventListener('change', toggleFields);

    // Trigger subject fetching when a tutor is selected
    tutorSelect.addEventListener('change', function () {
        const tutorID = this.value;
        if (tutorID) {
            fetchTutorStudents(tutorID);
        } else {
            studentSelect.innerHTML = '<option value="">Select Student</option>'; // Clear if no tutor is selected
            if (window.choicesInstanceStudents) {
                window.choicesInstanceStudents.clearChoices(); // Clear previous choices
            }
        }
    });

    // Trigger subject fetching when a student is selected
    studentSelect.addEventListener('change', function () {
        const studentID = this.value;
        const tutorID = tutorSelect.value; // Get the selected tutorID

        if (studentID) {
            fetchStudentSubjects(studentID, tutorID);
        } else {
            subjectSelect.innerHTML = '<option value="">Select Subject</option>'; // Clear if no student is selected
            if (window.choicesInstanceSubjects) {
                window.choicesInstanceSubjects.clearChoices(); // Clear previous choices
            }
        }
    });

});


</script>
@endsection
