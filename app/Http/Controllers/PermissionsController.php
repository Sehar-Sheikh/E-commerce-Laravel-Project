<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getPermission($request->role_id);
        }
        return view('users.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);
        $permission = Permission::create(["name" => strtolower(trim($request->name))]);
        if ($permission) {
            toast('New Permission Added successfully!', 'success');
            return view('users.permissions.index');
        }
        toast('Error on Saving Permission!', 'error');
        return back()->withInput();
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
    public function edit(Permission $permission)
    {
        return view('users.permissions.edit')->with(['permission'=>$permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            "name" => 'required|unique:permissions,name,'.$permission->id
        ]);

        if($permission->update($request->only('name')))
        {
           toast('New Permission Updated successfully!', 'success');
           return view('users.permissions.index');
        }
           toast ('Error on Updating Permission!','error');
           return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        if ($request->ajax() && $permission->delete()) {
            return response(["message" => "Permission Deleted Successfully"], 200);
        }
        return response(["message" => "Data Deletion Failed! Try Again!"], 201);
    }

    private function getPermission($role_id)
    {
        $data = Permission::get();

        return DataTables::of($data, $role_id)
            ->addColumn('chkBox', function ($row) use ($role_id) {
                if ($row->name == "home") {
                    return "<input type='checkbox' name='permission[" . $row->name . "]' value=" . $row->name . " checked onclick='return false;'>";
                } else {
                    $role = Role::find($role_id);

                    if ($role) {
                        $rolePermissions = $role->permissions->pluck('name')->toArray();

                        if ($rolePermissions && in_array($row->name, $rolePermissions)) {
                            return "<input type='checkbox' name='permission[" . $row->name . "]' value=" . $row->name . " checked>";
                        }
                    }

                    return "<input type='checkbox' name='permission[" . $row->name . "]' value=" . $row->name . " class='permission'>";
                }
            })
            ->addColumn('action', function ($row) {
                $action = "";
                $action .= "<a class='btn btn-sm btn-warning' id='btnEdit' href='" . route('users.permissions.edit', $row->id) . "'><i class='fas fa-edit'></i></a>";
                $action .= " <button class='btn btn-sm btn-outline-danger' data-id='" . $row->id . "' id='btnDel'><i class='fas fa-trash'></i></button>";

                return $action;
            })
            ->rawColumns(['chkBox', 'action'])
            ->make(true);
    }
}
