<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Exam Gatepass System </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            background-color: #ffffff; 
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        .logo { 
            max-width: 150px; 
            width: 100%;
            height: auto;
            margin: 20px auto; 
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.02);
        }
        .login-link { 
            position: absolute; 
            top: 15px; 
            right: 15px; 
        }
        @media (max-width: 576px) {
            .login-link {
                top: 10px;
                right: 10px;
            }
        }
        .form-section { 
            margin-top: 30px;
            margin-bottom: 40px;
            padding: 0 15px;
        }
        .btn-login {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: #fff; 
            border: none; 
            border-radius: 25px;
            padding: 8px 20px; 
            transition: 0.3s;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,123,255,0.2);
        }
        .btn-login:hover {
            background: linear-gradient(90deg, #0056b3, #0099cc);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
        .form-control, .form-select {
            border-radius: 12px;
            border: 1.5px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
            outline: none;
        }
        @media (max-width: 768px) {
            .w-50 {
                width: 100% !important;
            }
            .form-section {
                margin-top: 20px;
            }
            h2 {
                font-size: 1.6rem;
            }
        }
        .mb-3 {
            margin-bottom: 1.2rem !important;
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 30px;
            padding: 12px 35px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(40,167,69,0.2);
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,167,69,0.3);
            background: linear-gradient(135deg, #218838, #1ba87b);
        }
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .form-wrapper {
            max-width: 550px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 25px 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }
        @media (max-width: 576px) {
            .form-wrapper {
                padding: 20px 15px;
            }
            .btn-success {
                padding: 10px 30px;
                width: 100%;
            }
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        .help-text {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 20px;
            text-align: center;
        }
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        #reg_number {
            font-family: monospace;
            letter-spacing: 0.3px;
            text-align: center;
        }
        button, .btn, select, input {
            touch-action: manipulation;
        }
        .step-card {
            border-left: 4px solid #dee2e6;
            transition: all 0.2s;
        }
        .step-active {
            border-left-color: #007bff;
            background-color: #f8f9ff;
        }
        .step-completed {
            border-left-color: #28a745;
        }
        .upload-preview {
    width: 100px;           
    height: 100px;
    object-fit: contain;    
    border-radius: 50%;
    border: 2px solid #28a745;
    background-color: #f0f0f0;  
}
    </style>
</head>
<body>

<div class="login-link">
    <a href="/login" class="btn btn-login">
        <i class="fas fa-sign-in-alt me-1"></i> Login
    </a>
</div>

<div class="text-center">
    <img src="/images/logo.png" alt="Institution Logo" class="logo">
    <h2 class="mt-2">Exam Gatepass System</h2>
    <p style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">
    
    </p>
</div>

<div class="container form-section">
    <div class="form-wrapper">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

      
        <div id="step-check" class="step-card p-3 mb-4 rounded">
            <h6></i> Enter Registration Number</h6>
            <div class="input-group mb-2">
                <input type="text" id="check_reg" class="form-control" placeholder="e.g. IRDP-LZC/BTCCD/SE25/0005">
                <button type="button" id="checkEligibilityBtn" class="btn btn-primary">Check</button>
            </div>
            <div id="eligibility-result" class="small mt-2"></div>
        </div>

       
        <div id="step-upload" class="step-card p-3 mb-4 rounded" style="display: none;">
            <h6></i> Step 1: Upload Passport Photo</h6>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reg_number" id="upload_reg">
                <div class="mb-2">
                    <input type="file" name="passport" id="passportInput" accept="image/*" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-sm btn-info" id="uploadBtn">Upload Passport</button>
                <div class="progress mt-2 d-none">
                    <div class="progress-bar" style="width:0%"></div>
                </div>
                <div id="uploadMsg" class="small mt-1"></div>
            </form>
            <div id="passport-preview" class="mt-2 text-center" style="display: none;">
                <img id="preview-img" class="upload-preview">
                <p class="text-success mt-1"><i class="fas fa-check-circle"></i> Uploaded successfully</p>
            </div>
        </div>

       
        <div id="step-generate" class="step-card p-3 rounded" style="display: none;">
            <h6></i> Step 2: Select Exam Type & Generate Gatepass</h6>
            <form action="{{ route('gatepass.store') }}" method="POST" id="gatepassForm">
                @csrf
                <input type="hidden" name="reg_number" id="generate_reg">
                <div class="mb-3">
                    <label for="exam_type" class="form-label"></i> Exam Type</label>
                    <select id="exam_type" name="exam_type" class="form-select" required>
                        <option value="">-- Choose Exam Type --</option>
                        <option value="SUPP"> Supp</option>
                        <option value="SPECIAL"> Special</option>
                        <option value="END OF SEMESTER I"> End of Semester I</option>
                        <option value="END OF SEMESTER II"> End of Semester II</option>
                        <option value="TEST I"> Test I</option>
                        <option value="TEST II"> Test II</option>
                        <option value="RM"> RM</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-download"></i> Generate Gatepass
                    </button>
                </div>
            </form>
        </div>

        <div class="help-text">
           
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentRegNumber = null;
    let isEligible = false;
    let hasPassport = false;


    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
    }, 3500);

   
    document.getElementById('checkEligibilityBtn').addEventListener('click', async function() {
        let reg = document.getElementById('check_reg').value.trim();
        if (!reg) {
            document.getElementById('eligibility-result').innerHTML = '<span class="text-danger">Please enter registration number.</span>';
            return;
        }
        document.getElementById('eligibility-result').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        try {
            let response = await fetch('{{ route("gatepass.form") }}?reg_number=' + encodeURIComponent(reg));
            let html = await response.text();
            
            let eligibilityResponse = await fetch('/check-eligibility?reg_number=' + encodeURIComponent(reg));
            let data = await eligibilityResponse.json();
            if (data.error) {
                document.getElementById('eligibility-result').innerHTML = '<span class="text-danger">' + data.error + '</span>';
                document.getElementById('step-upload').style.display = 'none';
                isEligible = false;
                return;
            }
            if (data.eligible) {
                document.getElementById('eligibility-result').innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Eligible! ADA: Paid, NHIF: Paid.</span>';
                isEligible = true;
                currentRegNumber = reg;
                document.getElementById('upload_reg').value = reg;
                document.getElementById('step-upload').style.display = 'block';
                
                if (data.has_passport) {
                    hasPassport = true;
                    document.getElementById('uploadForm').style.display = 'none';
                    document.getElementById('passport-preview').style.display = 'block';
                    document.getElementById('preview-img').src = '/storage/passports/' + data.passport_photo;
                    showGenerateStep();
                } else {
                    document.getElementById('uploadForm').style.display = 'block';
                    document.getElementById('passport-preview').style.display = 'none';
                }
            } else {
                document.getElementById('eligibility-result').innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Not eligible. ADA: ' + (data.ada ? 'Paid' : 'Pending') + ', NHIF: ' + (data.nhif ? 'Paid' : 'Pending') + '</span>';
                isEligible = false;
                document.getElementById('step-upload').style.display = 'none';
            }
        } catch (err) {
            document.getElementById('eligibility-result').innerHTML = '<span class="text-danger">Error checking eligibility. Try again.</span>';
        }
    });

   
    document.getElementById('uploadForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let progressDiv = this.querySelector('.progress');
        let progressBar = progressDiv.querySelector('.progress-bar');
        let msgDiv = document.getElementById('uploadMsg');
        
        progressDiv.classList.remove('d-none');
        let interval = setInterval(() => {
            let w = parseInt(progressBar.style.width);
            if(w < 90) progressBar.style.width = (w + 10) + '%';
        }, 200);
        
        try {
            let response = await fetch('{{ route("gatepass.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            let result = await response.json();
            clearInterval(interval);
            progressBar.style.width = '100%';
            if(result.success) {
                msgDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Upload successful!</span>';
                hasPassport = true;
                document.getElementById('uploadForm').style.display = 'none';
                document.getElementById('passport-preview').style.display = 'block';
                document.getElementById('preview-img').src = result.photo_url;
                showGenerateStep();
            } else {
                msgDiv.innerHTML = '<span class="text-danger">' + result.message + '</span>';
                progressDiv.classList.add('d-none');
            }
        } catch(err) {
            clearInterval(interval);
            msgDiv.innerHTML = '<span class="text-danger">Upload failed. Please try again.</span>';
            progressDiv.classList.add('d-none');
        }
    });

    function showGenerateStep() {
        document.getElementById('step-generate').style.display = 'block';
        document.getElementById('generate_reg').value = currentRegNumber;
       
        document.getElementById('step-generate').scrollIntoView({ behavior: 'smooth' });
    }

  
    const gatepassForm = document.getElementById('gatepassForm');
    const submitBtn = document.getElementById('submitBtn');
    if (gatepassForm) {
        gatepassForm.addEventListener('submit', function() {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
            }
        });
    }

   
    if ('ontouchstart' in window) {
        document.querySelectorAll('button, .btn').forEach(btn => {
            btn.style.cursor = 'pointer';
        });
    }
</script>


</body>
</html>