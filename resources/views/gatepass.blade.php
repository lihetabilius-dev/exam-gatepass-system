<!DOCTYPE html>
<html>
<head>
    <title>Exam Gatepass</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            background-color: #f8f9fa; 
        }
        @page { size: A4; margin: 20mm; }

        .gatepass {
            border: 3px double #2c3e50;
            padding: 20px;
            width: 400px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 12px;
            page-break-inside: avoid;
        }

        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #2c3e50; 
            padding-bottom: 8px; 
            position: relative;
        }
        .header h2 { 
            margin: 0; 
            font-size: 18px; 
            color: #2c3e50; 
        }
        .header-logo {
            display: inline-block;
            vertical-align: middle;
        }
        
        .passport-thumb {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 50px;      
            height: 50px;     
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #2c3e50;
            background: #f0f0f0;
        }

        .details { 
            margin-bottom: 15px; 
            font-size: 13px; 
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 6px 10px;
            border-bottom: 1px solid #ccc;
        }
        .details td:first-child {
            font-weight: bold;
            width: 40%;
            color: #2c3e50;
        }

        .qr { 
            text-align: center; 
            margin-top: 15px; 
        }
        .qr p { 
            font-size: 11px; 
            color: #555; 
        }

        .actions { 
            text-align: center; 
            margin-top: 20px; 
        }
        button { 
            padding: 8px 16px; 
            margin: 5px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            background-color: #2c3e50; 
            color: #fff; 
            font-weight: bold; 
            font-size: 12px; 
        }
        button:hover { 
            background-color: #34495e; 
        }

        .footer-note { 
            text-align: center; 
            font-size: 10px; 
            margin-top: 10px; 
            color: #666; 
        }
        .signatures table {
            width: 100%;
            margin-top: 10px;
        }
        .signatures td {
            text-align: center;
            vertical-align: bottom;
        }
        .signatures img {
            height: 35px;
            display: block;
            margin: 0 auto;
        }
        .signatures hr {
            border: 1px solid #555;
            width: 60%;
            margin: 5px auto;
        }
    </style>
</head>
<body>
    <div class="gatepass">
        <div class="header">
         
            <div class="header-logo">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" 
                     alt="Institution Logo" style="max-width:80px;">
            </div>
            <h2>Exam Gatepass</h2>
            <p><strong>Institution:</strong> IRDP-LZC</p>

            
            @if($student->passport_photo && file_exists(storage_path('app/public/passports/' . $student->passport_photo)))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/passports/' . $student->passport_photo))) }}" 
                     class="passport-thumb" 
                     alt="Passport Photo">
            @else
                <div class="passport-thumb" style="background:#ddd; display:flex; align-items:center; justify-content:center;">
                    <span style="font-size:8px;">No Photo</span>
                </div>
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
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="max-width:200px;">
            <p>Scan QR Code to verify authenticity</p>
        </div>

      
        <div class="signatures">
            <table>
                <tr>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('signatures/exam_officer.png'))) }}" 
                             alt="Examination Officer Signature">
                        <hr>
                        <p>Examination Officer</p>
                    </td>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('signatures/accountant_officer.png'))) }}" 
                             alt="Accountant Officer Signature">
                        <hr>
                        <p>Accountant Officer</p>
                    </td>
                </tr>
            </table>
        </div>

       
        <div class="actions">
            <button onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>