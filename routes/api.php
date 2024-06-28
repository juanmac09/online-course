<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Comments\CommentsReadController;
use App\Http\Controllers\Comments\CommentsWriteController;
use App\Http\Controllers\Comments\ReportCommentsController;
use App\Http\Controllers\Content\ContentManagementController;
use App\Http\Controllers\Content\ContentStatusController;
use App\Http\Controllers\Content\PrivateContentController;
use App\Http\Controllers\Content\SearchContentController;
use App\Http\Controllers\Content\UploadedContentController;
use App\Http\Controllers\Course\CourseAdvancedController;
use App\Http\Controllers\Course\CourseManagementController;
use App\Http\Controllers\Course\CourseStatusController;
use App\Http\Controllers\Course\PrivateCourseController;
use App\Http\Controllers\Course\SearchCoursesController;
use App\Http\Controllers\Course\UploadedCourseController;
use App\Http\Controllers\Enrollements\EnrollementsController;
use App\Http\Controllers\Enrollements\SearchEnrollementsController;
use App\Http\Controllers\Qualification\QualificationWriteController;
use App\Http\Controllers\Role\ManagingRoleUserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Authentication routes
Route::post('auth/login',[AuthController::class,'login']);
Route::post('auth/register',[AuthController::class,'register']);

Route::middleware('auth:api')->group(function() {
    // Role routes
    Route::prefix('role')->group(function() {
        Route::post('create', [RoleController::class, 'createRole']); // Create a role
        Route::get('all', [RoleController::class, 'getAllRoles']); // Get all roles
        Route::put('update', [RoleController::class, 'updateRole']); // Update a role
        Route::put('change-user', [ManagingRoleUserController::class, 'changeRoleToUser']); // Change role to user
    });

    // User routes
    Route::prefix('user')->group(function() {
        Route::post('create', [UserManagementController::class, 'createUser']); // Create a user
        Route::get('all', [UserManagementController::class, 'getAllUser']); // Get all users
        Route::put('update', [UserManagementController::class, 'updateUser']); // Update a user
        Route::put('disable', [UserManagementController::class, 'disableUser']); // Disable a user
    });

    // Course routes
    Route::prefix('course')->group(function() {
        Route::post('create', [CourseManagementController::class, 'createCourse']); // Create a course
        Route::get('all', [CourseManagementController::class, 'getAllCourse']); // Get all courses
        Route::put('update', [CourseManagementController::class, 'updateCourse']); // Update a course
        Route::put('disable', [CourseManagementController::class, 'disableCourse']); // Disable a course

        Route::get('getEnrolledCourses', [CourseAdvancedController::class, 'getEnrolledCourses']); // Get enrolled courses
        Route::get('getCourseUploaded', [UploadedCourseController::class, 'getCoursesUploaded']); // Get uploaded courses by the user
        Route::get('getPublicCourses', [UploadedCourseController::class, 'getPublicCourses']); // Get public courses
        Route::put('makePublicACourse', [UploadedCourseController::class, 'makePublicACourse']); // Make a course public

        Route::get('getPrivateCourses', [PrivateCourseController::class, 'getPrivateCourses']); // Get private courses
        Route::put('makePrivateACourse', [PrivateCourseController::class, 'makePrivateCourse']); // Make a course private

        Route::get('getDesactiveCourses', [CourseStatusController::class, 'getDesactiveCourses']); // Get deactivated courses
        Route::put('changeActiveStatusToCourse', [CourseStatusController::class, 'changeActiveStatusToCourse']); // Change the active status of a course

        Route::get('search', [SearchCoursesController::class, 'findByKeyword']); // Search courses by keyword
        Route::get('getPublicAndActiveCourses', [SearchCoursesController::class, 'getPublicAndActiveCourses']); // Get public and active courses
    });

    // Content routes
    Route::prefix('content')->group(function() {
        Route::post('upload', [ContentManagementController::class, 'uploadContent']); // Upload content
        Route::post('update', [ContentManagementController::class, 'updateContent']); // Update content
        Route::get('course-content', [ContentManagementController::class, 'getContentForCourse']); // Get content for a course
        Route::put('disable', [ContentManagementController::class, 'disableContent']); // Disable content
        Route::put('updateContentOrder', [ContentManagementController::class, 'updateContentOrder']); // Update content order

        Route::get('getPublicContent', [UploadedContentController::class, 'getPublicContent']); // Get public content
        Route::put('makePublicAContent', [UploadedContentController::class, 'makePublicAContent']); // Make a content public

        Route::get('getPrivateContent', [PrivateContentController::class, 'getPrivateContent']); // Get private content
        Route::put('makePrivateAContent', [PrivateContentController::class, 'makePrivateAContent']); // Make a content private

        Route::get('getStatusContent', [ContentStatusController::class, 'getStatusDesactiveContent']); // Get content status
        Route::put('changeStatusContent', [ContentStatusController::class, 'changeStatusActiveContent']); // Change content status

        Route::get('getPublicAndActiveContent', [SearchContentController::class, 'getPublicAndActiveContent']); // Get public and active content
    });

    // Enrollments routes
    Route::prefix('enrollments')->group(function() {
        Route::post('create', [EnrollementsController::class, 'enrollInCourse']); // Enroll in a course
        Route::post('unEnroll', [EnrollementsController::class, 'unEnrollInCourse']); // Unenroll from a course

        Route::get('searchEncorrollments', [SearchEnrollementsController::class, 'findEnrollmentsByKeywords']); // Search enrollements by keyword
        Route::get('getPublicAndActiveEncorrollments', [SearchEnrollementsController::class, 'getPublicAndActiveEnrollments']); // Get public and active enrollments
    });

    // Comments routes
    Route::prefix('comments')->group(function() {
        Route::post('create', [CommentsWriteController::class, 'createComments']); // Create a comment
        Route::get('getCommentsbyContent', [CommentsReadController::class, 'getCommentsbyContent']); // Get all comments
        Route::put('update', [CommentsWriteController::class, 'updateComments']); // Update a comment
        Route::put('disable', [CommentsWriteController::class, 'disableComments']); // Disable a comment
        Route::get('getCommentsByUser', [ReportCommentsController::class, 'getCommentByUser']); // Get comments by user
    });

    // Qualification routes
    Route::prefix('qualification')->group(function() {
        Route::post('create', [QualificationWriteController::class, 'createQualification']); // Create a qualification by user
        Route::put('update', [QualificationWriteController::class, 'updateQualification']); // Update a qualification
        Route::get('getQualificationByCourse', [QualificationWriteController::class, 'getQualificationByCourse']); // Get qualification by course
    });
});



