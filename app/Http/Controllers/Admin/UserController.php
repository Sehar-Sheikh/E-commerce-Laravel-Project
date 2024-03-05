<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUsers();
        }
        return view('admin.users.list')->with(["roles" => Role::get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roles = Role::latest()->get();

        return view('admin.users.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request,User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email:rfc,dns|unique:users,email,'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->fill($request->except('roles', 'password'));
            $user->password = Hash::make($request->password);
            $user->save();

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            $message = 'User added successfully.';
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            "user" => $user,
            "userRole" => $user->roles->pluck('name')->toArray(),
            "roles" => Role::latest()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            $message = 'User not found.';
            session()->flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            $user->update($request->except('roles', 'password'));

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $message = 'User updated successfully.';
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->ajax() && $user->delete()) {
            return response(["message" => "USer Deleted Successfully"], 200);
        }
        return response(["message" => "User Deletion Failed! Try Again!"], 201);
    }

    private function getUsers()
    {
        $data = User::with('roles')->get();
        return DataTables::of($data)
            ->addColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->created_at)->format('d M, Y h:i:s A');
            })
            ->addColumn('roles', function ($row) {
                $role = "";
                if ($row->roles != null) {
                    foreach ($row->roles as $next) {
                        $role .= '<span class="badge badge-primary">' . ucfirst($next->name) . '</span> ';
                    }
                }
                return $role;
            })
            ->addColumn('action', function ($row) {
                $action = "";
                // if(auth()->user()->hasRole('superuser'))
                $action .= " <a class='btn btn-xs btn-warning' id='btnEdit' href='" . route('users.edit', $row->id) . "'><i class='fas fa-edit'></i></a>";
                // if(auth()->user()->hasRole('superuser'))
                $action .= " <button class='btn btn-xs btn-outline-danger' data-id='" . $row->id . "' id='btnDel'><i class='fas fa-trash'></i></button>";
                return $action;
            })
            ->rawColumns(['name', 'date', 'roles', 'action'])->make(true);
    }
}
