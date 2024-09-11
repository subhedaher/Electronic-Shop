<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $admins = Admin::with('roles')->where('id', '!=', auth(session('guard'))->user()->id)->paginate(5);
        return view('cms.admins.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('guard_name', '=', 'admin')->get();
        return view('cms.admins.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'role_id' => 'required|numeric|exists:roles,id',
            'full_name' => 'required|string|min:3|max:45',
            'email' => 'required|email|unique:admins',
            'address' => 'required|string|min:3|max:45',
            'phone_number' => 'required|string|unique:admins|min:12|max:15',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), 'string', 'confirmed'
            ]
        ]);


        if (!$validator->fails()) {
            $admin = new Admin();
            $admin->full_name = $request->input('full_name');
            $admin->email = $request->input('email');
            $admin->address = $request->input('address');
            $admin->phone_number = $request->input('phone_number');
            $admin->password = Hash::make($request->input('password'));
            $saved = $admin->save();
            if ($saved) {
                $admin->assignRole((int)$request->input('role_id'));
            }
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Admin Added Successfully" : "Admin Added Failed!"
            ], $saved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $roles = Role::where('guard_name', '=', 'admin')->get();
        $currentRole = $admin->roles()->first();
        return view('cms.admins.edit', ['roles' => $roles, 'currentRole' => $currentRole, 'admin' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validator = Validator($request->all(), [
            'role_id' => 'required|numeric|exists:roles,id',
        ]);

        if (!$validator->fails()) {
            $admin->syncRoles((int)$request->input('role_id'));
            return response()->json([
                'status' => true,
                'message' => "Admin updated Successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $countRow = Admin::destroy($admin->id);
        return response()->json([
            'status' => $countRow,
            'message' => $countRow ? "Admin Deleted Successfully" : "Admin Deleted Failed!"
        ], $countRow ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
