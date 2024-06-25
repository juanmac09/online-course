<?php

namespace App\Repository\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IStatusContentWriteRepository;
use App\Models\CourseContent;

class StatusContentWriteRepository implements IStatusContentWriteRepository
{
    /**
     * Changes the status of a content.
     *
     * @param int $content_id The ID of the content to be updated.
     * @param int $status The new status of the content.
     *
     * @return \App\Models\CourseContent The updated content object.
     */
    public function changeContentStatus(int $content_id, int $status)
    {
        $content = CourseContent::find($content_id);
        $content->update([
            'status' => $status
        ]);
        return $content;
    }
}
