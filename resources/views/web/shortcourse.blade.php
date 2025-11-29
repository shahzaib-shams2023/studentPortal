@extends("web.head_foot")
@section("title")
Aptech Garden Center | Short Courses
@endsection
@section("section")
<div class="page-content bg-white">
        <!-- inner page banner -->
        <div class="page-banner ovbl-dark" style="background-image:url(/aptech_web/assets/images/banner/banner3.jpg);">
            <div class="container">
                <div class="page-banner-entry">
                    <h1 class="text-white">Our Short Courses</h1>
				 </div>
            </div>
        </div>
		<!-- Breadcrumb row -->
		<div class="breadcrumb-row">
			<div class="container">
				<ul class="list-inline">
					<li><a href="#">Home</a></li>
					<li>Our Short Courses</li>
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
                                    <li>Office Automation</li>
                                    <li>Building Modernistic Website</li>
                                    <li>.NET Core Web Development</li>
                                    <li>PHP & Laravel Development</li>
									<li>Python Programming</li>
									<li>Digital Marketing</li>
									<li>Android App Development</li>
                                </ul>
                            </div>
						</div>
						<div class="col-lg-9 col-md-8 col-sm-12">
							<div class="row">
							@foreach($shortcourse as $s)
								<div class="col-md-6 col-lg-4 col-sm-6 m-b30">
									<div class="cours-bx">
										<div class="action-box">
											<img src="images/shortcourses/{{$s->image}}" alt="">
											<a href="#" data-toggle="modal" data-target="#shortcoursedetailmodal" class="btn">Read More</a>
										</div>
										<div class="info-bx text-center">
											<h5><a href="#">{{$s->coursename}}
					
											</a></h5>
											<br>
					
											<span>{{$s->description}}</span>
										</div>
										<div class="cours-more-info">
											<div class="review">
												<span style="font-weight: bold;">{{$s->courseduration}}</span>
												<br>
												<span style="font-size: 11px;">Course Duration</span>
											</div>
											<div class="price">
												<span style="font-weight: bold; font-size: 12px;">Class Duration</span>
												<br>
												<span style="font-size: 11px;">Class Duration</span>
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