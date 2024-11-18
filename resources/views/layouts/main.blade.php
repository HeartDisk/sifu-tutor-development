<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <link rel="icon" type="image/x-icon" href="{{asset('public/template/education.png')}}">
      <!-- Scripts -->
      <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
      <!-- Fonts -->
      <link rel="dns-prefetch" href="//fonts.gstatic.com">
      <!-- Styles -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
      <link rel="stylesheet" href="{{ asset('template/assets/css/style926d.css') }}">
      <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>-->
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css'>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link href="{{asset('public/css/jQueryTab.css')}}" rel="stylesheet" />
      <link href="{{asset('public/css/animation.css')}}" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="{{asset('public/css/shahroz.css')}}">
   </head>
   <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
      <div id="app">
         <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            </nav> -->
         <div class="nk-app-root">
            <div class="nk-main">
               <div class="nk-sidebar nk-sidebar-fixed is-theme" id="sidebar">
                  <div style="height:60px; background:white;" class="nk-sidebar-element nk-sidebar-head">
                     <div class="nk-sidebar-brand" style="justify-content: center;">
                        <a href="{{url('/home')}}" class="logo-link">
                           <div class="logo-wrap">
                              <img src="{{asset('template/login.png')}}" style="width: 150px; object-fit: cover;"/>
                           </div>
                        </a>
                        <div class="nk-compact-toggle me-n1">
                           <button class="btn btn-md btn-icon text-light btn-no-hover compact-toggle">
                           <em class="icon off ni ni-chevrons-left"></em>
                           <em class="icon on ni ni-chevrons-right"></em>
                           </button>
                        </div>
                        <div class="nk-sidebar-toggle me-n1">
                           <button class="btn btn-md btn-icon text-light btn-no-hover sidebar-toggle">
                           <em class="icon ni ni-arrow-left"></em>
                           </button>
                        </div>
                     </div>
                  </div>
                  @include('partial/sideNavigationBasic')
               </div>
               <div class="nk-wrap">
                  <div class="nk-header nk-header-fixed">
                     <div class="container-fluid">
                        <div class="nk-header-wrap">
                           <div class="nk-header-logo ms-n1">
                              <div class="nk-sidebar-toggle">
                                 <button class="btn btn-sm btn-icon btn-zoom sidebar-toggle d-sm-none">
                                 <em class="icon ni ni-menu"></em>
                                 </button>
                                 <button class="btn btn-md btn-icon btn-zoom sidebar-toggle d-none d-sm-inline-flex">
                                 <em class="icon ni ni-menu"></em>
                                 </button>
                              </div>
                           </div>
                           <nav class="nk-header-menu nk-navbar">
                           </nav>
                           <div class="nk-header-tools">
                              <ul class="nk-quick-nav ms-2">
                                 <li class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown">
                                       <div class="d-sm-none">
                                          <div class="media media-md media-circle">
                                             <img src="{{asset('template/images/avatar/a.jpg')}}" alt="" class="img-thumbnail">
                                          </div>
                                       </div>
                                       <div class="row align-items-center p-0 rounded" style="width:170px;padding:5px !important;padding-left: 13px !important;background-color:#efefef;">
                                          <div class="col-9 p-0">
                                             <p style="color:#000;font-size:14px;font-weight:500">{{Auth::user()->name}}</p>
                                          </div>
                                          <div class="col-3 p-0">
                                             <img src="{{asset('public/template/education.png')}}" alt=""  class="img-thumbnail">
                                          </div>
                                       </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md">
                                       <div class="dropdown-content dropdown-content-x-lg py-3 border-bottom border-light">
                                          <div class="media-group">
                                             <div class="media media-xl media-middle media-circle">
                                                <img src="{{asset('template/images/avatar/a.jpg')}}" alt="" class="img-thumbnail">
                                             </div>
                                             <div class="media-text">
                                                <div class="lead-text">{{Auth::user()->name}}</div>
                                                <span class="sub-text">
                                                @php
                                                $roleName = DB::table('roles')->where('id','=',Auth::user()->role)->first();
                                                echo $roleName->name;
                                                @endphp
                                                </span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="dropdown-content dropdown-content-x-lg py-3 border-bottom border-light">
                                          <ul class="link-list">
                                             <li>
                                                <a href="{{route('profile', Auth::user()->id)}}">
                                                <em class="icon ni ni-user"></em>
                                                <span>My Profile</span>
                                                </a>
                                             </li>
                                             <li>
                                                <a href="{{route('changePassword', Auth::user()->id)}}">
                                                <em class="icon ni ni-setting-alt"></em>
                                                <span>Change Password</span>
                                                </a>
                                             </li>
                                             <li>
                                                <a href="{{url('truncate-tables')}}">
                                                <em class="icon ni ni-trash"></em>
                                                <span>Flush Data</span>
                                                </a>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="dropdown-content dropdown-content-x-lg py-3">
                                          <ul class="link-list">
                                             <li>
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                                <em class="icon ni ni-signout"></em>
                                                {{ __('Logout') }}
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                   @csrf
                                                </form>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  @yield('content')
                  <div class="nk-footer">
                     <div class="container-fluid">
                        <div class="nk-footer-wrap">
                           <div class="nk-footer-copyright"> &copy; 2024 SifuTutor. Developed by <a href="https://brainiaccreation.com/" target="_blank" class="text-reset">Brainiac Creation</a>
                           </div>
                           <div class="nk-footer-links">
                              <ul class="nav nav-sm">
                                 <li class="nav-item">
                                    <a href="#" class="nav-link">About</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="#" class="nav-link">Support</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="#" class="nav-link">Blog</a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </body>
   <script src="{{ asset('template/assets/js/bundle.js') }}"></script>
   <script src="{{ asset('template/assets/js/scripts.js') }}"></script>
   <div class="offcanvas offcanvas-end offcanvas-size-lg" id="notificationOffcanvas">
      <div class="offcanvas-header border-bottom border-light">
         <h5 class="offcanvas-title" id="offcanvasTopLabel">Recent Notification</h5>
         <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body" data-simplebar>
         <ul class="nk-schedule">
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content">
                     <span class="smaller">2:12 PM</span>
                     <div class="h6">Added 3 New Images</div>
                     <ul class="d-flex flex-wrap gap g-2 py-2">
                        <li>
                           <div class="media media-xxl">
                              <img src="{{asset('template/images/product/a.jpg') }}" alt="" class="img-thumbnail">
                           </div>
                        </li>
                        <li>
                           <div class="media media-xxl">
                              <img src="{{asset('template/images/product/b.jpg') }}" alt="" class="img-thumbnail">
                           </div>
                        </li>
                        <li>
                           <div class="media media-xxl">
                              <img src="{{asset('template/images/product/c.jpg') }}" alt="" class="img-thumbnail">
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
            </li>
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content">
                     <span class="smaller">4:23 PM</span>
                     <div class="h6">Invitation for creative designs pattern</div>
                  </div>
               </div>
            </li>
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content nk-schedule-content-no-border">
                     <span class="smaller">10:30 PM</span>
                     <div class="h6">Task report - uploaded weekly reports</div>
                     <div class="list-group-dotted mt-3">
                        <div class="list-group-wrap">
                           <div class="p-3">
                              <div class="media-group">
                                 <div class="media rounded-0">
                                    <img src="{{asset('template/images/icon/file-type-pdf.svg') }}" alt="">
                                 </div>
                                 <div class="media-text ms-1">
                                    <a href="#" class="title">Modern Designs Pattern</a>
                                    <span class="text smaller">1.6.mb</span>
                                 </div>
                              </div>
                           </div>
                           <div class="p-3">
                              <div class="media-group">
                                 <div class="media rounded-0">
                                    <img src="{{asset('template/images/icon/file-type-doc.svg') }}" alt="">
                                 </div>
                                 <div class="media-text ms-1">
                                    <a href="#" class="title">Cpanel Upload Guidelines</a>
                                    <span class="text smaller">18kb</span>
                                 </div>
                              </div>
                           </div>
                           <div class="p-3">
                              <div class="media-group">
                                 <div class="media rounded-0">
                                    <img src="{{asset('template/images/icon/file-type-code.svg') }}" alt="">
                                 </div>
                                 <div class="media-text ms-1">
                                    <a href="#" class="title">Weekly Finance Reports</a>
                                    <span class="text smaller">10mb</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </li>
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content">
                     <span class="smaller">3:23 PM</span>
                     <div class="h6">Assigned you to new database design project</div>
                  </div>
               </div>
            </li>
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content nk-schedule-content-no-border flex-grow-1">
                     <span class="smaller">5:05 PM</span>
                     <div class="h6">You have received a new order</div>
                     <div class="alert alert-info mt-2" role="alert">
                        <div class="d-flex">
                           <em class="icon icon-lg ni ni-file-code opacity-75"></em>
                           <div class="ms-2 d-flex flex-wrap flex-grow-1 justify-content-between">
                              <div>
                                 <h6 class="alert-heading mb-0">Business Template - UI/UX design</h6>
                                 <span class="smaller">Shared information with your team to understand and contribute to your project.</span>
                              </div>
                              <div class="d-block mt-1">
                                 <a href="#" class="btn btn-md btn-info">
                                 <em class="icon ni ni-download"></em>
                                 <span>Download</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </li>
            <li class="nk-schedule-item">
               <div class="nk-schedule-item-inner">
                  <div class="nk-schedule-symbol active"></div>
                  <div class="nk-schedule-content">
                     <span class="smaller">2:45 PM</span>
                     <div class="h6">Project status updated successfully</div>
                  </div>
               </div>
            </li>
         </ul>
      </div>
   </div>
   <!-- The core Firebase JS SDK is always required and must be listed first -->
   <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
   <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
   <!-- TODO: Add SDKs for Firebase products that you want to use
      https://firebase.google.com/docs/web/setup#available-libraries -->
   <script>
      // Your web app's Firebase configuration
      var firebaseConfig = {
          apiKey: "AIzaSyA5KDujI41Fjat2JjFdDyUahSRfVkx3Aro",
      authDomain: "sifututor-af80c.firebaseapp.com",
      projectId: "sifututor-af80c",
      storageBucket: "sifututor-af80c.appspot.com",
      messagingSenderId: "725388372527",
      appId: "1:725388372527:web:557e266c9441d6e37c74da",
      measurementId: "G-0X568JP99F"
      };
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      
      const messaging = firebase.messaging();
      
      function initFirebaseMessagingRegistration() {
          messaging.requestPermission().then(function () {
              return messaging.getToken()
          }).then(function(token) {
              
              axios.post("{{ route('fcmToken') }}",{
                  _method:"PATCH",
                  token
              }).then(({data})=>{
                  console.log(data)
              }).catch(({response:{data}})=>{
                  console.error(data)
              })
      
          }).catch(function (err) {
              console.log(`Token Error :: ${err}`);
          });
      }
      
      initFirebaseMessagingRegistration();
      
      messaging.onMessage(function({data:{body,title}}){
          new Notification(title, {body});
      });
   </script>
   
      <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
   <script src="{{ asset('template/assets/js/charts/analytics-chart.js') }}"></script>
   <script src="{{ asset('template/assets/js/data-tables/data-tables.js') }}"></script>
   <script src="https://sadaftraders.com/st/public/src/jquery.inputpicker.js"></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js'></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
   <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




<!--DATA TABLE WORK-->



 <!-- Include jQuery -->
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <!-- Include DataTables -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- Include DataTables Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

    <script>
        $(document).ready(function() {
    // Initialize the DataTable
    $('.table').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "initComplete": function() {
            $('.dtb-status').each(function() {
            var status = $(this).text().trim();
            if (status === 'paid') {
                $(this).addClass('dtb-paid');
            } else if (status === 'unpaid') {
                $(this).addClass('dtb-unpaid');
            }
        });
        
        $('.tstb-status').each(function() {
            var status = $(this).text().trim();
            if (status === 'Active') {
                $(this).addClass('dtb-paid');
            } else if (status === 'Inactive') {
                $(this).addClass('dtb-unpaid');
            }
        });
      
        $('.dtb-vstatus').each(function() {
            var vstatus = $(this).text().trim();
            if (vstatus === 'verified') {
                $(this).addClass('dtb-paid');
            } else if (vstatus === 'unverified') {
                $(this).addClass('dtb-unpaid');
            }
        });
        
        $('.dtb-astatus').each(function() {
            var astatus = $(this).text().trim();
            if (astatus === 'approved') {
                $(this).addClass('dtb-paid');
            } else if (astatus === 'unapproved') {
                $(this).addClass('dtb-unpaid');
            } else if (astatus === 'rejected') {
                $(this).addClass('dtb-nostat');
            } else if (astatus === 'pending') {
                $(this).addClass('dtb-pending');
            }
        });

        $('.dtb-tcstatus').each(function() {
            var tcstatus = $(this).text().trim();
            if (tcstatus === 'approved') {
                $(this).addClass('dtb-paid');
            } else if (tcstatus === 'pending') {
                $(this).addClass('dtb-pending');
            }
        });

        $('.dtb-apstatus').each(function() {
            var apstatus = $(this).text().trim();
            if (apstatus === 'completed') {
                $(this).addClass('dtb-paid');
            } else if (apstatus === 'incomplete') {
                $(this).addClass('dtb-unpaid');
            } else if (apstatus === 'no-application') {
                $(this).addClass('dtb-nostat');
            }
        });

        $('.dtb-vcsstatus').each(function() {
            var vcsstatus = $(this).text().trim();
            if (vcsstatus === 'scheduled') {
                $(this).addClass('dtb-nostat');
            } else if (vcsstatus === 'pending') {
                $(this).addClass('dtb-pending');
            } else if (vcsstatus === 'attended') {
                $(this).addClass('dtb-paid');
            }
        });
        }
    });
});

    </script>


<!--END DATA TABLE WORK-->





<script>

tinymce.init({
  selector: 'textarea#editor1, textarea#editor2',
  height: 500,
  plugins: [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
    'insertdatetime', 'media', 'table', 'help', 'wordcount'
  ],
  toolbar: 'undo redo | blocks | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
});


google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
    // Handle all inputs dynamically (address, streetAddress1, studentAddress)
    var input = document.getElementById('address') || 
                document.getElementById('streetAddress1') || 
                document.getElementById('studentAddress');

    if (input) {
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            
            // Extract postal code
            var postalCode = '';
            for (var i = 0; i < place.address_components.length; i++) {
                if (place.address_components[i].types.includes("postal_code")) {
                    postalCode = place.address_components[i].long_name;  // Get postal code
                    break;
                }
            }

            // Now you can use the postal code, for example:
            document.getElementById("postalcode").value = postalCode;
            
            // Extract latitude and longitude
            document.getElementById("customerLatitude").value = place.geometry.location.lat();
            document.getElementById("customerLongitude").value = place.geometry.location.lng();
    
            // Extract address components
            var addressComponents = place.address_components;
            var state = '';
            var city = ''; // Define city variable
    
            // Loop through the address components to get city and state
            for (var i = 0; i < addressComponents.length; i++) {
                if (addressComponents[i].types.includes("administrative_area_level_1")) {
                    state = addressComponents[i].long_name; // State name
                }
                if (addressComponents[i].types.includes("locality")) {
                    city = addressComponents[i].long_name; // City name
                }
            }

            // Select state and trigger city loading
            var stateDropdown = $('#customerState');
            var stateMatched = false;

            stateDropdown.find('option').each(function() {
                var optionText = $(this).text().toLowerCase().trim();
                var stateNameFromGoogle = state.toLowerCase().trim();

                if (optionText === stateNameFromGoogle) {
                    // Set the selected value programmatically
                    stateDropdown.val($(this).val()).trigger('change'); // Trigger change to load cities based on state
                    stateMatched = true;
                    return false; // Break the loop
                }
            });

            // Wait for cities to load, then select the city
            if (stateMatched) {
                loadCitiesForState(stateDropdown.val(), city); // Pass city to select once cities are loaded
            }
        });
    }
}

$(document).ready(function() {
    $("#customerCity, #customerState, #bankName, #tutors, #staffs").select2();
    
    $("select#customerState").change(function() {
        var customerState = $(this).val();
        loadCitiesForState(customerState, '');
    });
});

function loadCitiesForState(stateId, cityToSelect) {
    $("#customerCity").html('');

    if (stateId === "") {
        $('#customerCity').append('<option value="">Select City</option>');
    } else {
        $.ajax({
            url: "https://sifu.qurangeek.com/addTicketAjaxPOSTcustomerState",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                customerState: stateId
                // ,
                // _token: 'vVeRAc46nAtpEVnIz1IjbHDJVudrvQB7fyqvab8v'
            },
            type: "post",
            success: function(data) {
                $('#customerCity').append('<option value="">Select City</option>');
                $('#customerCity').append(data.cities);

                if (cityToSelect) {
                    selectCityFromGooglePlaces(cityToSelect);
                }
            }
        });
    }
}

// Function to auto-select the city from Google Places after the city dropdown is populated
function selectCityFromGooglePlaces(cityToSelect) {
    setTimeout(function() {
        var cityDropdown = $('#customerCity');

        // Loop through the options to match the city name
        cityDropdown.find('option').each(function() {
            var optionText = $(this).text().toLowerCase().trim();
            var cityNameFromGoogle = cityToSelect.toLowerCase().trim();

            if (optionText === cityNameFromGoogle) {
                // Set the selected value programmatically
                cityDropdown.val($(this).val()).trigger('change'); // Trigger change event
                return false; // Break the loop once the city is selected
            }
        });
    }, 500); // Adjust the delay time if needed
}

        $(document).ready(function () {
            var $sourceInput = $('#mobile_code');

            var $targetInput = $('#whatsapp_code');

            $sourceInput.on('input', function () {
                var inputValue = $sourceInput.val();

                $targetInput.val(inputValue);
            });


            var $getAgeInput = $('#age');
            var $getDateOfBirth = $('#dateOfBirth');
            
            $getAgeInput.on('input', function () {
                var ageInputValue = $getAgeInput.val();
                var age = parseInt(ageInputValue);
                if (!isNaN(age)) {
                    var currentDate = new Date();
                    var birthYear = currentDate.getFullYear() - age;
                    var dob = new Date(birthYear, currentDate.getMonth(), currentDate.getDate());
            
                    // Format the date as mm/dd/yy
                    var month = String(dob.getMonth() + 1).padStart(2, '0'); // Months are 0-based
                    var day = String(dob.getDate()).padStart(2, '0');
                    var year = String(dob.getFullYear()).slice(-2); // Last two digits of the year
                    var dobFormatted = `${month}/${day}/${year}`;
            
                    $('#dobResult').text(dobFormatted);
                    $getDateOfBirth.val(dobFormatted);
                }
            });
            
            $('#dateOfBirth').on('input', function () {
                var dob = $getDateOfBirth.val();
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                $getAgeInput.val(age);
            });

        });
   
$('#nric').mask('######-##-####', {
        placeholder: '000000-00-0000',
        translation: {
            '#': {
                pattern: /[0-9]/
                }
            }
    });
   
      /*CKEDITOR.replace('editor1', {
          height: 260,
          removeButtons: 'PasteFromWord',
        });
        CKEDITOR.replace('editor2', {
          height: 260,
          removeButtons: 'PasteFromWord',
        });*/
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
      })
      
      /*$('.datatable-init').DataTable( {
          
      });*/
      
      $('#mobile_code').keypress(function (e) {
          var txt = String.fromCharCode(e.which);
          if (!txt.match(/[0-9]/)) {
              return false;
          }
      });
      $('#whatsapp_codeTwo').keypress(function (e) {
          var txt = String.fromCharCode(e.which);
          if (!txt.match(/[0-9]/)) {
              return false;
          }
      });
      $('#whatsapp_code').keypress(function (e) {
          var txt = String.fromCharCode(e.which);
          if (!txt.match(/[0-9]/)) {
              return false;
          }
      });
      $('#mobile_codeTwo').keypress(function (e) {
          var txt = String.fromCharCode(e.which);
          if (!txt.match(/[0-9]/)) {
              return false;
          }
      });
   // -----Country Code Selection
      $("#mobile_code").intlTelInput({
        initialCountry: "my",
        separateDialCode: false,
        //utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
      });
       $("#whatsapp_code").intlTelInput({
        initialCountry: "my",
        separateDialCode: false,
        //utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
      });
      
       $("#mobile_codeTwo").intlTelInput({
        initialCountry: "my",
        separateDialCode: false,
        //utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
      });
       $("#whatsapp_codeTwo").intlTelInput({
        initialCountry: "my",
        separateDialCode: false,
        //utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
      });
      
      
        

              
              
              
   </script>
</html>
