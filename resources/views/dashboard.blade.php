<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Exam Gatepass System</title>
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
            max-width: 700px; margin: auto; padding: 30px;
            border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #fff; margin-bottom: 30px;
        }
        .btn-upload {
            background: linear-gradient(90deg, #28a745, #5cd65c);
            color: #fff; border: none; border-radius: 8px;
            padding: 12px; font-weight: 500; transition: 0.3s;
        }
        .btn-upload:hover {
            background: linear-gradient(90deg, #218838, #45b649);
            color: #fff;
        }
    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Exam Gatepass</a>
        <div class="d-flex ms-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>


<div class="sidebar">
    <h4>Menu</h4>
    <a href="{{ route('dashboard') }}">Dashboard</a>
   
</div>


<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    @if(Auth::user()->role === 'dean')
        <div class="dashboard-card">
            <h5 class="mb-3"><i class="bi bi-file-earmark-spreadsheet"></i> Upload Dean Data (CSV)</h5>
            <form method="POST" action="{{ route('upload.dean') }}" enctype="multipart/form-data" class="border p-4 rounded">
                @csrf
                <div class="mb-3">
                    <label for="dean_csv" class="form-label">Choose Dean CSV File</label>
                    <input type="file" id="dean_csv" name="csv_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-upload w-100">Upload Dean CSV</button>
            </form>
        </div>
    @endif

   
    @if(Auth::user()->role === 'accounts')
        <div class="dashboard-card">
            <h5 class="mb-3"><i class="bi bi-file-earmark-spreadsheet"></i> Upload Accountant Data (CSV)</h5>
            <form method="POST" action="{{ route('upload.accountant') }}" enctype="multipart/form-data" class="border p-4 rounded">
                @csrf
                <div class="mb-3">
                    <label for="accountant_csv" class="form-label">Choose Accountant CSV File</label>
                    <input type="file" id="accountant_csv" name="csv_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-upload w-100">Upload Accountant CSV</button>
            </form>
        </div>
    @endif

   
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
