<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Discount;
use App\Genre;
use App\Narrator;
use App\Subscription;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

/**
 * @resource User
 *
 * all related operations to specified user
 *
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Register
     *
     * register specified user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = array(
            'name' => 'required|min:7',
            'phone'    => 'required|max:11|min:11|regex:/(09)[0-9]{9}/',
            'email' => 'required|email',
            'password' => 'required|min:3'
        );
        $messages = [
            'name.required' => 'وارد کردن نام شما ضروری است ',
            'email.required' => 'وارد کردن ایمیل شما ضروری است ',
            'password.required' => 'وارد کردن گذرواژه  شما ضروری است ',
            'name.min' => 'نام کامل ( حداقل ۷ کاراکتر) خود را وارد نمایید  ',
            'email.email' => 'ایمیل معتبر نیست',
            'password.min' => 'حداقل 3 کاراکتر لازم است',
            'phone.required'   => 'موبایل الزامی است.',
            'phone.min'        => 'موبایل شما معتبر نیست.',
            'phone.regex' =>'فرمت شماره تماس درست نیست از فرمت مثالی ۰۹۳۰۱۱۰۱۰۱۰ استفاده نمایید.'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if (! $validator->fails()) {
            try {
                User::create([
                    'name'     => $request->get('name'),
                    'email'    => $request->get('email'),
                    'phone'    => $request->get('phone'),
                    'password' => bcrypt($request->get('password')),
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => [], 'result' => 0, 'description' => 'repetetive user', 'message' => 'Token Not Created']);
            }
            $condition = ['email' => $request->get('email')];
            $user = User::query()->where($condition)->first();
            $api_code = str_random(60);
            $user->api_token = $api_code;
            try {
                $user->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => [], 'result' => 0, 'description' => 'failed to save user', 'message' => 'Token Not Created']);
            }
            return response()->json(['data' => ['user' => $user], 'result' => 1, 'description' => 'success to save user', 'message' => ['User registered successfully']]);
        }else{
            return response()->json(['data' => ['errors'=>$validator->errors()], 'result' => 0, 'description' => 'wrong input', 'message' => 'failed by Validator']);
        }
    }

    /**
     * Login
     *
     * login specified user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $response = ['data'=>[],'result' => '0','description'=>'','message'=>''];
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:3'
        );
        $messages = [
            'email.required' => 'وارد کردن ایمیل شما ضروری است ',
            'password.required' => 'وارد کردن گذرواژه  شما ضروری است ',
            'email.email' => 'ایمیل معتبر نیست',
            'password.min' => 'حداقل 3 کاراکتر لازم است'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if (! $validator->fails()) {
            $email = Input::get('email');
            $condition=['email'=> $email];
            $user=User::query()->where($condition)->first();
            if(is_null($user)){
                $response['description']='user not exist';
                $response['message']='. کاربر ثبت نشده است';
                return response()->json($response);
            }
            else{
                if(!password_verify(Input::get('password'),$user->password))
                {
                    $response['description']='password mistmatch';
                    $response['message']='گذرواژه اشتباه می باشد.';
                    return response()->json($response);
                }
                $api_code=str_random(60);
                $user=User::query()->find($user->id);
                $user->api_token=$api_code;
                try{
                    $user->save();
                }
                catch ( \Illuminate\Database\QueryException $e){
                    $response['message']='Token Not Created';
                    return response()->json($response);
                }
                $user = User::query()->where($condition)->first();
                $response['data']=$user;
                $response['message']='successful Login';
                $response['result']='1';
            }
            return response()->json($response);
        }else{
            $response['data']=['errors'=>$validator->errors()];
            $response['description']='wrong input';
            $response['message']='validator error';
            return response()->json($response);
        }

    }
    /**
     * Logout
     *
     * logout specified user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        $user=User::query()->where('api_token',Input::get('api_token'))->first();
        if (! is_null($user)){
            $user->api_token=null;
            $user->save();
            return response()->json(['data' => [], 'result' => 1, 'description' => 'logging out successfully', 'message' => 'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'authentication failed', 'message' => 'Token Not Created']);

        }
    }

    /**
     * Buy Subscription
     *
     * buy specified subscription
     * 
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function buySubscription(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $subscription = Subscription::query()->find($id);
        if($user and $subscription and $request->input('api_token')) {
            $price = $subscription->price;
            $Code = '0';
            if ($request->input('code')) {
                $discount = Discount::query()->where(['code' => $request->input('code'), 'subscription_id' => $id])->first();
                if ($discount) {
                    if ($discount->count > 0) {
                        $discount->count -= 1;
                        try {
                            $discount->save();
                        } catch (\Illuminate\Database\QueryException $e) {
                            return response()->json(['data'=>['error'=>$e],'result'=>0,'description'=>"after finding the discount couldn't change his count and save it",'message'=>'failed']);
                        }
                        $Code = $request->input('code');
                        if ($discount->type == 0) {
                            $price = $subscription->price * (100 - $discount->value) / 100;
                        } else {
                            $price = $subscription->price - $discount->value;
                        }
                    } else {
                        $Code = '0';
                    }
                } else {
                    $Code = '0';
                }
            }
            $amount = $price * 10;
            $api = 'ad19e8fe996faac2f3cf7242b08972b6';
            $redirect = 'http://vestacamp.vestaak.com/subscriptions/verify';
            $result = $this->send($api, $amount, $redirect);
            $result = json_decode($result);
            if ($result->status) {
                $trans = new Transaction();
                $trans->user_id = $user->id;
                $trans->transid = $result->transId;
                $trans->amount = $amount;
                $trans->type = 'subscription.' . $subscription->id . $Code;
                try {
                    $trans->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['data'=>['error'=>$e],'result'=>0,'description'=>"couldn't save new transaction",'message'=>'failed']);
                }
                $go = "https://pay.ir/payment/gateway/$result->transId";
                return redirect($go);
            } else {
                return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong','message'=>"مشکلی در اتصال به درگاه پرداخت به وجود آمده است لطفا کمی بعد تلاش کنید."]);
            }
        }
        elseif($user){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong subscription id ', 'message' => 'failed']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong user api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Verify
     *
     * verify bought subscription
     *
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function buySubscriptionVerify()
    {
        $user = User::query()->where('api_token',Input::get('api_token'))->first();
        $api = 'ad19e8fe996faac2f3cf7242b08972b6';
        $transId = $_POST['transId'];
        $result = $this->verify($api,$transId);
        $result = json_decode($result);
        $trans=Transaction::query()->where('transid',$transId)->first();
        if(is_null($trans) || $trans->user_id!=$user->id || $result->status!=1 || $result->amount!=$trans->amount){
            return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong','message'=>"مشکلی در تراکنش شما به وجود آمده است، لطفا کمی بعد تلاش کنید."]);
        }
        $trans=Transaction::query()->findOrFail($trans->id);
        $trans->save();
        $pieces = explode(".", $trans->type);
        $subscription=Subscription::query()->findOrFail(intval($pieces[1]));
        $Code=$pieces[2];
        $cardnumber = $_POST['cardNumber'];
        $trans->type=$trans->type.'='.$cardnumber;
        if ($Code=='0'){
            $Code=0;
        }
        $res=$this->takeSubscription($subscription,$user,$Code);
        if(! $res['error']){
            $trans->condition=1;
            try{
                $trans->save();
            }
            catch ( \Illuminate\Database\QueryException $e){
                return response()->json(['data'=>['error'=>$e],'result'=>0,'description'=>'transaction saving problem','message'=>"مشکلی در تراکنش شما به وجود آمده است، لطفا کمی بعد تلاش کنید."]);
            }
            return  response()->json(['transId'=>$transId,'subscription'=>$subscription,'price'=>$trans->amount/10000]);
        }
        else{
            return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong in checking again','message'=>"تراکنش با موفقیت انجام شد، ولی مشکلی به وجود آمده است ، با بخش پشتیبانی تماس بگیرید. | "." کد پیگیری تراکنش :$transId "]);
        }

    }

    /**
     * increase Credit
     *
     * to buy books user can increase his credit
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function increaseCredit(Request $request)
    {
        $user = User::query()->where('api_token',Input::get('api_token'))->first();
        $price = $request->input('amount');
        if($user and $price>0){
            $amount = $price * 10;
            $api = 'ad19e8fe996faac2f3cf7242b08972b6';
            $redirect = 'http://vestacamp.vestaak.com/users/credit/verify';
            $result = $this->send($api, $amount, $redirect);
            $result = json_decode($result);
            if ($result->status) {
                $trans = new Transaction();
                $trans->user_id = $user->id;
                $trans->transid = $result->transId;
                $trans->amount = $amount;
                $trans->type = 'credit';
                try {
                    $trans->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['data'=>['error'=>$e],'result'=>0,'description'=>"couldn't save new transaction",'message'=>'failed']);
                }
                $go = "https://pay.ir/payment/gateway/$result->transId";
                return redirect($go);
            } else {
                return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong','message'=>"مشکلی در اتصال به درگاه پرداخت به وجود آمده است لطفا کمی بعد تلاش کنید."]);
            }
        }
        elseif($user){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'negative or zero amount of money', 'message' => 'failed']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }
    /**
     * Verify
     *
     * verify increased credit
     *
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function increaseCreditVerify()
    {
        $user = User::query()->where('api_token',Input::get('api_token'))->first();
        $api = 'ad19e8fe996faac2f3cf7242b08972b6';
        $transId = $_POST['transId'];
        $result = $this->verify($api,$transId);
        $result = json_decode($result);
        $trans=Transaction::query()->where('transid',$transId)->first();
        if(is_null($trans) || $trans->user_id!=$user->id || $result->status!=1 || $result->amount!=$trans->amount){
            $message="مشکلی در تراکنش شما به وجود آمده است، لطفا کمی بعد تلاش کنید.";
            return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong','message'=>$message]);
        }
        $res=$this->AdjustCredit($trans->amount/10000);
        if($res){
            $cardnumber = $_POST['cardNumber'];
            $trans=Transaction::query()->findOrFail($trans->id);
            $trans->type=$trans->type.'='.$cardnumber;
            $trans->condition=1;
            $trans->save();
            return response()->json(['data'=>['transId'=>$transId,'finance'=>$user->finance_amount+$trans->amount/10,'amount'=>$trans->amount/10],'result'=>1,'description'=>'successfully','message'=>'success']);
        }
        else{
            $message="تراکنش با موفقیت انجام شد، ولی مشکلی به وجود آمده است ، با بخش پشتیبانی تماس بگیرید. | "." کد پیگیری تراکنش :$transId ";
            return response()->json(['data'=>[],'result'=>0,'description'=>'something goes wrong','message'=>$message]);
        }

    }

    public function AdjustCredit($payment)
    {
        $user = User::query()->where('api_token',Input::get('api_token'))->first();
        if($user->finance_amount!=-1)
        {
            $user->finance_amount = $user->finance_amount + $payment;
            try{
                $user->save();
            }
            catch ( \Illuminate\Database\QueryException $e){
                return response()->json(['data'=>[],'result'=>0,'description'=>'cannot save the user','message'=>$e]);
            }
            return response()->json(['data'=>['user'=>$user],'result'=>1,'description'=>'user saved successfully','message'=>'success']);
        }
        else
        {
            $user->finance_amount = $payment;
            try{
                $user->save();
            }
            catch ( \Illuminate\Database\QueryException $e){
                return response()->json(['data'=>[],'result'=>0,'description'=>'cannot save the user','message'=>$e]);
            }
            return response()->json(['data'=>['user'=>$user],'result'=>1,'description'=>'user saved successfully','message'=>'success']);
        }
    }
    /**
     * Take Subscription
     *
     * takeSubscription
     *
     * @param $subscription
     * @param $user
     * @param $code
     * @return $this|array
     */
    public function takeSubscription($subscription,$user,$code)
    {
        $response=[];
        $price = $subscription->price;
        if($code) {
            $discount = Discount::query()->where(['code'=>$code,'subscription_id'=>$subscription->id])->first();
            if (is_null($discount)) {
                $response['error'] = 'not such a code in valid';
                $response['price'] = $price;
                return $response;
            }
            else
            {
                if(!is_null($discount)) {
                    if ($discount->count <= 0 or $discount->disable == 1|| $discount->subscription_id!=$subscription->id) {
                        $response['error'] = 'not available as it is expired';
                        $response['price'] = $price;
                        return $response;
                    }
                    else
                    {
                        $discount->count -= 1;
                        try{
                            $discount->save();
                        }
                        catch ( \Illuminate\Database\QueryException $e){
                            $response['error']= 'can not save discount';
                            return $response;
                        }
                        $response['error'] = 'there is no error';
                        if ($discount->type == 0) {
                            $newprice = $price * (100-$discount->value) / 100;
                        } else {
                            $newprice = $price - $discount->value;
                        }
                        $response['price'] = $newprice;

                        $subscriptions = $user->subscriptions()->get();
                        $mostRecent = 0;
                        foreach ($subscriptions as $subscription){
                            if ($subscription->pivot->expiration_date > $mostRecent) {
                                $mostRecent = $subscription->pivot->expiration_date;
                            }
                        }
                        $date = $mostRecent;
                        $date = strtotime($date);
                        try{
                            if($subscription->type == 0) {
                                $date = strtotime("+1 day", $date);
                                $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                            }
                            if($subscription->type == 1) {
                                $date = strtotime("+7 day", $date);
                                $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                            }
                            if($subscription->type == 2) {
                                $date = strtotime("+31 day", $date);
                                $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                            }
                            if($subscription->type == 3) {
                                $date = strtotime("+365 day", $date);
                                $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                            }
                        }
                        catch ( \Illuminate\Database\QueryException $e){
                            $response['error']=$e;
                            $response['price']=$price;
                            return $response;
                        }
                        return $response;
                    }
                }
                else{
                    $response['error']=1;
                    $response['price']=$price;
                    return $response;
                }
            }
        }
        else{
            $subscriptions = $user->subscriptions()->get();
            $mostRecent = 0;
            foreach ($subscriptions as $subscription){
                if ($subscription->pivot->expiration_date > $mostRecent) {
                    $mostRecent = $subscription->pivot->expiration_date;
                }
            }
            $date = $mostRecent;
            $date = strtotime($date);
            try{
                if($subscription->type == 0) {
                    $date = strtotime("+1 day", $date);
                    $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                }
                if($subscription->type == 1) {
                    $date = strtotime("+7 day", $date);
                    $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                }
                if($subscription->type == 2) {
                    $date = strtotime("+31 day", $date);
                    $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                }
                if($subscription->type == 3) {
                    $date = strtotime("+365 day", $date);
                    $user->subscriptions()->attach($subscription->id, ['paid' => $price, 'expiration_date' => date('Y-m-d H:i:s', $date)]);
                }
            }
            catch ( \Illuminate\Database\QueryException $e){
                $response['error']=$e;
                return view('pay-error.pay-error')->with(['message'=>$e]);
            }
            $response['error']=0;
            $response['price']=$price;
            return $response;
        }
    }
    /**
     * send
     *
     * @param $api
     * @param $amount
     * @param $redirect
     * @param null $factorNumber
     * @return mixed
     */
    function send($api, $amount, $redirect, $factorNumber=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pay.ir/payment/send');
        curl_setopt($ch, CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect&factorNumber=$factorNumber");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * verify
     *
     * @param $api
     * @param $transId
     * @return mixed
     */
    public function verify($api, $transId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pay.ir/payment/verify');
        curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$api&transId=$transId");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    /**
     * Get Book
     *
     * get specified book by subscription the user bought
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBook(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and $request->input('api_token')) {
            $subscriptions = $user->subscriptions()->where('paid', '<>', 0)->get();
            if (count($subscriptions)) {
                $mostRecent = 0;
                foreach ($subscriptions as $subscription) {
                    if ($subscription->pivot->expiration_date > $mostRecent) {
                        $mostRecent = $subscription->pivot->expiration_date;
                    }
                }
                $now = date('Y-m-d H:i:s');
                if ($mostRecent > $now) {
                    $book = $user->books()->wherePivot('book_id', $id)->first();
                    if ($book) {
                        return response()->json(['data' => [], 'result' => 0, 'description' => 'user already got this book', 'message' => 'failed']);
                    } else {
                        $book = Book::query()->find($id);
                        if($book) {
                            try {
                                $user->books()->attach($id);
                            } catch (\Illuminate\Database\QueryException $e) {
                                return response()->json(['data' => ['error' => $e], 'result' => 0, 'description' => 'cannot save book', 'message' => 'failed']);
                            }
                        }
                        else{
                            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong book id ', 'message' => 'failed']);
                        }
                        $books = $user->books()->get();
                        return response()->json(['data' => ['books' => $books], 'result' => 1, 'description' => 'user got this book successfully', 'message' => 'successfully']);
                    }
                } else {
                    return response()->json(['data' => ['today_date' => $now], 'result' => 0, 'description' => 'all user subscriptions expired', 'message' => 'failed by no subscription']);
                }
            } else {
                return response()->json(['data' => ['subscriptions' => $subscriptions], 'result' => 0, 'description' => 'user has no subscription to get a book ', 'message' => 'failed by no subscription']);
            }
        }else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Wish Book
     *
     * add new wish book
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishBook(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and $request->input('api_token')){
            $wish = $user->wishlist()->wherePivot('book_id',$id)->first();
            if($wish){
                return response()->json(['data'=>[],'result'=>0,'description'=>'user already wish this book','message'=>'failed']);
            }
            else{
                $book = Book::query()->find($id);
                if($book) {
                    try {
                        $user->wishlist()->attach($id);
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['data' => ['error' => $e], 'result' => 0, 'description' => 'cannot wish book ', 'message' => 'failed']);
                    }
                }
                else{
                    return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong book id ', 'message' => 'failed']);
                }
                $wishes = $user->wishlist()->get();
                return response()->json(['data'=>['books'=>$wishes],'result'=>1,'description'=>'user got wish book successfully','message'=>'success']);
            }
        }else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Get Genre
     *
     * add new genre for user
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenre(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and  $request->input('api_token')){
            $genre = $user->genres()->wherePivot('genre_id',$id)->first();
            if($genre){
                return response()->json(['data'=>[],'result'=>0,'description'=>'user already get this genre','message'=>'failed']);
            }
            else{
                $genre = Genre::query()->find($id);
                if($genre) {
                    try {
                        $user->genres()->attach($id);
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['data' => ['error' => $e], 'result' => 0, 'description' => 'cannot save genre', 'message' => 'failed']);
                    }
                }
                else{
                    return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong genre id ', 'message' => 'failed']);
                }
                $genres = $user->genres()->get();
                return response()->json(['data'=>['genres'=>$genres],'result'=>1,'description'=>'user got genre succesfully','message'=>'success']);
            }
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Review Book
     *
     * user review specified book
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviewBook(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $book = Book::query()->find($id);
        if($user and $book and $request->input('api_token')) {
            $rules = array(
                'comment' => 'required',
                'rate' => 'required',
            );
            $messages = [
                'comment.required' => 'وارد کردن نظر شما ضروری است ',
                'rate.required' => 'وارد کردن امتیاز شما ضروری است ',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if (! $validator->fails()) {
                try {
                    $user->book_reviews()->attach($id, ['comment' => $request->input('comment'), 'rate' => $request->input('rate')]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['data' => ['error'=>$e], 'result' => 0, 'description' => 'cannot save the comment', 'message' => 'failed']);
                }
                $review = $user->book_reviews()->where('book_id',$id)->where('user_id',$user->id)->get();
                return response()->json(['data' => ['review',$review], 'result' => 1, 'description' => 'comment saved successfully', 'message' => 'success']);
            }
            else{
                return response()->json(['data' => ['errors'=>$validator->errors()], 'result' => 0, 'description' => 'wrong input', 'message' => 'failed by Validator']);
            }
        }
        elseif ($book){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong book id ', 'message' => 'failed']);
        }
    }
    /**
     * Review Author
     *
     * user review specified author
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviewAuthor(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $author = Author::query()->find($id);
        if($user and $author and $request->input('api_token')) {
            $rules = array(
                'comment' => 'required',
                'rate' => 'required',
            );
            $messages = [
                'comment.required' => 'وارد کردن نظر شما ضروری است ',
                'rate.required' => 'وارد کردن امتیاز شما ضروری است ',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if (! $validator->fails()) {
                try {
                    $user->author_reviews()->attach($id, ['comment' => $request->input('comment'), 'rate' => $request->input('rate')]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['data' => ['error'=>$e], 'result' => 0, 'description' => 'cannot save the comment', 'message' => 'failed']);
                }
                $review = $user->author_reviews()->where('author_id',$id)->where('user_id',$user->id)->get();
                return response()->json(['data' => ['review',$review], 'result' => 1, 'description' => 'comment saved successfully', 'message' => 'success']);
            }
            else{
                return response()->json(['data' => ['errors'=>$validator->errors()], 'result' => 0, 'description' => 'wrong input', 'message' => 'failed by Validator']);
            }
        }
        elseif ($author){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong author id ', 'message' => 'failed']);
        }
    }
    /**
     * Review Narrator
     *
     * user review specified narrator
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviewNarrator(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $narrator = Narrator::query()->find($id);
        if($user and $narrator and $request->input('api_token')) {
            $rules = array(
                'comment' => 'required',
                'rate' => 'required',
            );
            $messages = [
                'comment.required' => 'وارد کردن نظر شما ضروری است ',
                'rate.required' => 'وارد کردن امتیاز شما ضروری است ',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if (! $validator->fails()) {
                try {
                    $user->narrator_reviews()->attach($id, ['comment' => $request->input('comment'), 'rate' => $request->input('rate')]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['data' => ['error'=>$e], 'result' => 0, 'description' => 'cannot save the comment', 'message' => 'failed']);
                }
                $review = $user->narrator_reviews()->where('narrator_id',$id)->where('user_id',$user->id)->get();
                return response()->json(['data' => ['review',$review], 'result' => 1, 'description' => 'comment saved successfully', 'message' => 'success']);
            }
            else{
                return response()->json(['data' => ['errors'=>$validator->errors()], 'result' => 0, 'description' => 'wrong input', 'message' => 'failed by Validator']);
            }
        }
        elseif ($narrator){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong narrator id ', 'message' => 'failed']);
        }
    }
    /**
     * Change Password
     *
     * change password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePass(Request $request)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user  and Input::get('api_token')) {
            $input = Input::all();
            if (!$request->has('oldpass') || !$request->has('newpass')) {
                return response()->json(['data' => [], 'result' => 0, 'description' => 'there is no information', 'message' => 'failed']);
            }

            if (!password_verify($request->input('oldpass'), $user->password))
                return response()->json(['data' => [], 'result' => 0, 'description' => 'there is mismatch in password', 'message' => 'failed']);
            $rules = array(
                'newpass' => 'required|min:6',
            );
            $messages = [
                'newpass.required' => 'رمز عبور ضروری میباشد ',
                'newpass.min' => 'حداقل طول پسورد ۶ است ',
            ];
            $validator = Validator::make($input, $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['data' => ['errors' => $validator->errors()], 'result' => 0, 'dscription' => 'validator failed', 'message' => 'failed']);
            }
            $user->password = bcrypt(Input::get('newpass'));
            try {
                $user->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => ['errors' => $e], 'result' => 0, 'dscription' => 'cannot save the user', 'message' => 'failed']);
            }
            $user = User::query()->where('api_token', Input::get('api_token'))->first();
            return response()->json(['data' => ['user' => $user], 'result' => 1, 'dscription' => 'password change successfully', 'message' => 'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Upload Photo
     *
     * upload photo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPhoto()
    {
        $user = User::query()->where('api_token',Input::get('api_token'))->first();
        if($user and Input::get('api_token')) {
            if (Input::hasFile('image')) {
                $file = array('image' => Input::file('image'));
                $rules = array('image' => 'max:100000|mimes:jpeg,JPEG,PNG,png');
                $messages = [
                    'image.max' => 'حجم فایل بسیار زیاد است ',
                    'image.mimes' => 'فرمت فایل شما ساپورت نمیشود.',
                ];
                $validator = Validator::make($file, $rules, $messages);
                if ($validator->fails()) {
                    return response()->json(['data' => ['errors' => $validator->errors()], 'result' => 0, 'dscription' => 'validator failed', 'message' => 'failed']);
                }
                if (Input::file('image')->isValid()) {
                    $destinationPath = 'uploads/' . $user->id; // upload path
                    $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                    $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
                    $user->image = $destinationPath . '/' . $fileName;
                    try {
                        $user->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['data' => ['errors' => $e], 'result' => 0, 'dscription' => 'cannot save the user', 'message' => 'failed']);
                    }
                    $user = User::query()->where('api_token',Input::get('api_token'))->first();
                    return response()->json(['data' => ['user' => $user], 'result' => 1, 'dscription' => 'the user successfully save', 'message' => 'success']);
                } else {
                    return response()->json(['data' => [], 'result' => 0, 'description' => 'the image is not valid ', 'message' => 'failed']);
                }
            } else
                return response()->json(['data' => 0, 'result' => 0, 'description' => 'no image file ', 'message' => 'failed']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Delete Wish book
     *
     * detach a wish book for a user
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteWantBook(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $wish = $user->wishlist()->wherePivot('book_id',$id)->first();
        if($user and $wish and $request->input('api_token')) {
            try {
                $user->wishlist()->detach($id);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => ['errors' => $e], 'result' => 0, 'description' => 'cannot detach the book', 'message' => 'failed']);
            }
            return response()->json(['data'=>['wishBook'=>$wish],'result'=>1,'description'=>'specified book detach successfully','message'=>'success']);
        }
        elseif ($wish){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => "wrong book id or you didn't want this book ", 'message' => 'failed']);
        }
    }
    /**
     * Delete User Genre
     *
     * detach a genre selected by a user
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteGenre(Request $request,$id)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $genre = $user->genres()->wherePivot('genre_id',$id)->first();
        if($user and $genre and $request->input('api_token')) {
            try {
                $user->genres()->detach($id);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => ['errors' => $e], 'result' => 0, 'description' => 'cannot detach the genre', 'message' => 'failed']);
            }
            return response()->json(['data'=>['userGenre'=>$genre],'result'=>1,'description'=>'specified genre detach successfully','message'=>'success']);
        }
        elseif ($genre){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => "wrong genre id or you didn't want this genre ", 'message' => 'failed']);
        }
    }

    /**
     * Delete User Image
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUploadPhoto(Request $request)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        $image = $user->image;
        if($user and $image and $request->input('api_token')) {
            $user->image = null;
            try {
                $user->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => ['errors' => $e], 'result' => 0, 'description' => 'cannot delete the image', 'message' => 'failed']);
            }
            $user = User::query()->where('api_token',$request->input('api_token'))->first();
            return response()->json(['data'=>['user'=>$user],'result'=>1,'description'=>'specified user image delete successfully','message'=>'success']);
        }
        elseif ($image){
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
        else {
            return response()->json(['data' => [], 'result' => 0, 'description' => "there is no image to delete", 'message' => 'failed']);
        }
    }
    /**
     * Delete User Account
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUser(Request $request)
    {
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and $request->input('api_token')) {
            try {
                $user->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['data' => ['errors' => $e], 'result' => 0, 'description' => 'cannot delete the user', 'message' => 'failed']);
            }
            return response()->json(['data'=>['user'=>$user],'result'=>1,'description'=>'specified user delete successfully','message'=>'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }

    /**
     * Books may like
     *
     * according to user genres showing related books
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function booksMayLike(Request $request)
    {
        $search = [];
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and $request->input('api_token')) {
            $genres = $user->genres()->get();
            if (count($genres)) {
                foreach ($genres as $genre) {
                    $books = Book::query()->whereHas('genres', function ($query) use ($genre) {
                        $query->where('genre_id', $genre->id);
                    })->distinct()->get();
                    foreach ($books as $book) {
                        $book["authors"]="";
                        $counter=0;
                        foreach ($book->authors()->get() as $author){
                            if($counter)
                                $book["authors"]=$book['authors'].",".$author->name;
                            else
                                $book["authors"]=$author->name;
                            $counter++;
                        }
                        $book["narrators"]="";
                        $counter=0;
                        foreach ($book->narrators()->get() as $narrator){
                            if($counter)
                                $book["narrators"]=$book['narrators'].",".$narrator->name;
                            else
                                $book["narrators"]=$narrator->name;
                            $counter++;
                        }
                        $book["genres"]="";
                        $counter=0;
                        foreach ($book->genres()->get() as $bookgenre){
                            if($counter)
                                $book["genres"]=$book['genres'].",".$bookgenre->name;
                            else
                                $book["genres"]=$bookgenre->name;
                            $counter++;
                        }
                        $rate_count=0;
                        $rate_value=0;
                        foreach ($book->reviews()->wherePivot('enable',1)->get() as $review){
                            if($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if($rate_count == 0)
                            $book['rate']=0;
                        else
                            $book['rate']=$rate_value/$rate_count;
                    }
                    foreach ($books as $book)
                        $search[] = $book;
                }
            }
            return response()->json(['data'=>['books'=>array_unique($search)],'result'=>1,'description'=>'specified user delete successfully','message'=>'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }
    /**
     * Books got
     *
     * showing all books user get by subscription
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function booksGet(Request $request)
    {
        $search = [];
        $user = User::query()->where('api_token',$request->input('api_token'))->first();
        if($user and $request->input('api_token')) {
            $books = $user->books()->get();
            foreach ($books as $book) {
                $book["authors"] = "";
                $counter = 0;
                foreach ($book->authors()->get() as $author) {
                    if ($counter)
                        $book["authors"] = $book['authors'] . "," . $author->name;
                    else
                        $book["authors"] = $author->name;
                    $counter++;
                }
                $book["narrators"] = "";
                $counter = 0;
                foreach ($book->narrators()->get() as $narrator) {
                    if ($counter)
                        $book["narrators"] = $book['narrators'] . "," . $narrator->name;
                    else
                        $book["narrators"] = $narrator->name;
                    $counter++;
                }
                $book["genres"] = "";
                $counter = 0;
                foreach ($book->genres()->get() as $bookgenre) {
                    if ($counter)
                        $book["genres"] = $book['genres'] . "," . $bookgenre->name;
                    else
                        $book["genres"] = $bookgenre->name;
                    $counter++;
                }
                $rate_count = 0;
                $rate_value = 0;
                foreach ($book->reviews()->wherePivot('enable', 1)->get() as $review) {
                    if ($review->pivot->rate) {
                        $rate_count++;
                        $rate_value += $review->pivot->rate;
                    }
                }
                if ($rate_count == 0)
                    $book['rate'] = 0;
                else
                    $book['rate'] = $rate_value / $rate_count;
                $search[] = $book;
            }
            return response()->json(['data'=>['books'=>array_unique($search)],'result'=>1,'description'=>'specified user delete successfully','message'=>'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong api_token ', 'message' => 'failed']);
        }
    }
}
