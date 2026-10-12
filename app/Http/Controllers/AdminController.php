<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;

class AdminController extends Controller
{
    
    public function index(Request $request)
    {
        $users = User::all();

        
        $query = Student::query();

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('reg_number', 'like', "%{$search}%");
            });
        }

       
        $perPage = $request->get('per_page', 15);

        $students = $query->paginate($perPage);

        return view('admin.dashboard', compact('users', 'students'));
    }

    
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    
    public function addUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string'
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        return response()->view('admin.success', [
            'message' => 'User added successfully'
        ]);
    }

  
    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'blocked';
        $user->save();

        return response()->view('admin.success', [
            'message' => 'User blocked successfully'
        ]);
    }

   
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->view('admin.success', [
            'message' => 'User deleted successfully'
        ]);
    }

    
    public function students(Request $request)
    {
        $query = Student::query();

     
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('reg_number', 'like', "%{$search}%");
            });
        }

        
        $perPage = $request->get('per_page', 15);

        $students = $query->paginate($perPage);

        return view('admin.students', compact('students'));
    }

    
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->view('admin.success', [
            'message' => 'Student deleted successfully'
        ]);
    }

  
    public function deleteSelectedStudents(Request $request)
    {
        $ids = $request->input('student_ids', []);
        if (!empty($ids)) {
            Student::whereIn('id', $ids)->delete();
            return response()->view('admin.success', [
                'message' => 'Selected students deleted successfully'
            ]);
        }

        return response()->view('admin.success', [
            'message' => 'No students selected'
        ]);
    }
}
