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
                        Add Subject
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Subjects List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
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
                        <form method="POST" action="{{route('submitProduct')}}" enctype="multipart/form-data">
                           @csrf
                           <div class="row g-3">
                               <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="product_image" class="form-label">Product Image</label>
                                        <div class="form-control-wrap">
                                            <input type="file" class="form-control" name="product_image" id="product_image" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Subject ID</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" readonly name="productID" value="@php $ThreeDigitRandomNumber = rand(100,999);echo 'SBJ-'.$ThreeDigitRandomNumber; @endphp" id="tutorID"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Subject Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="product_name" id="product_name" required></div>
                                 </div>
                              </div>
                              <div class="col-lg-3" style="display:none;">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Service</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="services" data-search="true" name="brand" data-sort="false">
                                          <option selected value="sifututor" selected="selected">SifuTutor</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Level</label>
                                    <div class="form-control-wrap">
                                       <select class="form-control js-select" id="category" data-search="true" name="category" data-sort="false" required>
                                          @foreach($categories as $categoryRow)
                                          <option value="{{$categoryRow->id}}"> {{$categoryRow->category_name}} -  {{$categoryRow->mode}} </option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-2"><button class="btn btn-primary mt-4" type="submit">Submit</button></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
              
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
function myFunction() {
  var checkBox = document.getElementById("is_tution_service");
  var text = document.getElementById("text");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
    text.style.display = "none";
  }
}

$(document).ready(function(){
    $("select#category").change(function(){
        var categoryID = $(this).children("option:selected").val();        
        $.ajax({
            url: "{{ url('/selectCategoryPriceByAjax/') }}"+"/"+categoryID,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $('#price').val(data.categoryPrice);
            }
        });
    });
});
</script>
@endsection