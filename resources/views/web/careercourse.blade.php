@extends("web.head_foot")
@section("title")
Aptech Garden Center | Career Courses
@endsection
@section("section")
<div class="page-content bg-white">
        <!-- inner page banner -->
        <div class="page-banner ovbl-dark" style="background-image:url(/aptech_web/assets/images/banner/banner3.jpg);">
            <div class="container">
                <div class="page-banner-entry">
                    <h1 class="text-white">Our Courses</h1>
				 </div>
            </div>
        </div>
		<!-- Breadcrumb row -->
		<div class="breadcrumb-row">
			<div class="container">
				<ul class="list-inline">
					<li><a href="#">Home</a></li>
					<li>Our Courses</li>
				</ul>
			</div>
		</div>
		<!-- Breadcrumb row END -->
        <!-- inner page banner END -->
		<div class="content-block">
            <!-- About Us -->
			<div class="section-area section-sp1">
                <div class="container">
					 <div class="row">
						<div class="col-lg-3 col-md-4 col-sm-12 m-b30">
							<div class="widget courses-search-bx placeani">
								
							</div>
							<div class="widget widget_archive">
                                <h5 class="widget-title style-1">All Courses</h5>
                                <ul>
                                    <li>Website Designing</li>
                                    <li>Laravel Web Development</li>
                                    <li>.NET Core Web Development</li>
                                    <li>Flutter App Development</li>
									<li>Mern Stack Development</li>
									<li>Digital Marketing</li>
									<li>Office Automation</li>


                                </ul>
                            </div>
						
						</div>
						<div class="col-lg-9 col-md-8 col-sm-12">
							<div class="row">
							@foreach($careercourse as $c)

								<div class="col-md-6 col-lg-4 col-sm-6 m-b30">
									<div class="cours-bx">
										<div class="action-box">
											<img src="/images/careercourses/{{$c->image}}" alt="">
											<a href="#" data-toggle="modal" data-target="#careercoursemodaldetail" class="btn">Read More</a>
										</div>
										<div class="info-bx text-center">
											<h5><a href="#">{{$c->semester}}</a></h5>		
											<span><b>{{$c->coursename}}</b></span>
											<br>
											<br>
											<span>{{$c->description}}</span>
											<div class="progress">
												<div class="progress-bar progress-bar-warning" role="progressbar"  aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:{{$c->completition}}">
												</div>
											  </div>
										</div>
										<div class="cours-more-info">
											<div class="review">
												<span style="font-weight: bold;">6 Month</span>
												<br>
												<span style="font-size: 11px;">{{$c->courseduration}}</span>
											</div>
											<div class="price">
												<span style="font-size: 11px;">Class Duration</span>
												<br>
												<span style="font-weight: bold; font-size: 12px;">{{$c->classduration}}</span>
											</div>
										</div>
									</div>
								</div>
							
								@endforeach

							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
		<!-- contact area END -->
		
    </div>
@endsection