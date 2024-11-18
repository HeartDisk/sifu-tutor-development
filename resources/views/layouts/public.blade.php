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
    
   
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css'>
    <script src="https://sadaftraders.com/st/public/src/jquery.inputpicker.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    

        <style>
        
        @font-face {
              font-family: 'CircularStdBlack' !important;
          src: url('{{ asset('font/CircularStd-Black.ttf') }}') format('truetype');

          font-weight: normal;
              font-style: normal;
            }
            
            body {
              font-family: 'CircularStdBlack', Arial, sans-serif !important; /* Use the custom font for the body or specific elements */
            }
        .table-dark{
            background-color:#2e314a !important;
            color:#fff !imporant;
        }
        
        input[type=date], input[type=date]:focus, input[type=datetime-local], input[type=datetime-local]:focus, input[type=email], input[type=email]:focus, input[type=number], input[type=number]:focus, input[type=password], input[type=password]:focus, input[type=search-md], input[type=search-md]:focus, input[type=search], input[type=search]:focus, input[type=tel], input[type=tel]:focus, input[type=text], input[type=text]:focus, input[type=time], input[type=time]:focus, input[type=url], input[type=url]:focus, textarea.md-textarea, textarea.md-textarea:focus{
                border: 1px solid #d2d2d2 !important;
                box-shadow: 0 1px 0 0 #d2d2d2 !important;
        }
        .dataTable-table{
            font-size:1rem !important;
        }
        
         .overline-title{
                font-size:14px;
                color:#fff;
            }
            .td{
                /* font-size:19px !important; */
                color:#fff;
            }
            
            .table>tbody>tr:nth-child(odd){
                  /* background-color:#fff !important; */
                  color:#000;
            } 
            
            .table>tbody>tr:hover {
                background-color: #f0f0f0; /* Change this to your desired hover color */
            }

            .table>tbody>tr:nth-child(even){
              background-color:rgba(0,0,0,.05) !important;  
              color:#000;
            }
            
            .table>thead>tr>td{
                  font-size:15px;
                  font-weight:bold;
                  color:#000;
            }
            
            .nk-content{
                background-color:#f9f9f9 !important;
            }
            .card{
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                overflow:hidden;10px;
                padding:10px !important;
            }
            .form-control:not([type=file]):read-only{
                background-color:#fff !important;
            }
            .nk-menu-text{
                font-size:17px !important;
            }
             .form-control:not([type=file]):read-only{
        background-color:#fff !important;
        
    }
    .existingCustomer, .existingStudent {
        font-size:21px; 
        font-weight:bold; 
        
    }
    .form-label{
        font-size:17px !important;
        color:#000;
        
    }
    .form-control{
        color:#000 !important;
    }
    
    .choices__item--selectable{
        font-size:1.3rem !important;
        color:#000 !important;
    }
        </style>
</head>
<body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div id="app">
        <div class="nk-app-root">
            <div class="nk-main"> 
            
        <div class="nk-wrap">
          <div style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" class="nk-header nk-header-fixed">
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
                  <!--<ul class="nav nav-sm">-->
                  <!--  <li class="nav-item">-->
                  <!--    <a href="#" class="nav-link">About</a>-->
                  <!--  </li>-->
                  <!--  <li class="nav-item">-->
                  <!--    <a href="#" class="nav-link">Support</a>-->
                  <!--  </li>-->
                  <!--  <li class="nav-item">-->
                  <!--    <a href="#" class="nav-link">Blog</a>-->
                  <!--  </li>-->
                  <!--</ul>-->
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


   

  <script src="{{ asset('template/assets/js/charts/analytics-chart.js') }}"></script>
  <script src="{{ asset('template/assets/js/data-tables/data-tables.js') }}"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js'></script>
    <script>
    
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
