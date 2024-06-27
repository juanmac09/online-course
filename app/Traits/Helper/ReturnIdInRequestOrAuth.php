<?php

namespace App\Traits\Helper;

trait ReturnIdInRequestOrAuth
{
    /**
     * Retrieves the user ID from the request or from the authenticated user.
     *
     * @param $request The HTTP request object.
     *
     * @return int The user ID.
     */
    public function getUserIdFromRequestOrAuth($request):int
    {
        if ($request->has('id')) {
            return $request->get('id');
        }
        return auth()->id();
    }
}
