<?php

namespace App\Policies;

use App\Interfaces\Repository\Comments\ICommentsRepository;
use App\Interfaces\Repository\Content\IContentRepository;
use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\Comments;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentsPolicy extends Policy
{


    public $courseOwnerService;
    public $contentRepository;
    public $commentsRepository;
    public function __construct(ICourseOwnerService $courseOwnerService, IPermissionVerificationService $permissionService, IContentRepository $contentRepository, ICommentsRepository $commentsRepository)
    {
        parent::__construct($permissionService);
        $this->courseOwnerService = $courseOwnerService;
        $this->contentRepository = $contentRepository;
        $this->commentsRepository = $commentsRepository;
    }



    /**
     * Determines whether the user can get comments by content.
     *
     * @param User $user The authenticated user.
     * @param int $content_id The ID of the content.
     *
     * @return bool True if the user can get comments, false otherwise.
     */
    public function getCommentsbyContent(User $user, int $content_id): bool
    {

        $content = $this->contentRepository->contentById($content_id);
        $isSuscribe = $this->courseOwnerService->isSuscriber($user, $content->course_id);
        $permitted = $this->permissionService->hasPermission($user, 'comments.index.content');
        return $isSuscribe || $permitted;
    }


    /**
     * Determines whether the user can create a comment.
     *
     * @param User $user The authenticated user.
     * @param int $content_id The ID of the content.
     *
     * @return bool True if the user can create a comment, false otherwise.
     */
    public function create(User $user, int $content_id): bool
    {
        $content = $this->contentRepository->contentById($content_id);
        $isSuscribe = $this->courseOwnerService->isSuscriber($user, $content->course_id);
        $permitted = $this->permissionService->hasPermission($user, 'comments.create');
        return $isSuscribe && $permitted;
    }


    /**
     * Determines whether the user can update a comment.
     *
     * @param User $user The authenticated user.
     * @param int $comment_id The ID of the comment.
     *
     * @return bool True if the user can update the comment, false otherwise.
     */
    public function update(User $user, int $comment_id): bool
    {
        $comment = $this->commentsRepository->commentById($comment_id);
        $commentOwner = $comment->user_id  == $user->id;
        $permitted = $this->permissionService->hasPermission($user, 'comments.update');
        return $commentOwner && $permitted;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user The authenticated user.
     * @param int $comment_id The ID of the comment.
     *
     * @return bool True if the user can delete the comment, false otherwise.
     */
    public function disable(User $user, int $comment_id): bool
    {
        $comment = $this->commentsRepository->commentById($comment_id);
        $commentOwner = $comment->user_id  == $user->id;
        $permitted = $this->permissionService->hasPermission($user, 'comments.disable');
        return $commentOwner || $permitted;
    }


    /**
     * Determines whether the user can get comments by the user.
     *
     * @param User $user The authenticated user.
     *
     * @return bool True if the user can get comments by the user, false otherwise.
     */
    public function getCommentByUser(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'comments.index.user');
        return  $permitted;
    }
}
