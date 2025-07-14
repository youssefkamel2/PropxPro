<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_newsletter_subscribers')->only('index');
        $this->middleware('permission:remove_newsletter_subscriber')->only('destroy');
    }

    // Public subscribe endpoint
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        $subscriber = NewsletterSubscription::firstOrCreate(
            ['email' => $data['email']],
            ['is_active' => true]
        );
        if (!$subscriber->is_active) {
            $subscriber->is_active = true;
            $subscriber->save();
        }
        return $this->success(null, 'Subscribed successfully');
    }

    // Protected: list subscribers
    public function index()
    {
        $subs = NewsletterSubscription::all();
        return $this->success($subs, 'Subscribers fetched successfully');
    }

    // Protected: remove (deactivate) subscriber
    public function destroy($newsletterSubscription)
    {
        $newsletterSubscription = NewsletterSubscription::find($newsletterSubscription);
        if (!$newsletterSubscription) {
            return $this->error('Subscriber not found', 404);
        }

        $newsletterSubscription->delete();
        return $this->success(null, 'Subscriber deleted successfully');
    }
} 