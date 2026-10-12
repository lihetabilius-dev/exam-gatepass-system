<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students - Exam Gatepass System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Students Management</h2>

    
    <form method="GET" action="{{ route('admin.students') }}" class="row mb-3">
        <div class="col-md-2">
            <select name="per_page" class="form-select" onchange="this.form.submit()">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>Show 5</option>
                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>Show 15</option>
                <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>Show 40</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or reg number">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    
    <form method="POST" action="{{ route('admin.deleteSelectedStudents') }}">
        @csrf
        @method('DELETE')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Reg Number</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td><input type="checkbox" name="student_ids[]" value="{{ $student->id }}"></td>
                    <td>{{ $student->reg_number }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->course }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.deleteStudent', $student->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-danger mt-2">Delete Selected</button>
    </form>

  
    <div class="mt-3">
        {{ $students->appends(['per_page' => request('per_page'), 'search' => request('search')])->links() }}
    </div>
</div>

<script>
  
    document.getElementById('select-all').addEventListener('click', function(e) {
        const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });
</script>
</body>
</html>
