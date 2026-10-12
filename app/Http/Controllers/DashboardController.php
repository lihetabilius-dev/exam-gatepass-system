<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\EligibleStudent;
use App\Models\Gatepass;
use Barryvdh\DomPDF\Facade\Pdf;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class DashboardController extends Controller
{
    public function index()
    {
        if (!in_array(Auth::user()->role, ['dean', 'accounts'])) {
            return redirect('/')->with('error', 'Access denied.');
        }
        return view('dashboard');
    }

   
    public function uploadByAccountant(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $regNumber  = trim($row[0]);
            $fullname   = trim($row[1]);
            $amountPaid = (float) str_replace([',', ' '], '', $row[2]);

            $student = Student::firstOrCreate(
                ['reg_number' => $regNumber],
                ['full_name' => $fullname]
            );

            DB::table('payments')->insert([
                'student_id'    => $student->id,
                'amount_paid'   => $amountPaid,
                'payment_status'=> 'Ada',
                'payment_date'  => now()->toDateString(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $eligibility = EligibleStudent::firstOrCreate(['student_id' => $student->id]);
            $eligibility->update([
                'amount_paid' => $amountPaid
            ]);
        }

        fclose($handle);
        return back()->with('success', 'Accountant CSV uploaded successfully!');
    }


    public function uploadByDean(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $fullname   = trim($row[0]);
            $nhifStatus = trim($row[1]);

            $student = Student::where('full_name', $fullname)->first();

            if ($student) {
                DB::table('payments')->insert([
                    'student_id'    => $student->id,
                    'amount_paid'   => 0,
                    'payment_status'=> $nhifStatus,
                    'payment_date'  => now()->toDateString(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $eligibility = EligibleStudent::firstOrCreate(['student_id' => $student->id]);
                $eligibility->update([
                    'nhif_status' => $nhifStatus
                ]);
            }
        }

        fclose($handle);
        return back()->with('success', 'Dean CSV uploaded successfully!');
    }

    public function generateGatepass(Request $request)
    {
        $request->validate([
            'reg_number' => 'required|string',
            'exam_type'  => 'required|string',
        ]);

        $student = Student::where('reg_number', $request->reg_number)->first();
        if (!$student) return back()->with('error', 'Mwanafunzi hajapatikana!');

        $eligibility = EligibleStudent::where('student_id', $student->id)->first();
        if (!$eligibility) return back()->with('error', 'Hakuna rekodi za malipo!');

        if (empty($eligibility->amount_paid) || $eligibility->amount_paid <= 0) {
            return back()->with('error', 'HUJALIPA ADA');
        }

        if (empty($eligibility->nhif_status) || $eligibility->nhif_status !== 'Successful Paid') {
            return back()->with('error', 'HUJALIPIA BIMA');
        }

        $eligibility->update(['is_eligible' => true]);

       
        $existingGatepass = Gatepass::where('student_id', $student->id)
            ->where('exam_type', $request->exam_type)
            ->first();

        if ($existingGatepass) {
            $gatepass = $existingGatepass;
        } else {
            $token = hash('sha256', $student->reg_number . now() . $request->exam_type);

            $gatepass = Gatepass::create([
                'student_id' => $student->id,
                'exam_type'  => $request->exam_type,
                'qr_code'    => $token,
                'issued_at'  => now(),
            ]);
        }

        $payload = "Reg Number: {$student->reg_number}\n"
                 . "Full Name: {$student->full_name}\n"
                 . "Exam Type: {$gatepass->exam_type}\n"
                 . "Payment Status (BIMA): {$eligibility->nhif_status}\n"
                 . "Amount Paid (Ada): " . number_format($eligibility->amount_paid, 2, '.', ',') . "\n"
                 . "Issued At: " . \Carbon\Carbon::parse($gatepass->issued_at)->format('d M Y H:i');

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($payload)
            ->size(200)
            ->build();

        $qrCode = base64_encode($result->getString());

        return view('gatepass', [
            'student' => $student,
            'gatepass' => $gatepass,
            'qrCode' => $qrCode,
            'nhifStatus' => $eligibility->nhif_status
        ]);
    }

    public function downloadGatepass($id)
    {
        $gatepass = Gatepass::findOrFail($id);
        $student = $gatepass->student;

        $eligibility = EligibleStudent::where('student_id', $student->id)->first();

        $payload = "Reg Number: {$student->reg_number}\n"
                 . "Full Name: {$student->full_name}\n"
                 . "Exam Type: {$gatepass->exam_type}\n"
                 . "Payment Status (BIMA): " . ($eligibility->nhif_status ?? 'Not Recorded') . "\n"
                 . "Amount Paid (Ada): " . number_format($eligibility->amount_paid ?? 0, 2, '.', ',') . "\n"
                 . "Issued At: " . \Carbon\Carbon::parse($gatepass->issued_at)->format('d M Y H:i');

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($payload)
            ->size(200)
            ->build();

        $qrCode = base64_encode($result->getString());

  
        $pdf = Pdf::loadView('gatepass.pdf', [
            'student' => $student,
            'gatepass' => $gatepass,
            'qrCode' => $qrCode,
            'nhifStatus' => $eligibility->nhif_status ?? 'Not Recorded'
        ]);

        $cleanReg = str_replace(['/', '\\'], '_', $student->reg_number);
        return $pdf->download('gatepass_'.$cleanReg.'.pdf');
    }
}
