<?php

namespace App\Repository\Content;

use App\Interfaces\Repository\Content\IContentWriteRepository;
use App\Models\CourseContent;

class ContentWriteRepository implements IContentWriteRepository
{
    /**
     * This function is responsible for uploading content to the database.
     *
     * @param array $contentData An associative array containing the content data to be inserted.
     * The keys of the array should match the column names in the 'course_contents' table.
     *
     * @return void This function does not return any value.
     *
     * @throws \Exception If there is an error while inserting the content data into the database.
     */
    public function uploadContent(array $contentData)
    {
        CourseContent::create($contentData);
        return $contentData;
    }

    /**
     * This method is responsible for updating the content in the database.
     *
     * @param int $id The unique identifier of the content to be updated.
     * @param array $contentData An associative array containing the updated content data.
     * The keys of the array should match the column names in the 'course_contents' table.
     *
     * @return \App\Models\CourseContent The updated content object.
     *
     * @throws \Exception If there is an error while updating the content data into the database.
     */
    public function updateContent(int $id, array $contentData)
    {
        $content = CourseContent::find($id);
        $content->update($contentData);
        return $content;
    }


    /**
     * This method is responsible for disabling the content in the database.
     *
     * @param int $id The unique identifier of the content to be disabled.
     *
     * @return \App\Models\CourseContent The updated content object.
     *
     * @throws \Exception If there is an error while disabling the content data into the database.
     */
    public function disableContent(int $id)
    {
        $content = CourseContent::find($id);
        $content->update([
            'status' => 0,
        ]);
        return $content;
    }


    /**
     * This method is responsible for updating the order of a content in the database.
     *
     * @param int $id The unique identifier of the content to be updated.
     * @param int $position The new position of the content.
     *
     * @return \App\Models\CourseContent The updated content object.
     *
     * @throws \Exception If there is an error while updating the content order in the database.
    */
    public function updateContentOrder(int $id, int $position)
    {
        $content = CourseContent::find($id);
        $content->update([
            'order' => $position,
        ]);
        return $content;
    }
}
