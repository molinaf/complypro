<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    /**
     * Handle user endorsement by a supervisor.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {

        // $this->middleware('auth'); // Ensure the user is logged in
        // $this->middleware(function ($request, $next) {
        //     if (auth()->user()->role !== 'S') {
        //         abort(403, 'Unauthorized action.');
        //     }
        //     return $next($request);
        // });

    }

    public function endorseUser(Request $request, $id)
    {
        // Find the user to be endorsed
        $user = User::findOrFail($id);

        //dd($user,$request,$id);
        // Ensure the user is in "Pending" status
        if ($user->status !== 'P') {
            return redirect()->back()->with('error', 'This user has already been processed.');
        }
        //dd($user,$request,$id);

        // Update the user's status and assign the supervisor
        $user->status = 'A'; // Active
        $user->supervisor_id = auth()->id(); // Set the supervisor's ID
        $user->save();

        return redirect()->route('home')->with('success', 'User successfully endorsed.');
    }
    /**
     * Show a list of pending users.
     *
     * @return \Illuminate\View\View
     */
    public function showPendingUsers()
    {
        $user = auth()->user();

        // Check the role of the logged-in user
        if (in_array($user->role, ['A', 'M'])) {
            // Admin: View all pending users
            $pendingUsers = User::where('status', 'P')->get();
        } else if ($user->role === 'S') {
            // Supervisor: View pending users in their company
            $pendingUsers = User::where('status', 'P')
                ->where('company_id', $user->company_id)
                ->get();
        } else {
            // Unauthorized access for other roles
            abort(403, 'Unauthorized action.');
        }

        return view('supervisor.pending_users', compact('pendingUsers'));
    }

    public function dashboard()
    {
        // Fetch users with "Pending" status for the supervisor's company
        $pendingUsers = \App\Models\User::where('status', 'P')
            ->where('company_id', auth()->user()->company_id)
            ->get();

        return view('dashboard.supervisor', compact('pendingUsers'));
    }

    /**
     * Display the global supervisor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function globalDashboard()
    {
        // Fetch all pending users for global supervisors
        $pendingUsers = User::where('status', 'P')->get();

        return view('dashboard.global_supervisor', compact('pendingUsers'));
    }
}
