<?php

use Illuminate\Support\Facades\Route;

// KPI
Route::get('kpi/evaluation/{id}/verify', 'Auth\LoginController@authKpiEvaluation')->name('kpi.evaluation.verify');
Route::namespace('KPI')->prefix('kpi')->name('kpi.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('self-evaluation', 'SelfEvaluation\SelfEvaluationController', ['only' => ['index', 'create', 'edit', 'update', 'store', 'destroy']]);

    Route::resource('evaluation-review', 'EvaluateReview\EvaluateReviewController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);

    Route::resource('rule-list', 'Rule\RuleController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::get('rule-dropdown/{group}', 'Rule\RuleController@dropdown')->name('rule-dropdown');
    Route::post('rule-list/upload', 'Rule\RuleController@upload');

    Route::resource('template', 'Template\TemplateController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::group(['prefix' => 'template/{template}/edit'], function () {
        Route::resource('rule-template', 'RuleTemplate\RuleTemplateController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store']]);
        Route::get('ruletemplate/bytemplate', 'RuleTemplate\RuleTemplateController@bytemplate')->name('rule-template.list');
        Route::put('ruletemplate/switch', 'RuleTemplate\RuleTemplateController@switchrow')->name('rule-template.switch');
        Route::delete('ruletemplate/destroy', 'RuleTemplate\RuleTemplateController@deleteRuleTemplate')->name('rule-template.destroy');
    });

    Route::group(['prefix' => 'evaluation-form'], function () {
        Route::resource('staff', 'EvaluationForm\StaffDataController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
        Route::group(['prefix' => 'staff/{staff}/edit'], function () {
            Route::resource('period/{period}/evaluate', 'EvaluationForm\EvaluationFormController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
        });
    });

    Route::resource('set-actual', 'SetActual\SetActualController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::resource('for-eddy', 'EddyMenu\AllEvaluationController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
    Route::put('for-eddy/{id}/updateAch', 'EddyMenu\AllEvaluationController@updateAch');

    Route::resource('set-period', 'SetPeriod\TargetPeriodController', ['only' => ['index', 'create', 'edit', 'show', 'update', 'store', 'destroy']]);
});
