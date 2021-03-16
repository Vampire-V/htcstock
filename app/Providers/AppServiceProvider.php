<?php

namespace App\Providers;

use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\BudgetServiceInterface;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PermissionsServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Services\IT\Interfaces\SystemServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\IT\Interfaces\VendorServiceInterface;
use App\Services\IT\Service\AccessoriesService;
use App\Services\IT\Service\BudgetService;
use App\Services\IT\Service\DepartmentService;
use App\Services\IT\Service\DivisionService;
use App\Services\IT\Service\PermissionsService;
use App\Services\IT\Service\PositionService;
use App\Services\IT\Service\RoleService;
use App\Services\IT\Service\SystemService;
use App\Services\IT\Service\TransactionsService;
use App\Services\IT\Service\UserService;
use App\Services\IT\Service\VendorService;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Interfaces\TargetUnitServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\EvaluateDetailService;
use App\Services\KPI\Service\EvaluateService;
use App\Services\KPI\Service\RuleCategoryService;
use App\Services\KPI\Service\RuleService;
use App\Services\KPI\Service\RuleTemplateService;
use App\Services\KPI\Service\TargetPeriodService;
use App\Services\KPI\Service\TargetUnitService;
use App\Services\KPI\Service\TemplateService;
use App\Services\Legal\Interfaces\ActionServiceInterface;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use App\Services\Legal\Interfaces\ApprovalDetailServiceInterface;
use App\Services\Legal\Interfaces\ApprovalServiceInterface;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Services\Legal\Interfaces\PaymentTermServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Legal\Interfaces\SubtypeContractServiceInterface;
use App\Services\Legal\Service\ActionService;
use App\Services\Legal\Service\AgreementService;
use App\Services\Legal\Service\ApprovalDetailService;
use App\Services\Legal\Service\ApprovalService;
use App\Services\Legal\Service\ComercialListsService;
use App\Services\Legal\Service\ComercialTermService;
use App\Services\Legal\Service\ContractDescService;
use App\Services\Legal\Service\ContractRequestService;
use App\Services\Legal\Service\PaymentTermService;
use App\Services\Legal\Service\PaymentTypeService;
use App\Services\Legal\Service\SubtypeContractService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // JsonResource::withoutWrapping();
        // IT
        $this->app->bind(AccessoriesServiceInterface::class, AccessoriesService::class);
        $this->app->bind(TransactionsServiceInterface::class, TransactionsService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(BudgetServiceInterface::class, BudgetService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(PermissionsServiceInterface::class, PermissionsService::class);
        $this->app->bind(SystemServiceInterface::class,SystemService::class);
        $this->app->bind(DivisionServiceInterface::class,DivisionService::class);
        $this->app->bind(PositionServiceInterface::class,PositionService::class);
        $this->app->bind(VendorServiceInterface::class,VendorService::class);

        // Legal
        $this->app->bind(ActionServiceInterface::class, ActionService::class);
        $this->app->bind(AgreementServiceInterface::class, AgreementService::class);
        $this->app->bind(ContractRequestServiceInterface::class,ContractRequestService::class);
        $this->app->bind(ContractDescServiceInterface::class,ContractDescService::class);
        $this->app->bind(PaymentTypeServiceInterface::class,PaymentTypeService::class);
        $this->app->bind(ComercialListsServiceInterface::class,ComercialListsService::class);
        $this->app->bind(ComercialTermServiceInterface::class,ComercialTermService::class);
        $this->app->bind(SubtypeContractServiceInterface::class,SubtypeContractService::class);
        $this->app->bind(PaymentTermServiceInterface::class,PaymentTermService::class);
        $this->app->bind(ApprovalServiceInterface::class,ApprovalService::class);
        $this->app->bind(DepartmentServiceInterface::class,DepartmentService::class);
        $this->app->bind(ApprovalDetailServiceInterface::class,ApprovalDetailService::class);

        // KPI
        $this->app->bind(RuleCategoryServiceInterface::class,RuleCategoryService::class);
        $this->app->bind(TargetUnitServiceInterface::class,TargetUnitService::class);
        $this->app->bind(RuleServiceInterface::class,RuleService::class);
        $this->app->bind(RuleTemplateServiceInterface::class,RuleTemplateService::class);
        $this->app->bind(TemplateServiceInterface::class,TemplateService::class);
        $this->app->bind(EvaluateServiceInterface::class,EvaluateService::class);
        $this->app->bind(TargetPeriodServiceInterface::class,TargetPeriodService::class);
        $this->app->bind(EvaluateDetailServiceInterface::class,EvaluateDetailService::class);
        
    }
}
