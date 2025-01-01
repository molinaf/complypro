<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\UserAuthorisation;
use App\Models\UserAuthRequisite;
use App\Models\Authorisation;
use App\Models\User;
use App\Models\Company;
use App\Models\ModuleAuthorisation;
use App\Models\F2FAuthorisation;
use App\Models\InductionAuthorisation;
use App\Models\LicenseAuthorisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthorisationController extends Controller
{
    public function registrationPage()
    {
        $user = Auth::user();

        $users = [];
        $companies = null;

        if ($user->role === 'S') {
            // Supervisors see users in their company
            $users = User::where('company_id', $user->company_id)->get();
        } elseif (in_array($user->role, ['A', 'G'])) {
            // Admins and Global Supervisors see all companies
            $companies = Company::all();
        }

        // Fetch authorisations grouped by category
        $authorisations = Authorisation::with('category')->get()->groupBy('category.name');

        return view('authorisations.registration', compact('users', 'companies', 'authorisations'));
    }

    public function viewUserDetails(Request $request)
    {
        $user = Auth::user();
        $companies = null;
        $users = null;
        $selectedCompany = $request->get('company_id');
        $selectedUser = $request->get('user_id');

        if ($user->role === 'U') {
            // Users can only view their own data
            $selectedUser = $user->id;
        } elseif ($user->role === 'S') {
            // Supervisors can view any user within their company
            $users = User::where('company_id', $user->company_id)->get();
        } elseif (in_array($user->role, ['A', 'G', 'M', 'H'])) {
            // Admins, Global Supervisors, Managers, and HSE Managers can select a company and view users in it
            $companies = Company::all();
            if ($selectedCompany) {
                $users = User::where('company_id', $selectedCompany)->get();
            }
        }

        // Initialize variables
        $selectedUserDetails = null;
        $applications = [];

        if ($selectedUser) {
            $selectedUserDetails = User::find($selectedUser);

            // Fetch applications and include user authorisations and prerequisites
            $applications = Application::with([
                'userAuthorisations.authorisation',
                'userAuthorisations.prerequisites.module',
                'userAuthorisations.prerequisites.f2f',
                'userAuthorisations.prerequisites.induction',
                'userAuthorisations.prerequisites.license',
            ])
            ->where('user_id', $selectedUser)
            ->get();
        }

        return view('authorisations.view', [
            'user' => $user,
            'companies' => $companies,
            'users' => $users,
            'selectedCompany' => $selectedCompany,
            'selectedUserDetails' => $selectedUserDetails,
            'applications' => $applications,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'authorisations' => 'required|array',
        ]);
    
        try {
            DB::transaction(function () use ($request) {
                // Create an application record
                $application = Application::create([
                    'endorser_id' => Auth::id(),
                    'user_id' => $request->user_id,
                    'endorsement_date' => now(),
                    'status' => 'P',
                ]);
    
                foreach ($request->authorisations as $authId) {
                    // Create User Authorisation linked to the application
                    UserAuthorisation::create([
                        'application_id' => $application->id, // Correctly linking to application_id
                        'authorisation_id' => $authId,
                        'status' => 'P', // Pending by default
                    ]);
    
                    // Process prerequisites for the selected authorisation
                    $this->processPrerequisites($application->id, $authId); // Correctly pass application ID
                }
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    
        return redirect()->route('authorisations.register')->with('success', 'Application created successfully!');
    }
    
    
    
    /**
     * Fetch grouped prerequisites for a user.
     */
    protected function getGroupedPrerequisites($userId)
    {
        // Fetch prerequisites by joining user_authorisations with applications
        $prerequisites = UserAuthRequisite::whereHas('userAuthorisation.application', function ($query) use ($userId) {
            $query->where('user_id', $userId); // Use applications.user_id instead of user_authorisations.user_id
        })->with([
            'module' => function ($query) {
                $query->select('id', 'name');
            },
            'f2f' => function ($query) {
                $query->select('id', 'name');
            },
            'induction' => function ($query) {
                $query->select('id', 'name');
            },
            'license' => function ($query) {
                $query->select('id', 'name');
            },
        ])->get();

        // Remove duplicates and group by type
        return $prerequisites->unique(function ($item) {
            return $item->type . $item->reference_id;
        })->groupBy('type');
    }

    


    /**
     * Process prerequisites for an authorisation and create requisite records.
     */
    protected function processPrerequisites($applicationId, $authorisationId)
    {
        // Fetch the user_authorisation ID associated with the application and authorisation
        $userAuthorisation = UserAuthorisation::where('application_id', $applicationId)
            ->where('authorisation_id', $authorisationId)
            ->firstOrFail();

        $userAuthId = $userAuthorisation->id;
        if (!$userAuthorisation) {
            throw new \Exception("UserAuthorisation record not found for application ID: $applicationId and authorisation ID: $authorisationId");
        }

        $userAuthId = $userAuthorisation->id;

        // Modules
        $modulePrerequisites = ModuleAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($modulePrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 1, // Type for modules
                'reference_id' => $prerequisite->module_id,
                'status' => 'P', // Pending by default
            ]);
        }

        // F2Fs
        $f2fPrerequisites = F2FAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($f2fPrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 2, // Type for F2Fs
                'reference_id' => $prerequisite->f2f_id,
                'status' => 'P', // Pending by default
            ]);
        }

        // Inductions
        $inductionPrerequisites = InductionAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($inductionPrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 3, // Type for inductions
                'reference_id' => $prerequisite->induction_id,
                'status' => 'P', // Pending by default
            ]);
        }

        // Licenses
        $licensePrerequisites = LicenseAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($licensePrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 4, // Type for licenses
                'reference_id' => $prerequisite->license_id,
                'status' => 'P', // Pending by default
            ]);
        }
    }

    public function relatedItems($id)
    {
        // Fetch the specific authorisation by ID
        $authorisation = Authorisation::findOrFail($id);

        // Fetch related items
        $relatedItems = [
            'modules' => $authorisation->modules, // Assuming relationship exists
            'f2fs' => $authorisation->f2fs,
            'inductions' => $authorisation->inductions,
            'licenses' => $authorisation->licenses,
        ];
        // Return only the view fragment for related items
        return view('dynamic.related_items', compact('relatedItems', 'authorisation'))->render();
    }


}
