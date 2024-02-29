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

        $users = User::latest();

        if (!empty($request->get('keyWord'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyWord') . '%')
                ->orWhere('email', 'like', '%' . $request->get('keyWord') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.users.list', [
            'users' => $users,
            'roles' => Role::get(),
        ]);
    }

    public function create(Request $request)
    {

        $roles = Role::latest()->get();

        return view('admin.users.create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'status' => 'required|in:1,0',
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


    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            $message = 'User not found.';
            session()->flash('error', $message);
            return redirect()->route('admin.users.index');
        }

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::latest()->get(),
        ]);
    }

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
            'phone' => 'required',
            'status' => 'required|in:1,0',
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

    public function destroy($id)
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

        $user->delete();
        $message = 'User deleted successfully.';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
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

                $action .= " <a class='btn btn-xs btn-warning' id='btnEdit' href='" . route('users.edit', $row->id) . "'><i class='fas fa-edit'></i></a>";

                $action .= " <button class='btn btn-xs btn-outline-danger' data-id='" . $row->id . "' id='btnDel'><i class='fas fa-trash'></i></button>";

                return $action;
            })
            ->rawColumns(['name', 'date', 'roles', 'action'])
            ->make(true);
    }
}
