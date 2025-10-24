<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Auth;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Show the users dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        try {
            $roles = Role::pluck('name', 'id');
            $users = User::all();
            return view('create-user', compact('roles', 'users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show User List
     *
     * @param Request $request
     * @return mixed
     */

    public function getUserList(Request $request): mixed
    {
        $excludedRoleIds = Role::whereIn('name', ['Super Admin', 'Admin'])->pluck('id')->toArray();
        $data = User::whereDoesntHave('roles', function ($query) use ($excludedRoleIds) {
                $query->whereIn('id', $excludedRoleIds);
            })
            ->get();
        $hasManageUser = Auth::user()->can('manage_user');

        return Datatables::of($data)
            ->addColumn('serial_no', function ($data) {
                return $data->id;
            })
            ->addColumn('roles', function ($data) {
                $roles = $data->getRoleNames()->toArray();
                $badge = '';
                if ($roles) {
                    $badge = implode(' , ', $roles);
                }

                return $badge;
            })
            ->addColumn('permissions', function ($data) {
                $roles = $data->getAllPermissions();
                $badges = '';
                foreach ($roles as $key => $role) {
                    $badges .= '<span class="text text-dark m-1">' . $role->name . '</span>';
                }

                return $badges;
            })
            ->addColumn('action', function ($data) use ($hasManageUser) {
                $output = '';
                if ($data->name == 'Super Admin') {
                    return '';
                }
                $canEdit = Auth::user()->can('edit_user');
                $canDelete = Auth::user()->can('delete_user');

                //if ($hasManageUser && ($canEdit || $canDelete)) {
                    $output = '<div class="table-actions">';
                   // if ($canEdit) {
                        $output .= '<a href="' . url('employee/' . $data->id) . '" ><i class="ik ik-edit-2 f-16 mr-15 text-success"></i></a>';
                   // }
                    if ($canDelete) {
                        $output .= '<a href="' . url('employee/delete/' . $data->id) . '"><i class="ik ik-trash-2 f-16 text-danger"></i></a>';
                    }
                    $output .= '</div>';
                //}

                return $output;
            })
            ->rawColumns(['roles', 'permissions', 'action'])
            ->make(true);
    }

    /**
     * User Create
     *
     * @return mixed
     */
    public function create(): mixed
    {
        try {
            $roles = Role::pluck('name', 'id');
            $users = User::all();
            return view('create-user', compact('roles', 'users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store User
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email',
                'password' => 'required',
                'role' => [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) {
                        if (!Role::where('id', $value)->exists()) {
                            $fail($attribute . ' is invalid.');
                        }
                    },
                ],
                'start_date' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $userData = [
                'password' => Hash::make($request->input('password')),
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone', '000-0000'),
                'otp' => rand(100000, 999999),
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ];
    
            // Check if user_id is present to determine if it's an update or create operation
            if ($request->has('user_id')) {
                $user = User::findOrFail($request->user_id);
    
                // Check if the email is being updated to a new value
                if ($user->email !== $request->input('email') && User::where('email', $request->input('email'))->exists()) {
                    return response()->json(['errors' => ['email' => 'The email has already been taken.']], 422);
                }
    
                $user->update($userData);
            } else {
                // Create new user
                // Check if the email already exists
                if (User::where('email', $request->input('email'))->exists()) {
                    return response()->json(['errors' => ['email' => 'The email has already been taken.']], 422);
                }
    
                $user = User::create($userData);
            }

            if ($request->has('role')) {
                $role_id = (int)$request->input('role');
                $role = Role::find($role_id);

                if ($role) {
                    // Assign new role to the user
                    $user->syncRoles([$role_id]);
                } else {
                    return response()->json(['errors' => ['role' => 'Invalid role selected']], 422);
                }
            }

            // Return the updated user data
            return response()->json(['user' => $user, 'message' => 'Employee information saved successfully!'], 200);
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return response()->json(['error' => $bug], 500);
        }
    }
           
    
    /**
     * Edit User
     *
     * @param int $id
     * @return mixed
     */
    public function edit($id): mixed
    {
        try {
            $user = User::with('roles', 'permissions')->find($id);
            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::pluck('name', 'id');

                return view('user-edit', compact('user', 'user_role', 'roles'));
            }

            return redirect('404');
        } catch (\Exception $e) {
            $bug = $e->getMessage();

            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Update User
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        // update user info
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'firstname' => 'required | string ',
            'lastname' => 'required | string ',
            'phone' => 'required | numeric',
            'email' => 'required | email',
            'role' => 'required',
        ]);

        // check validation for password match
        if (isset($request->password)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required | confirmed',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try {
            if ($user = User::find($request->id)) {
                $payload = [
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ];
                // update password if user input a new password
                if (isset($request->password) && $request->password) {
                    $payload['password'] = $request->password;
                }

                $update = $user->update($payload);
                // sync user role
                $user->syncRoles($request->role);

                return redirect()->back()->with('success', 'Employee information updated succesfully!');
            }

            return redirect()->back()->with('error', 'Failed to update Employee! Try again.');
        } catch (\Exception $e) {
            $bug = $e->getMessage();

            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Delete User
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
    
        if ($user->role === 'Admin' || $user->role === 'Super Admin') {
            return response()->json(['error' => 'Cannot delete admin or super admin'], 422);
        }
    
        $user->delete();
        return response()->json(['message' => 'Employee deleted!']);
    }
    
    public function restoreUser(Request $request): JsonResponse
    {
        $user = User::withTrashed()->find($request->user_id);
        if ($user) {
            $user->restore();
            return response()->json(['message' => 'Employee restored!']);
        }
    
        return response()->json(['error' => 'Employee not found'], 404);
    }

}
