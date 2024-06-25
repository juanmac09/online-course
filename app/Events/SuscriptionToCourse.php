<?php

namespace App\Events;

use App\Interfaces\Repository\Course\ICourseRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuscriptionToCourse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $course;

    /**
     * Create a new event instance.
     */
    public function __construct(int $user_id, int $course_id)
    {
        $userRepository = app(IUserProfileRepository::class);
        $courseRepository = app(ICourseRepository::class);

        $this->user = $userRepository->getUserForId($user_id);
        $this->course = $courseRepository->courseById($course_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
