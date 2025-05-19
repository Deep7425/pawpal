<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
    <a href="index.html" class="navbar-brand">
        <h1 class="m-0 text-primary"><i class="fa fa-paw me-3"></i>Pawpal</h1>
    </a>
    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto">
            <a href="{{route('index')}}" class="nav-item nav-link active">Home</a>
            <a href="{{route('aboutUs')}}" class="nav-item nav-link">About Us</a>
            <a href="{{route('Mission')}}" class="nav-item nav-link">Mission</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Programs</a>
                <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                    <a href="#" class="dropdown-item">Animal Welfare Foundation</a>
                    <a href="#" class="dropdown-item">Animal Healthcare</a>
                    <a href="#" class="dropdown-item">Bird Foundation</a>
                    <a href="#" class="dropdown-item">Success Stories</a>
                </div>
            </div>
            <a href="{{route('contactUs')}}" class="nav-item nav-link">Contact Us</a>
        </div>
        <a href="#" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Register Now<i class="fa fa-heart ms-3"></i></a>

        <a href="#" style="margin-left:2px;" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Sign In</a>
    </div>
</nav>