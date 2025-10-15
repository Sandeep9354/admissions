<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdmissionsController extends Controller
{
    // Middleware to secure routes
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // GET /api/admissions
   // GET /api/admissions
public function index(Request $request)
{
    // Start query builder
    $query = Admission::query();

    // Live search
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('student_name', 'like', "%{$search}%")
              ->orWhere('father_name', 'like', "%{$search}%")
              ->orWhere('mother_name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%");
        });
    }

    // Class filter
    if ($request->has('class') && !empty($request->class)) {
        $query->where('class_applied', $request->class);
    }

    // Pagination
    $perPage = $request->get('per_page', 10); // default 10
    $admissions = $query->orderBy('created_at', 'desc')->paginate($perPage);

    return response()->json($admissions);
}


// POST /api/admissions
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'student_name' => 'required|string|max:255',
        'class_applied' => 'required|string',
        'dob' => 'required|date',
        'email' => 'nullable|email',
        'registration_no' => 'required|string', // teacher manually enters
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $data = $request->all();

    // Optional fields defaults
    $optionalFields = [
        'dob_words','gender','mobile','whatsapp','address','section','father_name','mother_name',
        'parent_email','parent_occupation','prev_school_details','permanent_edu_no','appar_id_no',
        'subject_opted','transport_stoppage','status','transport_facility'
    ];

    foreach ($optionalFields as $field) {
        $data[$field] = $data[$field] ?? null;
    }

    $data['status'] = $data['status'] ?? 'Pending';
    $data['transport_facility'] = $data['transport_facility'] ?? 'No';

    // Handle file uploads
    $files = [
        'student_photo','birth_certificate','student_aadhar','father_aadhar','mother_aadhar',
        'transfer_certificate','medical_certificate'
    ];

    foreach ($files as $file) {
        if ($request->hasFile($file)) {
            $fileObj = $request->file($file);

            // 🔹 Store file in storage/app/public/uploads
            $filename = time().'_'.$fileObj->getClientOriginalName();
            $path = $fileObj->storeAs('public/uploads', $filename); // Laravel storage disk

            // 🔹 Save public path in DB (accessible via storage link)
            $data[$file] = 'storage/uploads/'.$filename;
        }
    }

    $admission = Admission::create($data);

    return response()->json([
        'success' => true,
        'admission' => $admission,
        'id' => $admission->id
    ], 201);
}


    // Helper function to save base64 files
    private function saveBase64File($base64Data, $prefix)
    {
        try {
            // Get file extension
            preg_match("/^data:(.*?);base64,/", $base64Data, $matches);
            $mimeType = $matches[1] ?? 'image/png';
            $extension = explode('/', $mimeType)[1] ?? 'png';

            // Remove metadata and decode
            $fileData = preg_replace('/^data:.*;base64,/', '', $base64Data);
            $fileData = base64_decode($fileData);

            // Generate filename
            $fileName = $prefix.'_'.time().'_'.Str::random(5).'.'.$extension;

            // Save to public storage
            Storage::disk('public')->put('uploads/'.$fileName, $fileData);

            return 'uploads/'.$fileName;
        } catch (\Exception $e) {
            return null;
        }
    }

    // GET /api/admissions/{id}
    public function show($id)
    {
        $admission = Admission::find($id);
        if(!$admission){
            return response()->json(['message'=>'Not Found'],404);
        }
        return response()->json($admission);
    }

    // PUT /api/admissions/{id}
    public function update(Request $request, $id)
    {
        $admission = Admission::find($id);
        if(!$admission){
            return response()->json(['message'=>'Not Found'],404);
        }

        $admission->update($request->all());

        return response()->json(['success' => true, 'admission' => $admission]);
    }

    // DELETE /api/admissions/{id}
    public function destroy($id)
    {
        $admission = Admission::find($id);
        if(!$admission){
            return response()->json(['message'=>'Not Found'],404);
        }

        $admission->delete();
        return response()->json(['success' => true, 'message' => 'Deleted Successfully']);
    }

    // Dashboard stats
public function stats()
{
    $totalAdmissions = Admission::count();
    $todaysAdmissions = Admission::whereDate('created_at', Carbon::today())->count();
    $classCount = Admission::distinct('class_applied')->count('class_applied');
    $pending = Admission::where('status', 'pending')->count();

    return response()->json([
        'total_admissions' => $totalAdmissions,
        'todays_admissions' => $todaysAdmissions,
        'class_count' => $classCount,
        'pending' => $pending
    ]);
}

}
