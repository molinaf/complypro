<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Fetch all users with "Pending" status
        $pendingUsers = User::where('status', 'P')->get();

        // Return the admin dashboard view with pending users
        return view('dashboard.admin', compact('pendingUsers'));
    }

    /**
     * Show the company selection page.
     *
     * @return \Illuminate\View\View
     */
    public function manageRoles(Request $request)
    {
        // Fetch all companies
        $companies = Company::all();

        // If a company is selected, fetch users for that company
        $users = [];
        if ($request->has('company_id')) {
            $users = User::where('company_id', $request->input('company_id'))->get();
        }

        return view('admin.manage_roles', compact('companies', 'users'));
    }

    /**
     * Update the role of a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:U,S,A,G,M',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    /**
     * Show the edit form for a user.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all(); // Include companies for possible reassignment
        return view('admin.edit_user', compact('user', 'companies'));
    }

    /**
     * Update the user details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:U,S,A,G,M',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->company_id = $request->input('company_id');
        $user->save();

        return redirect()->route('admin.manage.roles')->with('success', 'User details updated successfully.');
    }
    public function showAll()
    {
        // Fetch all users, grouped by company, sorted by name
        $groupedUsers = User::with('company')
            ->get()
            ->sortBy('name')
            ->groupBy(function ($user) {
                return $user->company->name ?? 'No Company';
            });

        // Pass grouped users to the view
        return view('admin.manage_roles', [
            'groupedUsers' => $groupedUsers,
            'companies' => Company::all(), // Include companies for the dropdown
        ]);
    }
    /**
     * Display the manager dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function managerDashboard()
    {
        // Fetch all pending users for managers to approve
        $pendingUsers = User::where('status', 'P')->get();

        return view('dashboard.manager', compact('pendingUsers'));
    }
}
