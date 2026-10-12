<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Exam Gatepass System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .sidebar {
            width: 220px; background-color: #343a40; color: #fff;
            min-height: 100vh; padding: 20px 10px; position: fixed;
            top: 56px; bottom: 0;
        }
        .sidebar h4 { text-align: center; margin-bottom: 30px; }
        .sidebar a {
            display: block; color: #fff; padding: 10px; margin-bottom: 10px;
            border-radius: 6px; text-decoration: none; transition: 0.3s;
        }
        .sidebar a:hover { background-color: #495057; }
        .content { margin-left: 240px; padding: 40px; }
        .dashboard-card {
            margin-bottom: 30px; padding: 20px;
            border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Admin Panel</a>
        <div class="d-flex ms-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>


<div class="sidebar">
    <h4>Admin Menu</h4>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.users') }}">Manage Users</a>
    <a href="{{ route('admin.students') }}">Manage Students</a>
</div>


<div class="content">
    <h2 class="mb-4">Welcome, Admin</h2>

    
    <div class="dashboard-card">
        <h5>Users Management</h5>

        
        <form method="POST" action="{{ route('admin.addUser') }}" class="mb-3">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="dean">Dean</option>
                        <option value="accounts">Accounts</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Add</button>
                </div>
            </div>
        </form>

       
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <!-- Block User -->
                        <form method="POST" action="{{ route('admin.blockUser', $user->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Block</button>
                        </form>

                        <!-- Delete User -->
                        <form method="POST" action="{{ route('admin.deleteUser', $user->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   
    <div class="dashboard-card">
        <h5>Students Management</h5>

        
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td><input type="checkbox" name="student_ids[]" value="{{ $student->id }}"></td>
                        <td>{{ $student->reg_number }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->course }}</td>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
   
    document.getElementById('select-all').addEventListener('click', function(e) {
        const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });
</script>
</body>
</html>
