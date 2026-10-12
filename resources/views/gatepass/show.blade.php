<!DOCTYPE html>
<html>
<head>
    <title>Exam Gatepass</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            position: relative;
        }
        body::before {
            content: "IRDP - Official Gatepass";
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            color: rgba(0,0,0,0.05);
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
        }
        .gatepass {
            border: 3px double #2c3e50;
            padding: 25px;
            width: 700px;
            margin: auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        
        .passport-container {
            float: right;
            margin-left: 20px;
            margin-bottom: 15px;
            text-align: center;
            width: 140px;
        }
        .passport-thumb {
            width: 120px;
            height: 150px;
            object-fit: contain;
            border: 2px solid #2c3e50;
            border-radius: 6px;
            background: #fff;
            padding: 4px;
        }
        .passport-label {
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
            color: #2c3e50;
        }
        .details {
            margin-bottom: 20px;
            font-size: 15px;
            overflow: auto;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 8px 12px;
            border-bottom: 1px solid #ccc;
        }
        .details td:first-child {
            font-weight: bold;
            width: 40%;
            color: #2c3e50;
        }
        .qr {
            text-align: center;
            margin-top: 25px;
            clear: both;
        }
        .qr p {
            font-size: 13px;
            color: #555;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #2c3e50;
            color: #fff;
            font-weight: bold;
        }
        button:hover {
            background-color: #34495e;
        }
        .footer-note {
            text-align: center;
            font-size: 12px;
            margin-top: 15px;
            color: #666;
        }
       
.signatures table {
    width: 100%;
    text-align: center;
    margin-top: 40px;
}
.signatures td {
    vertical-align: top;
    padding: 10px;
}
.signatures img {
    height: 60px;
    display: block;
    margin: 0 auto 5px; 
}
.signatures hr {
    border: 1px solid #555;
    width: 80%;
    margin: 5px auto; 
}
.signatures p {
    margin-top: 5px;
    font-weight: bold; 
}



    </style>
</head>
<body>
    <div class="gatepass">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Institution Logo" style="max-width:120px; margin-bottom:10px;">
            <h2>Exam Gatepass</h2>
            <p><strong>Institution:</strong> IRDP-LZC</p>
        </div>

      
        <div class="passport-container">
            @php
                $photoPath = !empty($student->passport_photo) ? storage_path('app/public/passports/' . $student->passport_photo) : null;
                $hasPhoto = $photoPath && file_exists($photoPath);
                $mimeType = $hasPhoto ? mime_content_type($photoPath) : 'image/jpeg';
                $base64Image = $hasPhoto ? base64_encode(file_get_contents($photoPath)) : null;
            @endphp
            @if($hasPhoto)
                <img src="data:{{ $mimeType }};base64,{{ $base64Image }}" 
                     class="passport-thumb" 
                     alt="Passport Photo">
                <div class="passport-label">Passport Photo</div>
            @else
                <div class="passport-thumb" style="background:#ddd; display:flex; align-items:center; justify-content:center;">
                    <span style="font-size:10px;">No Photo</span>
                </div>
                <div class="passport-label">Passport Photo</div>
            @endif
        </div>

        <div class="details">
            <table>
                <tr><td>Reg Number:</td><td>{{ $student->reg_number }}</td></tr>
                <tr><td>Full Name:</td><td>{{ $student->full_name }}</td></tr>
                <tr><td>Exam Type:</td><td>{{ $gatepass->exam_type }}</td></tr>
                <tr><td>Payment Status (BIMA):</td><td>Successful Paid</td></tr>
                <tr><td>Amount Paid (Ada):</td><td>{{ number_format(optional($student->eligibility)->amount_paid ?? 0, 0, '.', ',') }}</td></tr>
                @if($gatepass->issued_at)
                <tr><td>Issued At:</td><td>{{ \Carbon\Carbon::parse($gatepass->issued_at)->format('d M Y H:i') }}</td></tr>
                @endif
                @if($gatepass->expires_at ?? false)
                <tr><td>Valid Until:</td><td>{{ \Carbon\Carbon::parse($gatepass->expires_at)->format('d M Y H:i') }}</td></tr>
                @endif
            </table>
        </div>

        <div class="qr">
            {!! $qrCode !!}
            <p>Scan QR Code to view student details</p>
        </div>

    
<div class="signatures">
    <table>
        <tr>
            <td>
                @if(app()->environment('local'))
                    <img src="{{ asset('signatures/exam_officer.png') }}" alt="Examination Officer Signature">
                @else
                    <img src="{{ public_path('signatures/exam_officer.png') }}" alt="Examination Officer Signature">
                @endif
                <hr>
                <p>Examination Officer</p>
            </td>
            <td>
                @if(app()->environment('local'))
                    <img src="{{ asset('signatures/accountant_officer.png') }}" alt="Accountant Officer Signature">
                @else
                    <img src="{{ public_path('signatures/accountant_officer.png') }}" alt="Accountant Officer Signature">
                @endif
                <hr>
                <p>Accountant Officer</p>
            </td>
        </tr>
    </table>
</div>




        <div class="actions">
            <button onclick="window.print()">Print Gatepass</button>
            <form action="{{ route('download.gatepass', $gatepass->id) }}" method="GET" style="display:inline;">
               
            </form>
        </div>

        <div class="footer-note">
            Generated by IRDP Exam Gatepass System — Authentic Document
        </div>
    </div>
</body>
</html>
