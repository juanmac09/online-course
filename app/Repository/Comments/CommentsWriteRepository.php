<?php

namespace App\Repository\Comments;

use App\Interfaces\Repository\Comments\ICommentsWriteRepository;
use App\Models\Comments;

class CommentsWriteRepository implements ICommentsWriteRepository
{
    /**
     * Create a new comment.
     *
     * @param int $user_id The ID of the user who is creating the comment.
     * @param int $content_id The ID of the content to which the comment is attached.
     * @param string $comment The text of the comment.
     *
     * @return Comments The newly created comment object.
     */
    public function createComments(int $user_id, int $content_id, string $comment)
    {
        $comment = Comments::create([
            'user_id' => $user_id,
            'content_id' => $content_id,
            'content' => $comment
        ]);

        return $comment;
    }


    /**
     * Update an existing comment.
     *
     * @param int $comment_id The ID of the comment to be updated.
     * @param string $comment The new text of the comment.
     *
     * @return Comments The updated comment object.
     */
    public function updateComments(int $comment_id, string $content)
    {
        
        $comment = Comments::find($comment_id);
        $comment->update([
            'content' => $content
        ]);

        return $comment;
    }


    /**
     * Disable an existing comment.
     *
     * @param int $comment_id The ID of the comment to be disabled.
     *
     * @return Comments The updated comment object.
     */
    public function disableComments(int $comment_id)
    {

        $comment = Comments::find($comment_id);
        $comment->update([
            'status' => 0,
        ]);

        return $comment;
    }
}
