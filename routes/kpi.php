<?php

use App\Http\Controllers\KPI\DepartmentInChargeController;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// KPI
Route::get('kpi/evaluation/{id}/verify', 'Auth\LoginController@authKpiEvaluation')->name('kpi.evaluation.verify');

Route::get('/operations','Admin\UsersController@operations');
Route::get('users/dropdown','Admin\UsersController@dropdown')->name('users.dropdown');
Route::post('user/{id}/kpihided','Admin\UsersController@kpiHides');
Route::get('config/users/dropdown','Admin\UsersController@dropdown_config');
Route::get('divisions/dropdown','DivisionController@dropdown')->name('divisions.dropdown');
Route::get('category/dropdown','KPI\CategoryController@dropdown');

Route::namespace('KPI')->prefix('kpi')->name('kpi.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    Route::get('operation/reportscore', 'HomeController@reportscore');
    Route::get('weigth/config', 'HomeController@weigthconfig');
    Route::get('dashboard/you-self/{year}/report', 'HomeController@report_your_self');
    Route::get('dashboard/rule-of-year/{year}/report', 'HomeController@report_rule_of_year');
    Route::get('dashboard/staff-evaluate-of-year/{year}/report', 'HomeController@report_staff_evaluate_year');
    Route::put('evaluate/update/target-actual', 'HomeController@changetargetactual');
    // changetargetactual


    Route::resource('self-evaluation', 'SelfEvaluation\SelfEvaluationController', ['only' => ['index', 'create', 'edit', 'update', 'store', 'destroy']]);
    Route::get('evaluation/{id}/excel','SelfEvaluation\SelfEvaluationController@evaluateExcel')->name('self.download');
    Route::get('evaluation/user/{user}/quarter/{quarter}/year/{year}/excel','SelfEvaluation\SelfEvaluationController@evaluateQuarterExcel')->name('selfquarter.download');
    Route::get('evaluation/user/{user}/year/{year}/excel','SelfEvaluation\SelfEvaluationController@evaluateYearExcel')->name('selfyear.download');
        // new
        // Route::get('self-evaluation/evaluate', 'SelfEvaluation\SelfEvaluationController@create_new')->name('evaluate.create_new');
    Route::get('self-evaluation/{user}/score/', 'SelfEvaluation\SelfEvaluationController@score_many_month')->name('evaluate.score_many_month');
    Route::get('self-evaluation/user/{user}/quarter/{quarter}/year/{year}', 'SelfEvaluation\SelfEvaluationController@display_quarter')->name('quarter');
    Route::get('self-evaluation/user/{user}/year/{year}', 'SelfEvaluation\SelfEvaluationController@display_all_quarter')->name('allquarter');
    Route::get('self-evaluation/detailbyids','SelfEvaluation\SelfEvaluationController@detailbyids');

    Route::get('evaluate-report', 'SelfEvaluation\SelfEvaluationController@excelevaluate');

    Route::resource('evaluation-review', 'EvaluateReview\EvaluateReviewController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    // forceApprove
    Route::post('evaluation-review/force','EvaluateReview\EvaluateReviewController@forceApprove');
    Route::put('evaluation-review/{id}/evaluateEdit','EvaluateReview\EvaluateReviewController@evaluateEdit');
    Route::get('errors/evaluation-review','EvaluateReview\EvaluateReviewController@evaluateReviewErrors');

    Route::resource('rule-list', 'Rule\RuleController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::get('rule-dropdown/{group}', 'Rule\RuleController@dropdown')->name('rule-dropdown');
    Route::post('rule-list/upload', 'Rule\RuleController@upload');
    Route::post('rule-list/import', 'Rule\RuleController@import_rule');
    Route::get('rules/download', 'Rule\RuleController@rulesdowload')->name('rules.export');
    Route::post('rules/notin', 'Rule\RuleController@rulesnotin');
    Route::put('rule/put/evaluate','Rule\RuleController@switchrule');
    Route::post('rule/post/evaluate','Rule\RuleController@addrulenew');

    Route::get('rules/template/download', 'Rule\RuleController@template_rule')->name('rules.download-template');


    Route::resource('template', 'Template\TemplateController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::post('template/dynamic', 'Template\TemplateController@store_dynamic');
    Route::put('template/{id}/dynamic', 'Template\TemplateController@update_dynamic');
    Route::put('template/{id}/rename', 'Template\TemplateController@rename');
    Route::put('template/{id}/transfer', 'Template\TemplateController@transferToUser');
    Route::group(['prefix' => 'template/{template}/edit'], function () {
        Route::resource('rule-template', 'RuleTemplate\RuleTemplateController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::get('ruletemplate/bytemplate', 'RuleTemplate\RuleTemplateController@bytemplate')->name('rule-template.list');
        Route::put('ruletemplate/switch', 'RuleTemplate\RuleTemplateController@switchrow')->name('rule-template.switch');
        Route::delete('ruletemplate/destroy', 'RuleTemplate\RuleTemplateController@deleteRuleTemplate')->name('rule-template.destroy');
    });

    Route::group(['prefix' => 'evaluation-form'], function () {
        Route::resource('staff', 'EvaluationForm\StaffDataController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
        Route::group(['prefix' => 'staff/{staff}/edit'], function () {
            Route::put('period/{period}/evaluate/{evaluate}/pullback','EvaluationForm\EvaluationFormController@pullback')->name('evaluate.pullback');
            Route::resource('period/{period}/evaluate', 'EvaluationForm\EvaluationFormController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
        });
    });

    Route::resource('set-actual', 'SetActual\SetActualController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::put('send-mail/','SetActual\SetActualController@sendemail');
    Route::resource('for-eddy', 'EddyMenu\AllEvaluationController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    // Route::get('for-eddy/rulesready', 'EddyMenu\AllEvaluationController@rulesready')->name('rulesready');
    Route::group(['prefix' => 'for-eddy','as'=>'for-eddy.'], function () {

        Route::get('config/deadline', 'EddyMenu\DeadLineController@index')->name('deadline');
        Route::get('rules/ready', 'EddyMenu\AllEvaluationController@rules_ready')->name('rulesready');
        Route::get('config/deadline/dropdown', 'EddyMenu\DeadLineController@deadline');
        Route::get('user/actions/{id}', 'EddyMenu\DeadLineController@setting_action_user');
        Route::post('attach/action/{action}', 'EddyMenu\DeadLineController@attach_authorization');
        Route::post('detach/action/{action}', 'EddyMenu\DeadLineController@detach_authorization');
        Route::post('update/action/{action}', 'EddyMenu\DeadLineController@update_endday');

        Route::get('user/evaluates', 'EddyMenu\AllEvaluationController@user_evaluates')->name('user_evaluates');

    });

    Route::resource('set-period', 'SetPeriod\TargetPeriodController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::get('generate-month', 'SetPeriod\TargetPeriodController@generateMonth')->name('generate-month.auto');
    Route::resource('transfer-rules','TransferRules\AdminRuleController', ['only' => ['index']]);
    Route::put('transfer-rules','TransferRules\AdminRuleController@update')->name('transfer-rules.transfer');

    Route::get('department-in-charge',[DepartmentInChargeController::class,'index'])->name('dept-in-charge');
    Route::post('department-in-charge',[DepartmentInChargeController::class,'store']);
    Route::delete('department-in-charge/{id}', [DepartmentInChargeController::class,'destroy']);

});
