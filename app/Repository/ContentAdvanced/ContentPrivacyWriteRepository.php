<?php

namespace App\Repository\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyWriteRepository;
use App\Models\CourseContent;

class ContentPrivacyWriteRepository implements IContentPrivacyWriteRepository
{

    /**
     * Changes the privacy setting of a content.
     *
     * @param int $content_id The ID of the content to change the privacy setting for.
     * @param int $privacy The new privacy setting. 0 for private, 1 for public.
     *
     * @return \App\Models\CourseContent The updated content object.
     */
    public function changeContentPrivacy(int $content_id, int $privacy)
    {
        $content = CourseContent::find($content_id);
        $content->update([
            'public' => $privacy
        ]);
        return $content;
    }
}
