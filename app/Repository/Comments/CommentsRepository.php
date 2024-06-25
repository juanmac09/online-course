<?php

namespace App\Repository\Comments;

use App\Interfaces\Repository\Comments\ICommentsRepository;
use App\Models\Comments;

class CommentsRepository implements ICommentsRepository
{
    /**
     * Retrieves a comment by its ID.
     *
     * @param int $id The unique identifier of the comment to retrieve.
     *
     * @return Comments The comment object if found, or null if not found.
     */
    public function commentById(int $id): Comments
    {
        $comments = Comments::find($id);
        return $comments;
    }
}
