<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Support\Facades\Config;

/**
 * @resource Subscription
 *
 *  all functions about subscriptions :
 *      to show all subscriptions , top subscription by number users use that subscription with their information
 *      to show specified subscription and related information
 *
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{
    /**
     * Subscription List
     *
     * Display a listing of the subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        foreach ($subscriptions as $subscription){
            $subscription["type_name"] = value(Config::get('subscriptionstypes.type.'.$subscription->type));
            $subscription["users_number"] = count($subscription->users()->get());
        }
        return response()->json(['data'=>['subscriptions'=>$subscriptions],'result'=>1,'description'=>'list of subscriptions with number of users that buy that subscription','message'=>'success']);
    }

    /**
     * Subscription
     *
     * Display the specified subscription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscription = Subscription::query()->find($id);
        if($subscription) {
            $subscription["type_name"] = value(Config::get('subscriptionstypes.type.' . $subscription->type));
            $subscription["users_number"] = count($subscription->users()->get());
            return response()->json(['data'=>['subscriptions'=>$subscription],'result'=>1,'description'=>'list of subscriptions with number of users that buy that subscription','message'=>'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong subscription id ', 'message' => 'failed']);
        }
    }

}
