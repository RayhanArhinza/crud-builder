<?php

namespace App\Http\Controllers;

use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::query();

        // Filter berdasarkan search query jika ada
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Menampilkan 5 data per halaman
        $roles = $query->paginate(5);

        return view('role.index', compact('roles'));
    }


    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $role = Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('role.index');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $role = Role::find($id);
        $role->update([
            'name' => $request->name,
        ]);
        return redirect()->route('role.index');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('role.index');
    }
}
