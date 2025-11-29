@extends("web.head_foot")
@section("title")
Aptech Garden Center | Events
@endsection
@section("section")
<div class="page-content bg-white">
        <!-- inner page banner -->
        <div class="page-banner ovbl-dark" style="background-image:url(/aptech_web/assets/images/banner/banner2.jpg);">
            <div class="container">
                <div class="page-banner-entry">
                    <h1 class="text-white">Events</h1>
				 </div>
            </div>
        </div>
		<!-- Breadcrumb row -->
		<div class="breadcrumb-row">
			<div class="container">
				<ul class="list-inline">
					<li><a href="#">Home</a></li>
					<li>Events</li>
				</ul>
			</div>
		</div>
		<!-- Breadcrumb row END -->
        <!-- contact area -->
        <div class="content-block">
			<!-- Portfolio  -->
			<div class="section-area section-sp1 gallery-bx">
				<div class="container">
					<div class="feature-filters clearfix center m-b40">
					<form action="" method="post">
						<div class="row">
							<div class="col-md-6">

							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-8">
										<input type="text" name="" placeholder="Search here.." id="" class="form-control">
									</div>
									<div class="col-md-4">
										<div class="text-center">
											<a href="#" class="btn" style="position: relative; right: 50px;">Search</a>
										</div>
									</div>
								</div>
							
							</div>
						</div>
					
					</form>
					</div>
					<div class="clearfix">
						<ul id="masonry" class="ttr-gallery-listing magnific-image row">
						@foreach($event as $e)
							<li class="action-card col-lg-6 col-md-6 col-sm-12 happening">
								<div class="event-bx m-b30">
									<div class="action-box">
										<img src="/images/eventimage/{{$e->image}}" alt="">
									</div>
									<div class="info-bx d-flex">
										<div>
											<div class="event-time">
												<div class="event-date">{{$e->date}}</div>
												<div class="event-month">{{$e->month}}</div>
											</div>
										</div>
										<div class="event-info">
											<h4 class="event-title"><a href="#">{{$e->title}}</a></h4>
											<ul class="media-post">
												<li><a href="#"><i class="fa fa-clock-o"></i>{{$e->timing}}</a></li>
											</ul>
											<p>{{$e->description}}</p>
										</div>
									</div>
								</div>
							</li>
							@endforeach
					
						</ul>
					</div>
				</div>
			</div>
        </div>
		<!-- contact area END -->
    </div>
@endsection