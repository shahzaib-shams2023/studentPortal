@extends("web.admin.adminlayout")
@section("dashboardcontent")
<div class="container-fluid">
            <div class="row">
               <div class="main-header">
                  <h4>Dashboard</h4>
               </div>
            </div>
            <!-- 4-blocks row start -->
            <div class="row dashboard-header">
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Career Course</span>
                     <h2 class="dashboard-total-products">{{$webcareercoursecount}}</h2>
                    
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Short Course</span>
                     <h2 class="dashboard-total-products">{{$webshortcoursemodelcount}}</h2>
                    
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Carousel</span>
                     <h2 class="dashboard-total-products">{{$webcarouselmodelcount}}</h2>
                    
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Placements</span>
                     <h2 class="dashboard-total-products">{{$webplacementsmodelcount}}</h2>
                    
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Connects</span>
                     <h2 class="dashboard-total-products">{{$webconnectmodelcount}}</h2>
                    
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Counters</span>
                     <h2 class="dashboard-total-products">{{$webcountermodelcount}}</h2>
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Winner Circle</span>
                     <h2 class="dashboard-total-products">{{$webwinnercirclemodelcount}}</h2>
                  </div>
               </div>
               <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Projects of Month</span>
                     <h2 class="dashboard-total-products">{{$webprojectofmonthmodelcount}}</h2>
                    
                  </div>
               </div>
                <div class="col-lg-2 col-md-6">
                  <div class="card dashboard-product" style="text-align: center;">
                     <span>Student of Month</span>
                     <h2 class="dashboard-total-products">{{$webstudentofmonthmodecount}}</h2>
                    
                  </div>
               </div>
            

            </div>
            <!-- 4-blocks row end -->

           
            <!-- 2-1 block end -->
         </div>

@endsection