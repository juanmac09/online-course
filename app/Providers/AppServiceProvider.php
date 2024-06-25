<?php

namespace App\Providers;

use App\Interfaces\Repository\Comments\ICommentsReadRepository;
use App\Interfaces\Repository\Comments\ICommentsRepository;
use App\Interfaces\Repository\Comments\ICommentsWriteRepository;
use App\Interfaces\Repository\Comments\IReportCommentsRepository;
use App\Interfaces\Repository\Content\IContentReadRepository;
use App\Interfaces\Repository\Content\IContentRepository;
use App\Interfaces\Repository\Content\IContentWriteRepository;
use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyReadRepository;
use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyWriteRepository;
use App\Interfaces\Repository\ContentAdvanced\ISearchContentRespository;
use App\Interfaces\Repository\ContentAdvanced\IStatusContentReadRepository;
use App\Interfaces\Repository\ContentAdvanced\IStatusContentWriteRepository;
use App\Interfaces\Repository\Course\ICourseReadRepository;
use App\Interfaces\Repository\Course\ICourseRepository;
use App\Interfaces\Repository\Course\ICourseWriteReportory;
use App\Interfaces\Repository\CourseAdvanced\IAdvancedCourseRepository;
use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyReadRepository;
use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyWriteRepository;
use App\Interfaces\Repository\CourseAdvanced\ICourseStatusReadRepository;
use App\Interfaces\Repository\CourseAdvanced\ICourseStatusWriteRepository;
use App\Interfaces\Repository\CourseAdvanced\ISearchPublicCourseRepository;
use App\Interfaces\Repository\CourseAdvanced\IUploadedCourseRepository;
use App\Interfaces\Repository\Enrollments\IEnrollmentsWriteRespository;
use App\Interfaces\Repository\Enrollments\ISearchEnrollmentsRepository;
use App\Interfaces\Repository\Permission\IPermissionVerificationRepository;
use App\Interfaces\Repository\Qualification\IQualificationReadRepository;
use App\Interfaces\Repository\Qualification\IQualificationWriteRepository;
use App\Interfaces\Repository\Role\IManagingRoleUserWriteRepository;
use App\Interfaces\Repository\Role\IRoleReadRepository;
use App\Interfaces\Repository\Role\IRoleWriteRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Repository\User\IUserReadRepository;
use App\Interfaces\Repository\User\IUserWriteRepository;
use App\Interfaces\Service\Auth\IAuth;
use App\Interfaces\Service\Comments\ICommentsReadService;
use App\Interfaces\Service\Comments\ICommentsWriteService;
use App\Interfaces\Service\Comments\IReportCommentsService;
use App\Interfaces\Service\Content\IContentReadService;
use App\Interfaces\Service\Content\IContentWriteService;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyReadService;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyWriteService;
use App\Interfaces\Service\ContentAdvanced\ISearchContentService;
use App\Interfaces\Service\ContentAdvanced\IStatusContentReadService;
use App\Interfaces\Service\ContentAdvanced\IStatusContentWriteService;
use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Interfaces\Service\Course\ICourseReadService;
use App\Interfaces\Service\Course\ICourseWriteService;
use App\Interfaces\Service\CourseAdvanced\IAdvancedCourseService;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateReadService;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateWriteService;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicReadService;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicWriteService;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusReadService;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusWriteService;
use App\Interfaces\Service\CourseAdvanced\ISearchPublicCourseService;
use App\Interfaces\Service\CourseAdvanced\IUploadedCourseService;
use App\Interfaces\Service\Enrollments\IEnrollmentsWriteService;
use App\Interfaces\Service\Enrollments\ISearchEnrollmentsService;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Interfaces\Service\Qualification\IQualificationWriteService;
use App\Interfaces\Service\Role\IManagingRoleUserWriteService;
use App\Interfaces\Service\Role\IRoleReadService;
use App\Interfaces\Service\Role\IRoleWriteService;
use App\Interfaces\Service\User\IUserReadService;
use App\Interfaces\Service\User\IUserWriteService;
use App\Repository\Comments\CommentsReadRepository;
use App\Repository\Comments\CommentsRepository;
use App\Repository\Comments\CommentsWriteRepository;
use App\Repository\Comments\ReportCommentsRepository;
use App\Repository\Content\ContentReadRepository;
use App\Repository\Content\ContentRepository;
use App\Repository\Content\ContentWriteRepository;
use App\Repository\ContentAdvanced\ContentPrivacyReadRepository;
use App\Repository\ContentAdvanced\ContentPrivacyWriteRepository;
use App\Repository\ContentAdvanced\SearchContentRespository;
use App\Repository\ContentAdvanced\StatusContentReadRepository;
use App\Repository\ContentAdvanced\StatusContentWriteRepository;
use App\Repository\Course\CourseReadRepository;
use App\Repository\Course\CourseRepository;
use App\Repository\Course\CourseWriteReportory;
use App\Repository\CourseAdvanced\AdvancedCourseRepository;
use App\Repository\CourseAdvanced\CoursePrivacyReadRepository;
use App\Repository\CourseAdvanced\CoursePrivacyWriteRepository;
use App\Repository\CourseAdvanced\CourseStatusReadRepository;
use App\Repository\CourseAdvanced\CourseStatusWriteRepository;
use App\Repository\CourseAdvanced\SearchPublicCourseRepository;
use App\Repository\CourseAdvanced\UploadedCourseRepository;
use App\Repository\Enrollments\EnrollmentsWriteRespository;
use App\Repository\Enrollments\SearchEnrollmentsRepository;
use App\Repository\Permission\PermissionVerificationRepository;
use App\Repository\Qualification\QualificationReadRepository;
use App\Repository\Qualification\QualificationWriteRepository;
use App\Repository\Role\ManagingRoleUserWriteRepository;
use App\Repository\Role\RoleReadRepository;
use App\Repository\Role\RoleWriteRepository;
use App\Repository\User\UserProfileRepository;
use App\Repository\User\UserReadRepository;
use App\Repository\User\UserWriteRepository;
use App\Services\Auth\AuthService;
use App\Services\Comments\CommentsReadService;
use App\Services\Comments\CommentsWriteService;
use App\Services\Comments\ReportCommentsService;
use App\Services\Content\ContentReadService;
use App\Services\Content\ContentWriteService;
use App\Services\ContentAdvanced\ContentPrivacyReadService;
use App\Services\ContentAdvanced\ContentPrivacyWriteService;
use App\Services\ContentAdvanced\SearchContentService;
use App\Services\ContentAdvanced\StatusContentReadService;
use App\Services\ContentAdvanced\StatusContentWriteService;
use App\Services\Course\CourseOwnerService;
use App\Services\Course\CourseReadService;
use App\Services\Course\CourseWriteService;
use App\Services\CourseAdvanced\AdvancedCourseService;
use App\Services\CourseAdvanced\CoursePrivateReadService;
use App\Services\CourseAdvanced\CoursePrivateWriteService;
use App\Services\CourseAdvanced\CoursePublicReadService;
use App\Services\CourseAdvanced\CoursePublicWriteService;
use App\Services\CourseAdvanced\CourseStatusReadService;
use App\Services\CourseAdvanced\CourseStatusWriteService;
use App\Services\CourseAdvanced\SearchPublicCourseService;
use App\Services\CourseAdvanced\UploadedCourseService;
use App\Services\Enrollments\EnrollmentsWriteService;
use App\Services\Enrollments\SearchEnrollmentsService;
use App\Services\Pagination\PaginationService;
use App\Services\Permission\PermissionVerificationService;
use App\Services\Qualification\QualificationReadService;
use App\Services\Qualification\QualificationWriteService;
use App\Services\Role\ManagingRoleUserWriteService;
use App\Services\Role\RoleReadService;
use App\Services\Role\RoleWriteService;
use App\Services\User\UserReadService;
use App\Services\User\UserWriteService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
 

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services
        $this-> app -> bind(IAuth::class,AuthService::class);
        $this -> app -> bind(IRoleWriteService::class,RoleWriteService::class);
        $this -> app -> bind(IRoleReadService::class,RoleReadService::class);
        $this -> app -> bind(IUserWriteService::class, UserWriteService::class);
        $this -> app -> bind(IUserReadService::class, UserReadService::class);
        $this -> app -> bind(ICourseWriteService::class,CourseWriteService::class);
        $this -> app -> bind(ICourseReadService::class,CourseReadService::class);
        $this -> app -> bind(IContentWriteService::class,ContentWriteService::class);
        $this -> app -> bind(IContentReadService::class,ContentReadService::class);
        $this -> app -> bind(IEnrollmentsWriteService::class,EnrollmentsWriteService::class);
        $this -> app -> bind(IAdvancedCourseService::class,AdvancedCourseService::class);
        $this -> app -> bind(IUploadedCourseService::class,UploadedCourseService::class);
        $this -> app -> bind(ICoursePublicWriteService::class,CoursePublicWriteService::class);
        $this -> app -> bind(ICoursePublicReadService::class,CoursePublicReadService::class);
        $this -> app -> bind(ICoursePrivateWriteService::class,CoursePrivateWriteService::class);
        $this -> app -> bind(ICoursePrivateReadService::class,CoursePrivateReadService::class);
        $this -> app -> bind(ICourseStatusWriteService::class,CourseStatusWriteService::class);
        $this -> app -> bind(ICourseStatusReadService::class,CourseStatusReadService::class);
        $this -> app -> bind(ISearchPublicCourseService::class,SearchPublicCourseService::class); 
        $this -> app -> bind(IContentPrivacyReadService::class,ContentPrivacyReadService::class);
        $this -> app -> bind(IContentPrivacyWriteService::class,ContentPrivacyWriteService::class);  
        $this -> app -> bind(IStatusContentReadService::class,StatusContentReadService::class); 
        $this -> app -> bind(IStatusContentWriteService::class,StatusContentWriteService::class); 
        $this -> app -> bind(ISearchContentService::class,SearchContentService::class); 
        $this -> app -> bind(ISearchEnrollmentsService::class,SearchEnrollmentsService::class);
        $this -> app -> bind(ICommentsReadService::class,CommentsReadService::class);
        $this -> app -> bind(ICommentsWriteService::class,CommentsWriteService::class);
        $this -> app -> bind(IReportCommentsService::class,ReportCommentsService::class);
        $this -> app -> bind(IQualificationWriteService::class,QualificationWriteService::class);
        $this -> app -> bind(IPaginationService::class,PaginationService::class);
        $this -> app -> bind(IQualificationReadService::class,QualificationReadService::class);
        $this -> app -> bind(IManagingRoleUserWriteService::class,ManagingRoleUserWriteService::class);
        $this -> app -> bind(IPermissionVerificationService::class,PermissionVerificationService::class);
        $this -> app -> bind(ICourseOwnerService::class,CourseOwnerService::class);

        // Repositories
        $this -> app -> bind(IRoleWriteRepository::class,RoleWriteRepository::class);
        $this -> app -> bind(IRoleReadRepository::class,RoleReadRepository::class);
        $this -> app -> bind(IUserWriteRepository::class,UserWriteRepository::class);
        $this -> app -> bind(IUserReadRepository::class,UserReadRepository::class);
        $this -> app -> bind(ICourseWriteReportory::class,CourseWriteReportory::class);
        $this -> app -> bind(ICourseReadRepository::class,CourseReadRepository::class);  
        $this -> app -> bind(IContentWriteRepository::class,ContentWriteRepository::class);
        $this -> app -> bind(IContentReadRepository::class,ContentReadRepository::class);
        $this -> app -> bind(IEnrollmentsWriteRespository::class,EnrollmentsWriteRespository::class);
        $this -> app -> bind(IAdvancedCourseRepository::class,AdvancedCourseRepository::class);
        $this -> app -> bind(IUserProfileRepository::class,UserProfileRepository::class);
        $this -> app -> bind(IUploadedCourseRepository::class,UploadedCourseRepository::class);
        $this -> app -> bind(ICoursePrivacyReadRepository::class,CoursePrivacyReadRepository::class);
        $this -> app -> bind(ICoursePrivacyWriteRepository::class,CoursePrivacyWriteRepository::class);
        $this -> app -> bind(ICourseStatusReadRepository::class,CourseStatusReadRepository::class);
        $this -> app -> bind(ICourseStatusWriteRepository::class,CourseStatusWriteRepository::class);
        $this -> app -> bind(ISearchPublicCourseRepository::class,SearchPublicCourseRepository::class); 
        $this -> app -> bind(IContentPrivacyReadRepository::class,ContentPrivacyReadRepository::class); 
        $this -> app -> bind(IContentPrivacyWriteRepository::class,ContentPrivacyWriteRepository::class); 
        $this -> app -> bind(IStatusContentReadRepository::class,StatusContentReadRepository::class); 
        $this -> app -> bind(IStatusContentWriteRepository::class,StatusContentWriteRepository::class);
        $this -> app -> bind(ISearchContentRespository::class,SearchContentRespository::class); 
        $this -> app -> bind(ISearchEnrollmentsRepository::class,SearchEnrollmentsRepository::class);
        $this -> app -> bind(ICommentsReadRepository::class,CommentsReadRepository::class);
        $this -> app -> bind(ICommentsWriteRepository::class,CommentsWriteRepository::class);
        $this -> app -> bind(IReportCommentsRepository::class,ReportCommentsRepository::class);
        $this -> app -> bind(IQualificationWriteRepository::class,QualificationWriteRepository::class);
        $this -> app -> bind(IQualificationReadRepository::class,QualificationReadRepository::class);
        $this -> app -> bind(IManagingRoleUserWriteRepository::class,ManagingRoleUserWriteRepository::class);
        $this -> app -> bind(IPermissionVerificationRepository::class,PermissionVerificationRepository::class);
        $this -> app -> bind(ICourseRepository::class,CourseRepository::class);
        $this -> app -> bind(IContentRepository::class,ContentRepository::class);
        $this -> app -> bind(ICommentsRepository::class,CommentsRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
