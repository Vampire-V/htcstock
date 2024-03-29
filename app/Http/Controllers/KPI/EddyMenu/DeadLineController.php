<?php

namespace App\Http\Controllers\KPI\EddyMenu;

use App\Http\Controllers\Controller;
use App\Services\KPI\Service\SettingActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeadLineController extends Controller
{
    protected $setting_action;
    public function __construct(SettingActionService $setting)
    {
        $this->setting_action = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \view('kpi.Eddy.setting');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deadline()
    {
        try {
            $deadlines = $this->setting_action->dropdown();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($deadlines, 'Get dead line', 200);
    }

    public function setting_action_user($id)
    {
        try {
            $action = $this->setting_action->find($id);
            $users = $action->users;
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($users, 'Get users setting for ' . $action->slug, 200);
    }

    public function attach_authorization(Request $request, $action)
    {
        $user = $request->user;
        DB::beginTransaction();
        try {
            $setting = $this->setting_action->find($action);
            $setting->users()->attach($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(\true, 'attach user to next action', 200);
    }

    public function detach_authorization(Request $request, $action)
    {
        $user = $request->user;
        DB::beginTransaction();
        try {
            $setting = $this->setting_action->find($action);
            $setting->users()->detach($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(\true, 'detach user to next action', 200);
    }

    public function update_endday(Request $request, $action)
    {
        DB::beginTransaction();
        try {
            $setting = $this->setting_action->find($action);
            $setting->end = $request->end;
            $setting->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(\true, 'update day success...', 200);
    }
}
