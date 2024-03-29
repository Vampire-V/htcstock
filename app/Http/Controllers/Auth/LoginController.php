<?php

namespace App\Http\Controllers\Auth;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Service\UserApproveService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $evaluateService;
    protected $userService;
    protected $userApproveService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EvaluateServiceInterface $evaluateServiceInterface, UserServiceInterface $userServiceInterface, UserApproveService $userApproveService)
    {
        $this->middleware('guest')->except('logout');
        $this->evaluateService = $evaluateServiceInterface;
        $this->userService = $userServiceInterface;
        $this->userApproveService = $userApproveService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'username';
    }
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function login(Request $request)
    {
        $input = $request->except(['_token']);
        $this->validate($request, [
            'username'    => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where([$fieldType => $input['username']])->first();
        if (!$user) {
            $errors = new MessageBag(['username' => [$fieldType . " invalid. (ไม่มีในระบบ)"]]);
            return \redirect()->back()->withErrors($errors)->withInput($input);
        }
        $credentials = array($fieldType => $input['username'], 'password' => $input['password']);
        if (Auth::attempt($credentials)) {
            return \redirect()->route('welcome')->with('alert-success', 'You are now logged in.');
        }

        $errors = new MessageBag(['password' => ['Password invalid. (รหัสผ่านไม่ถูกต้อง)']]);
        return \redirect()->back()->withErrors($errors)->withInput($input);
    }

    public function authenticatedLegalById($id, $contract)
    {
        Auth::loginUsingId($id);
        return \redirect()->route('legal.contract-request.show', $contract);
    }

    public function authKpiEvaluation($id)
    {
        try {
            $evaluate = $this->evaluateService->find($id);
            return \redirect()->route('kpi.dashboard');
        } catch (\Exception $e) {
            throw $e;
        }

        // switch ($evaluate->status) {
        //     case KPIEnum::ready:
        //         $user = $evaluate->user;
        //         Auth::login($user);
        //         return \redirect()->route('kpi.self-evaluation.edit', $evaluate->id);
        //         break;
        //     case KPIEnum::draft:
        //         $user = $evaluate->user;
        //         Auth::login($user);
        //         return \redirect()->route('kpi.self-evaluation.edit', $evaluate->id);
        //         break;
        //     case KPIEnum::on_process:
        //         $current_lv = $this->userApproveService->findCurrentLevel($evaluate);
        //         Auth::login($current_lv->approveBy);
        //         return \redirect()->route('kpi.evaluation-review.edit', $evaluate->id);
        //         break;
        //     case KPIEnum::approved:
        //         $user = $evaluate->user;
        //         Auth::login($user);
        //         return \redirect()->route('kpi.self-evaluation.edit', $evaluate->id);
        //         break;
        //     default:
        //         return abort(404, 'ไม่พบข้อมูล ติดต่อ Operation');
        //         break;
        // }
    }
}
