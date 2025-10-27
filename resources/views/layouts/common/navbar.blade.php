@if (isset(app('admin')->id))
    @include('layouts.admin.sidebar')
@elseif (isset(app('student')->id))
    @include('layouts.student.sidebar')
@elseif (isset(app('teacher')->id))
    @include('layouts.teacher.sidebar')
@endif

