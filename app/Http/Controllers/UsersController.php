<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserCreated;
use App\Services\RoleService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class UsersController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function index(RoleService $roleService)
    {
        if (\request()->ajax()) {
            return datatables()->of(User::select('*'))
                ->addColumn('action', function (User $user) {
                    // delete button
                    $deleteBtn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $user->id . '" data-original-title="Delete" class="dropdown-item js-delete">Delete</a>';
                    // edit button
                    $editBtn = '<a href="' . route("admin.system.users.show", $user->id) . '" data-toggle="tooltip"  data-id="' . $user->id . '" data-original-title="Edit" class="dropdown-item js-edit">Edit</a>';
                    // roles button
                    $rolesBtn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $user->id . '" data-original-title="Roles" class="dropdown-item rolesUser">Roles</a>';
                    return "<div class='drop-down dropdown-action'>
                                <a href='#' class='dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-three-dots-vertical'></i>
                                </a>
                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li>$editBtn</li>
                                    <li>$deleteBtn</li>
                                    <li>
                                      <a href='" . route('admin.system.users.reset-password', $user->id) . "' class='dropdown-item js-reset-password'>Reset Password</a>
                                    </li>
                                </ul>
                            </div>";

                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $roles = $this->roleService->getAllRoles();
        return view('admin.users.list', [
            'roles' => $roles,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email',
                Rule::unique('users')->ignore($request->id),
            ],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        $id = $request->input('id');
        DB::beginTransaction();
        $random = Str::random(8);
        $user = User::updateOrCreate(['id' => $id], [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone'),
            'password' => Hash::make($random)
        ]);

        $user->roles()->sync($request->input('roles'));

        DB::commit();
        if (!$id || $id == 0) {
            $user->notify(new UserCreated($user, $random));
        }

        return response()->json([
            'message' => 'User saved successfully with password: ' . $random . ' Please inform the user to change the password',
            'user' => $user,
        ]);
    }

    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function changePasswordView()
    {
        return view('admin.users.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()
                ->back()->with('error', 'Current password is incorrect');
        }


        $user->password = Hash::make($request->input('password'));
        $user->password_changed_at = now();
        $user->save();

        return redirect()
            ->intended()->with('success', 'Password changed successfully');
    }

    public function resetPassword(User $user)
    {
        $random = Str::random(8);
        $user->password = Hash::make($random);
        $user->password_changed_at = null;
        $user->save();
        return back()
            ->with('success', 'Password reset successfully. New password is: ' . $random);
    }

}
