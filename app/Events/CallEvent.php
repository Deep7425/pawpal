<?php

namespace App\Events;

use App\Models\ehr\VideoCall as EhrVideoCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\ehr\VideoCall;

class CallEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $videoCall;
    public $token;

    public function __construct(VideoCall $videoCall, $token = null)
    {
        $this->videoCall = $videoCall;
        $this->token = $token;
    }

    public function broadcastOn()
    {
        return new Channel('video-call-channel');
    }

    public function broadcastWith()
    {
        return [
            'status' => $this->videoCall->status,
            'channel_name' => $this->videoCall->channel_name,
            'token' => $this->token,
        ];
    }
}
