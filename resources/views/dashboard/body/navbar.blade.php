<div class="iq-top-navbar" style="background-color: #F3C623; width:100%">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div id="datetime" class="ml-auto text-muted small font-weight-bold"></div>
            {{-- <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="{{ route('dashboard') }}" class="header-logo">
                    <img src="../assets/images/logo.png" class="img-fluid rounded-normal" alt="logo">
                    <h5 class="logo-title ml-3">POS</h5>
                </a>
            </div> --}}
            {{-- <div class="iq-search-bar device-search">
                <form action="#" class="searchbox">
                    <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                    <input type="text" class="text search-input" placeholder="Search here...">
                </form>
            </div> --}}
            <div></div>
            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">
                        {{-- <li class="nav-item nav-icon search-content">
                            <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </a>
                            <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                                <form action="#" class="searchbox p-2">
                                    <div class="form-group mb-0 position-relative">
                                        <input type="text" class="text search-input font-size-12"
                                            placeholder="type here to search...">
                                        <a href="#" class="search-link"><i class="las la-search"></i></a>
                                    </div>
                                </form>
                            </div>
                        </li> --}}
                        <li class="nav-item nav-icon dropdown caption-content mt-2">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ auth()->user()->photo ? asset('storage/profile/'.auth()->user()->photo) : asset('assets/images/user/1.png') }}"
                                    class="img-fluid rounded" alt="user">
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                            <img src="{{ asset('assets/images/page-img/profile-bg.jpg') }}"
                                                alt="profile-bg" class="rounded-top img-fluid mb-4">
                                            <img src="{{ auth()->user()->photo ? asset('storage/profile/'.auth()->user()->photo) : asset('assets/images/user/1.png') }}"
                                                alt="profile-img" class="rounded profile-img img-fluid avatar-70">
                                        </div>
                                        <div class="p-3 text-center">
                                            {{-- <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                            <p class="mb-0">Since {{ date('d M, Y',
                                                strtotime(auth()->user()->created_at)) }}</p> --}}

                                            <div class="p-3 text-center">
                                                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                                <p class="mb-0">Akun dibuat: {{
                                                    \Carbon\Carbon::parse(auth()->user()->created_at)->translatedFormat('d
                                                    F Y') }}</p>


                                                <div class="d-flex justify-content-between mt-3 gap-2">
                                                    <a href="{{ route('profile') }}"
                                                        class="btn btn-outline-primary mr-3 d-flex align-items-center justify-content-center flex-fill">
                                                        <i class="fas fa-user me-2"></i> Profile
                                                    </a>
                                                    <form action="{{ route('logout') }}" method="POST"
                                                        class="flex-fill m-0">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-outline-danger d-flex align-items-center justify-content-center w-100">
                                                            <i class="fas fa-sign-out-alt me-2"></i> Sign Out
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>