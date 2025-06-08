@include('admin.layouts.partials.header')
<!-- Page Header Ends-->

<!-- Page Body Start-->
<div class="page-body-wrapper">
    <!-- Page Sidebar Start-->
    @include('admin.layouts.partials.sidebar')
    <!-- Page Sidebar Ends-->

    <!-- index body start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12"></div>

                @yield('content')
            
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- footer start-->
    @include('admin.layouts.partials.footer')
    <!-- footer End-->