<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->getRoles();
        }
        return view('users.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('users.roles.create')->with(['Permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate name
        $this->validate($request, [
            'name'=>'required|unique:roles,name',
            'permission'=>'required'
        ]);
        $role = Role::create(['name'=>strtolower(trim($request->name))]);
        $role->syncPermissions($request->permission);
        if($role)
        {
            toast('New Role Added successfully!', 'success');
           return view('users.roles.index');
        }
           toast ('Error on Saving Role!','error');
           return back()->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Role $role)
    {
        if($request->ajax())
        {
            return $this->getRolesPermissions($role);
        }
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('users.roles.show')->with(['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
    }

    private function getRoles()
    {
        $data=Role::withCount(['users','permissions'])->get();
        return DataTables::of($data)
        ->addColumn('name', function($row)
        {
            return ucfirst($row->name);
        })
        ->addColumn('users_count', function($row)
        {
            return $row->users_count;
        })
        ->addColumn('permissions_count', function($row)
        {
            return $row->permissions_count;
        })
        ->addColumn('action',function($row){
            $action = "";
            $action.="<a class='btn btn-sm btn-success' id='btnShow' href='".route('users.roles.show', $row->id)."'><i class='fas fa-eye'></i></a>";
            $action.=" <a class='btn btn-sm btn-warning' id='btnEdit' href='".route('users.roles.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
            $action.=" <button class='btn btn-sm btn-outline-danger' data-id='" .$row->id. "' id='btnDel'><i class='fas fa-trash'></i></button>";
            return $action;
        })
        ->make(true);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('users.roles.edit')->with(['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'permission'=>'required',
        ]);
        $role->update($request->only('name'));
        $role->syncPermissions($request->permission);
        if($role)
        {
            toast('New Role Updated successfully!', 'success');
           return view('users.roles.index');
        }
           toast ('Error on Updating Role!','error');
           return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        if ($request->ajax() && $role->delete())
        {
            return response(["message" => "Role Deleted Successfully"], 200);
        }
        return response(["message" => "Role Deletion Failed! Try Again!"], 201);

    }



    private function getRolesPermissions($role)
    {
        $permissions = $role->permissions;
        return DataTables::of($permissions)->make(true);
    }
}
