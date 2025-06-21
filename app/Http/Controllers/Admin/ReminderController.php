<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "time" => "required",
            "date" => "required",
            "title" => "required",
        ]);
        Reminder::create([
            "user_id"=>auth()->user()->id,
            "date"=>$request->date,
            "time"=>$request->time,
            "title"=>$request->title,
            "updated_at"=>null,
        ]);
        session()->flash("success",__('messages.Added_successfully'));
        return back();
    }
    public function confrim_reminder(Request $request)
    {
        $request->validate([
            'reminder_id' => 'required|exists:reminders,id',
        ]);
    
        $reminder = Reminder::find($request->reminder_id);
    
        if ($reminder) {
            $reminder->update(['seen' => true]);
    
            return response()->json([
                'message' => 'Confirmed successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Reminder not found',
            ], 404);
        }
    }
    
    public function remind_later(Request $request)
    {
        $request->validate([
            'reminder_id' => 'required|exists:reminders,id',
        ]);
    
        $reminder = Reminder::find($request->reminder_id);
    
        if ($reminder) {
            $reminder->update(['updated_at' => Carbon::now()]);
            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Reminder not found',
            ], 404);
        }
    }
    
}

