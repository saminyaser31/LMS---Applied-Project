{{-- <form method="GET" action="{{ route('courses') }}"> --}}
<form method="GET" action="#">
    <div class="rbt-search-with-category">
        <div class="filter-select rbt-modern-select search-by-category">
            <select name="course_categories[]">
                <option value="">All Categories</option>
                {{-- @foreach(App\Helper\Helper::getAllCourseCategory() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach --}}
            </select>
        </div>

        <div class="search-field">
            <input type="text" placeholder="Search Course">
            <button class="rbt-round-btn serach-btn" type="submit">
                <i class="feather-search"></i>
            </button>
        </div>
    </div>
</form>
