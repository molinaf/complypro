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
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthorisationNotification;

class AuthorisationController extends Controller
{
    public function registrationPage()
    {
        $user = Auth::user();
        $users = [];
        $companies = null;

        if ($user->role === 'S') {
            $users = User::where('company_id', $user->company_id)->get();
        } elseif (in_array($user->role, ['A', 'G'])) {
            $companies = Company::all();
        }

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
            $selectedUser = $user->id;
        } elseif ($user->role === 'S') {
            $users = User::where('company_id', $user->company_id)->get();
        } elseif (in_array($user->role, ['A', 'G', 'M', 'H'])) {
            $companies = Company::all();
            if ($selectedCompany) {
                $users = User::where('company_id', $selectedCompany)->get();
            }
        }

        $selectedUserDetails = null;
        $applications = [];

        if ($selectedUser) {
            $selectedUserDetails = User::find($selectedUser);
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
        $theView = 'authorisations.view';
        if ($request->get('for_email')) {
            $theView = 'emails.authorisation_notification';
        }
        return view($theView, [
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
                $application = Application::create([
                    'endorser_id' => Auth::id(),
                    'user_id' => $request->user_id,
                    'endorsement_date' => now(),
                    'status' => 'P',
                ]);

                $authorisations = [];
                foreach ($request->authorisations as $authId) {
                    $authorisation = Authorisation::findOrFail($authId);
                    $authorisations[] = $authorisation;

                    UserAuthorisation::create([
                        'application_id' => $application->id,
                        'authorisation_id' => $authId,
                        'status' => 'P',
                    ]);

                    $this->processPrerequisites($application->id, $authId);
                }

                $user = User::findOrFail($request->user_id);

                // Send email notification
                Mail::to($user->email)->send(new AuthorisationNotification($user, $authorisations));

            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('authorisations.register')->with('success', 'Application created successfully, and email notification sent!');
    }

    protected function getGroupedPrerequisites($userId)
    {
        $prerequisites = UserAuthRequisite::whereHas('userAuthorisation.application', function ($query) use ($userId) {
            $query->where('user_id', $userId);
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

        return $prerequisites->unique(function ($item) {
            return $item->type . $item->reference_id;
        })->groupBy('type');
    }

    protected function processPrerequisites($applicationId, $authorisationId)
    {
        $userAuthorisation = UserAuthorisation::where('application_id', $applicationId)
            ->where('authorisation_id', $authorisationId)
            ->firstOrFail();

        $userAuthId = $userAuthorisation->id;

        $modulePrerequisites = ModuleAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($modulePrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 1,
                'reference_id' => $prerequisite->module_id,
                'status' => 'P',
            ]);
        }

        $f2fPrerequisites = F2FAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($f2fPrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 2,
                'reference_id' => $prerequisite->f2f_id,
                'status' => 'P',
            ]);
        }

        $inductionPrerequisites = InductionAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($inductionPrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 3,
                'reference_id' => $prerequisite->induction_id,
                'status' => 'P',
            ]);
        }

        $licensePrerequisites = LicenseAuthorisation::where('authorisation_id', $authorisationId)->get();
        foreach ($licensePrerequisites as $prerequisite) {
            UserAuthRequisite::create([
                'user_authorisation_id' => $userAuthId,
                'type' => 4,
                'reference_id' => $prerequisite->license_id,
                'status' => 'P',
            ]);
        }
    }

    public function relatedItems($id)
    {
        $authorisation = Authorisation::findOrFail($id);

        $relatedItems = [
            'modules' => $authorisation->modules,
            'f2fs' => $authorisation->f2fs,
            'inductions' => $authorisation->inductions,
            'licenses' => $authorisation->licenses,
        ];

        return view('dynamic.related_items', compact('relatedItems', 'authorisation'))->render();
    }
}
