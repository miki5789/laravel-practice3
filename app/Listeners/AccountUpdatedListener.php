<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Events\AccountUpdated;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AccountUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the "accountUpdated" event.
     */
    public function handle(AccountUpdated $event)
    {
        $this->createChangeLog(Auth::id(), $event, 'Updated');
    }

    /**
     * Create a new ChangeLog instance.
     */
    protected function createChangeLog($operatorId, $event, $operationType)
    {
        
        $changeLog = new ActivityLog();
        $changeLog->operator_id = $operatorId;
        $changeLog->target_id = $event->old_data['id'];
        $changeLog->model = User::class;
        $changeLog->model_id = $event->old_data['id'];
        $changeLog->operation_type = $operationType;
        //dd(array("id" => $event->old_data->id, "surname" => $event->old_data->surname, "given_name" => $event->old_data->given_name, "image_path" => $event->old_data->image_path, "image_file_name" => $event->old_data->image_file_name, "birth_day" => $event->old_data->birth_day, "phone" => $event->old_data->phone, "email" => $event->old_data->email));
        $changeLog->original_data = json_encode($event->old_data);
        $changeLog->new_data = json_encode($event->new_data);
        //dd($changeLog->old_data, $changeLog->new_data);
        $changeLog->save();
    }
}
