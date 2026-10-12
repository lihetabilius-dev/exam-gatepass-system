<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Gatepass;
use App\Models\Payment;
use App\Models\NhifPayment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GatepassController extends Controller
{
    /**
     * Show the gatepass generation form with eligibility & upload
     */
    public function create(Request $request)
    {
        $student = null;
        $eligibility = null;

        if ($request->has('reg_number')) {
            $student = Student::where('reg_number', $request->reg_number)->first();
            if ($student) {
                $eligibility = $this->checkEligibility($student->id);
            }
        }

        return view('gatepass.form', [
            'student' => $student,
            'eligibility' => $eligibility,
        ]);
    }

    /**
     * Check if student has paid ADA (NHIF inachukuliwa kuwa imelipwa ikiwa ADA imelipwa)
     */
    private function checkEligibility($studentId)
    {
        
        $adaPaid = Payment::where('student_id', $studentId)->exists();

        
        return [
            'ada' => $adaPaid,
            'eligible' => $adaPaid  
        ];
    }

    /**
     * API endpoint to check eligibility and passport status (AJAX)
     */
    public function checkEligibilityApi(Request $request)
    {
        $regNumber = $request->query('reg_number');
        if (!$regNumber) {
            return response()->json(['error' => 'Registration number required']);
        }

        $student = Student::where('reg_number', $regNumber)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found']);
        }

        $eligibility = $this->checkEligibility($student->id);

        return response()->json([
            'eligible' => $eligibility['eligible'],
            'ada' => $eligibility['ada'],
            'has_passport' => !empty($student->passport_photo),
            'passport_photo' => $student->passport_photo,
        ]);
    }

    /**
     * Upload passport photo via AJAX
     */
    public function uploadPassport(Request $request)
    {
        $request->validate([
            'passport' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'reg_number' => 'required|string'
        ]);

        $student = Student::where('reg_number', $request->reg_number)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

      
        $eligibility = $this->checkEligibility($student->id);
        if (!$eligibility['eligible']) {
            return response()->json(['success' => false, 'message' => 'You are not eligible. Please clear ADA payment first.']);
        }

        $file = $request->file('passport');
        $filename = time() . '_' . preg_replace('/[\/\\\\]/', '_', $student->reg_number) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('passports', $filename, 'public');

        $student->passport_photo = $filename;
        $student->save();

        return response()->json([
            'success' => true,
            'photo_url' => Storage::url($path)
        ]);
    }

    /**
     * Generate Gatepass (after passport uploaded & eligible)
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'exam_type' => 'required|string|in:SUPP,SPECIAL,RM,END OF SEMESTER I,END OF SEMESTER II,TEST I,TEST II',
            'reg_number' => 'required|string',
        ]);

       
        $student = Student::where('reg_number', $request->reg_number)->first();

        if (!$student) {
            return back()->with('error', 'Student not found!');
        }

        
        if (!$student->passport_photo) {
            return back()->with('error', 'Please upload your passport photo first.');
        }

        
        $eligibility = $this->checkEligibility($student->id);
        if (!$eligibility['eligible']) {
            return back()->with('error', 'You are not eligible. Please clear ADA payment first.');
        }

        try {
            
            $gatepass = Gatepass::create([
                'student_id' => $student->id,
                'exam_type'  => $request->exam_type,
                'issued_at'  => now(),
                'qr_code'    => Str::uuid(),
            ]);

            
            $signatureData = $student->reg_number . '|' 
                           . $student->full_name . '|' 
                           . $gatepass->exam_type . '|' 
                           . $gatepass->issued_at;
            $digitalSignature = hash('sha256', $signatureData);

        
            $qrData = "Reg Number: {$student->reg_number}\n"
                    . "Name: {$student->full_name}\n"
                    . "Exam: {$gatepass->exam_type}\n"
                    . "ADA Paid: " . ($eligibility['ada'] ? 'Yes' : 'No') . "\n"
                    . "Issued At: " . \Carbon\Carbon::parse($gatepass->issued_at)->format('d M Y H:i') . "\n"
                    . "Signature: {$digitalSignature}";

           i
            $cleanReg = str_replace(['/', '\\'], '_', $student->reg_number);

           
            return view('gatepass.show', [
                'student' => $student,
                'gatepass' => $gatepass,
                'qrCode' => QrCode::size(180)->generate($qrData),
                'digitalSignature' => $digitalSignature,
                'cleanReg' => $cleanReg,
            ]);
        } catch (QueryException $e) {
            
            return back()->with('error', 'Database error: '.$e->getMessage());
        }
    }
}