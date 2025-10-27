<nav class="mainmenu-nav">
    <ul class="mainmenu">
        <li class="position-static">
            <a href="{{ route('home') }}">Home</a>
        </li>

        <li>
            {{-- <a href="{{ route('courses') }}">Browse Courses</a> --}}
            <a href="#">Browse Courses</a>
        </li>

        <li>
            {{-- <a href="{{ route('instructors') }}">Our Instructors</a> --}}
            <a href="#">Our Instructors</a>
        </li>

        {{-- <li class="has-dropdown has-menu-child-item">
            <a href="#">Dashboard
                <i class="feather-chevron-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Instructor</a>
                </li>
                <li><a href="{{ route('student.login-page') }}">Student</a>
                </li>
            </ul>
        </li> --}}

        <li class="has-dropdown has-menu-child-item">
            <a href="#">Pages <i class="feather-chevron-down"></i></a>
            <ul class="submenu">
                {{-- <li><a href="{{ route('about-us') }}">About Us</a></li>
                <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                <li><a href="{{ route('faqs') }}">FAQs</a></li>
                <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li> --}}
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">Privacy Policy</a></li>
                {{-- <li><a href="{{ route('cart') }}">Cart</a></li>
                <li><a href="{{ route('wishlist') }}">Wishlist</a></li> --}}
            </ul>
        </li>
    </ul>
</nav>
