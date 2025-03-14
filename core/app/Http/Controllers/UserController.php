<?php

namespace App\Http\Controllers;

use App\Models\AccountBind;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Models\WithdrawGateway;
use App\Models\Payment;
use App\Models\UserInterest;
use App\Models\GeneralSetting;
use App\Models\Ranking;
use App\Models\RefferedCommission;
use App\Models\User;
use App\Models\MoneyTransfer;
use App\Models\UserRanking;
use Illuminate\Http\Request;
use Nette\Utils\Random;
use Purifier;
use Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $general = GeneralSetting::first();
        $this->template = $general->theme == 1 ? 'frontend.' : "theme{$general->theme}.";
    }

    public function dashboard()
    {


        $pageTitle = "Dashboard";
        $totalInvest = Payment::where('user_id', Auth::id())->where('payment_status', 1)->sum('amount');
        $currentInvest = Payment::where('user_id', Auth::id())->where('payment_status', 1)->latest()->first('amount');
        $currentPlan = Payment::with('plan')->where('user_id', Auth::id())->where('payment_status', 1)->latest()->first();
        $allPlan = Payment::with('plan')->where('user_id', Auth::id())->latest()->paginate(10, ['*'], 'plan');
        $withdraw = Withdraw::where('user_id', Auth::id())->where('status', 1)->sum('withdraw_amount');
        $interestLogs = UserInterest::with('payment')->where('user_id', Auth::id())->latest()->paginate(10, ['*'], 'log');

        $commison = RefferedCommission::where('reffered_by', Auth::id())->sum('amount');

        $pendingInvest = Payment::where('user_id', Auth::id())->where('payment_status', 2)->sum('amount');
        $pendingWithdraw = Withdraw::where('user_id', Auth::id())->where('status', 0)->sum('withdraw_amount');
        $totalDeposit = Deposit::where('user_id', Auth::id())->where('payment_status', 1)->sum('final_amount');

        $uplineUser = User::where('id', Auth::user()->reffered_by)->first();
        $UplinerName = $uplineUser ? $uplineUser->fname . ' ' . $uplineUser->lname : 'N/A';

        //         $levelOneUsers = User::where('reffered_by', Auth::id())->pluck('id'); // Direct referred user IDs


        // $investedUsersCount = Payment::whereIn('user_id', $levelOneUsers)
        //     ->where('payment_status', 1) // Check payment status
        //     ->count(); // Get count of payments

        // return $investedUsersCount;
        return view($this->template . 'user.dashboard', compact('commison', 'pageTitle', 'interestLogs', 'totalInvest', 'currentInvest', 'currentPlan', 'allPlan', 'withdraw', 'pendingInvest', 'pendingWithdraw', 'totalDeposit', 'UplinerName'));
    }

    public function profile()
    {
        $pageTitle = 'Profile Edit';

        $user = auth()->user();

        return view($this->template . 'user.profile', compact('pageTitle', 'user'));
    }

    public function profileUpdate(Request $request)
    {



        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|unique:users,username,' . Auth::id(),
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'image' => 'sometimes|image|mimes:jpg,png,jpeg'

        ], [
            'fname.required' => 'First Name is required',
            'lname.required' => 'Last Name is required',

        ]);

        $user = auth()->user();


        if ($request->hasFile('image')) {
            $filename = uploadImage($request->image, filePath('user'), $user->image);
            $user->image = $filename;
        }


        $data = [
            'country' => $request->country,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
        ];

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->username = $request->username;
        $user->address = $data;
        $user->email = $request->email;
        $user->save();



        $notify[] = ['success', 'Successfully Updated Profile'];

        return back()->withNotify($notify);
    }


    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view($this->template . 'user.auth.changepassword', compact('pageTitle'));
    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'oldpassword' => 'required|min:6',
            'password' => 'min:6|confirmed',

        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->oldpassword, $user->password)) {
            return redirect()->back()->with('error', 'Old password do not match');
        } else {
            $user->password = bcrypt($request->password);

            $user->save();

            return redirect()->back()->with('success', 'Password Updated');
        }
    }


    public function transaction()
    {
        $pageTitle = "Transactions";

        $transactions = Transaction::where('user_id', auth()->id())->latest()->with('user')->paginate();

        return view($this->template . 'user.transaction', compact('pageTitle', 'transactions'));
    }

    public function withdraw()
    {
        $pageTitle = 'Withdraw Money';

        // Get available withdrawal methods
        $withdraws = WithdrawGateway::where('status', 1)->latest()->get();

        // Check if the user has an account bound
        $accountBind = AccountBind::where('user_id', auth()->id())->first();
        if (!$accountBind) {
            return redirect(url('account/bind'))->with('error', 'You need to bind an account before withdrawing.');
        }

        return view($this->template . 'user.withdraw.index', compact('pageTitle', 'withdraws', 'accountBind'));
    }


    public function withdrawCompleted(Request $request)
    {
        $general = GeneralSetting::first();
        $checkInvest = Payment::where('user_id', Auth::id())->where('payment_status', 1)->exists();
        if (!$checkInvest) {
            return redirect(url('dashboard'))->with('error', 'Please invest first before withdrawing.');
        }
        // Check per-day withdrawal limit
        $withdrawCount = Withdraw::where('user_id', auth()->id())->whereDate('created_at', now())->count();
        if ($general->withdraw_limit <= $withdrawCount) {
            return back()->with('error', 'Per day withdrawal limit exceeded');
        }

        // Validate only the amount input
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        // Check if the user has an account bound
        $accountBind = AccountBind::where('user_id', auth()->id())->first();
        if (!$accountBind) {
            return redirect(url('account/bind'))->with('error', 'You need to bind an account before withdrawing.');
        }

        // Fetch the withdrawal method from account_binds
        $withdrawMethod = WithdrawGateway::findOrFail($accountBind->withdraw_method_id);

        if ($request->amount < $withdrawMethod->min_amount || $request->amount > $withdrawMethod->max_amount) {
            return back()->with('error', 'Please follow the withdrawal limits');
        }
        if ($withdrawMethod->charge_type == 'percent') {
            $total = $request->amount + ($withdrawMethod->charge * $request->amount) / 100;
        } else {
            $total = $request->amount + $withdrawMethod->charge;
        }
        // Check if user has enough balance
        if (auth()->user()->balance < $total) {
            return back()->with('error', 'Insufficient Balance');
        }

        // Check withdraw limits

        // Calculate withdrawal charge

        // Deduct balance
        auth()->user()->balance -= $total;
        auth()->user()->save();

        // Store withdrawal data
        Withdraw::create([
            'user_id' => auth()->id(),
            'withdraw_method_id' => $accountBind->withdraw_method_id,
            'transaction_id' => strtoupper(Str::random(15)),
            'user_withdraw_prof' => [
                'email' => $accountBind->email,
                'account_information' => $accountBind->account_information,
                'note' => $accountBind->note,
            ],
            'withdraw_charge' => $withdrawMethod->charge,
            'withdraw_amount' => $request->amount,
            'status' => 0
        ]);

        return redirect(url('withdraw'))->with('success', 'Withdraw Successfully done.');
    }

    // public function withdrawCompleted(Request $request)
    // {
    //     $checkInvest = Payment::where('user_id', Auth::id())->where('payment_status', 1)->exists();

    //     if (!$checkInvest) {
    //         return redirect(url('dashboard'))->with('error', 'Please invest first before withdrawing.');
    //     }
    //     $general = GeneralSetting::first();

    //     // Check per-day withdrawal limit
    //     $withdrawCount = Withdraw::where('user_id', auth()->id())
    //         ->whereDate('created_at', now())
    //         ->count();

    //     if ($general->withdraw_limit <= $withdrawCount) {
    //         return back()->with('error', 'Per day withdrawal limit exceeded');
    //     }

    //     // Validate only the amount input
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //     ]);

    //     // Check if the user has an account bound
    //     $accountBind = AccountBind::where('user_id', auth()->id())->first();
    //     if (!$accountBind) {
    //         return redirect(url('account/bind'))->with('error', 'You need to bind an account before withdrawing.');
    //     }

    //     // Fetch the withdrawal method from account_binds
    //     $withdrawMethod = WithdrawGateway::findOrFail($accountBind->withdraw_method_id);

    //     // Check withdraw limits
    //     if ($request->amount < $withdrawMethod->min_amount || $request->amount > $withdrawMethod->max_amount) {
    //         return back()->with('error', 'Please follow the withdrawal limits.');
    //     }

    //     // Calculate withdrawal charge
    //     if ($withdrawMethod->charge_type == 'percent') {
    //         $withdrawCharge = ($withdrawMethod->charge * $request->amount) / 100;
    //     } else {
    //         $withdrawCharge = $withdrawMethod->charge;
    //     }

    //     $totalAmount = $request->amount + $withdrawCharge;

    //     // Check if user has enough balance (including charge)
    //     if (auth()->user()->balance < $totalAmount) {
    //         return back()->with('error', 'Insufficient balance including withdrawal charge.');
    //     }

    //     // Deduct balance
    //     auth()->user()->balance -= $totalAmount;
    //     auth()->user()->save();

    //     // Store withdrawal data
    //     Withdraw::create([
    //         'user_id' => auth()->id(),
    //         'withdraw_method_id' => $accountBind->withdraw_method_id,
    //         'transaction_id' => strtoupper(Str::random(15)),
    //         'user_withdraw_prof' => [
    //             'email' => $accountBind->email,
    //             'account_information' => $accountBind->account_information,
    //             'note' => $accountBind->note,
    //         ],
    //         'withdraw_charge' => $withdrawCharge,
    //         'withdraw_amount' => $request->amount,
    //         'status' => 0
    //     ]);

    //     return redirect(url('withdraw'))->with('success', 'Withdraw successfully completed.');
    // }


    public function accountBind()
    {
        $accountBind = AccountBind::where('user_id', auth()->id())->first();
        if ($accountBind) {
            return redirect(url('/dashboard'))->with('error', 'Some thing went wrong');
        }
        $pageTitle = "Bind Your Account";
        $withdrawMethods = WithdrawGateway::where('status', 1)->get();

        return view($this->template . 'user.account_bind', compact('pageTitle', 'withdrawMethods'));
    }

    public function storeAccountBind(Request $request)
    {
        $request->validate([
            'withdraw_method_id' => 'required|exists:withdraw_gateways,id',
            'email' => 'required|unique:account_binds,email',
            'account_information' => 'required',
            'note' => 'nullable'
        ], [
            'email.unique' => 'Account number already taken.', // Custom error message
        ]);

        AccountBind::updateOrCreate(
            ['user_id' => auth()->id(), 'withdraw_method_id' => $request->withdraw_method_id],
            [
                'email' => $request->email,
                'account_information' => $request->account_information,
                'note' => $request->note,
            ]
        );

        return redirect(url('withdraw'))->with('success', 'Account successfully bound.');
    }


    public function withdrawFetch(Request $request)
    {
        $withdraw = WithdrawGateway::findOrFail($request->id);

        return $withdraw;
    }

    public function allWithdraw(Request $request)
    {
        $pageTitle = 'All withdraw';

        $withdrawlogs = Withdraw::when($request->trx, function ($item) use ($request) {
            $item->where('transaction_id', $request->trx);
        })->when($request->date, function ($item) use ($request) {
            $item->whereDate('created_at', $request->date);
        })->where('user_id', auth()->id())->latest()->with('withdrawMethod')->paginate(10);

        return view($this->template . 'user.withdraw.withdraw_log', compact('pageTitle', 'withdrawlogs'));
    }

    public function pendingWithdraw()
    {
        $pageTitle = 'Pending withdraw';

        $withdrawlogs = Withdraw::where('user_id', auth()->id())->where('status', 0)->latest()->with('withdrawMethod')->paginate(10);

        return view($this->template . 'user.withdraw.withdraw_log', compact('pageTitle', 'withdrawlogs'));
    }

    public function completeWithdraw()
    {
        $pageTitle = 'Complete withdraw';

        $withdrawlogs = Withdraw::where('user_id', auth()->id())->where('status', 1)->latest()->with('withdrawMethod')->paginate(10);

        return view($this->template . 'user.withdraw.withdraw_log', compact('pageTitle', 'withdrawlogs'));
    }

    public function commision(Request $request)
    {

        $pageTitle = 'Complete withdraw';

        $commison = RefferedCommission::when($request->date, function ($item) use ($request) {
            $item->whereDate('created_at', $request->date);
        })->where('reffered_by', Auth::id())->latest()->paginate(10, ['*'], 'commison');

        return view($this->template . 'user.commision_log', compact('pageTitle', 'commison'));
    }

    public function PerfomanceChart()
    {
        $userId = auth()->user()->id;
        // Get the downline of the user (including indirect referrals)
        $downline = $this->getDownline($userId);

        // Active and Inactive Users Calculation
        $activeUsers = User::whereIn('id', $downline)->where('status', 1)->count();
        $inactiveUsers = User::whereIn('id', $downline)->where('status', 0)->count();

        // Total Deposit and Withdrawal for the Downline
        $downlineDeposit = Deposit::whereIn('user_id', $downline)->where('payment_status', 1)->sum('final_amount');
        $downlineWithdraw = Withdraw::whereIn('user_id', $downline)->where('status', 1)->sum('withdraw_amount');

        return view($this->template . 'user.perfomance', compact(
            'activeUsers',
            'inactiveUsers',
            'downlineDeposit',
            'downlineWithdraw',
        ));
    }
    public function getDownline($userId)
    {
        // Fetch all users who were referred by the given user
        $downline = User::where('reffered_by', $userId)->pluck('id')->toArray();

        // Recursively find the downline for all indirect referrals
        foreach ($downline as $userId) {
            $downline = array_merge($downline, $this->getDownline($userId));
        }

        return $downline;
    }
    public function TeamDetails()
    {
        $userId = auth()->user()->id;

        // **Step 1: Find Level 1 Users**
        $levelOneUsers = User::where('reffered_by', $userId)->pluck('id');

        // **Step 2: Find Level 2 Users**
        $levelTwoUsers = User::whereIn('reffered_by', $levelOneUsers)->pluck('id');

        // **Step 3: Find Level 3 Users**
        $levelThreeUsers = User::whereIn('reffered_by', $levelTwoUsers)->pluck('id');

        // **Level 1 Calculations**
        $levelOneUsersCount = $levelOneUsers->count();
        $totalInvestedAmountLevel1 = Payment::whereIn('user_id', $levelOneUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->sum('amount');

        $totalPlansLevel1 = Payment::whereIn('user_id', $levelOneUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->count();

        $totalInvestedMembersLevel1 = Payment::whereIn('user_id', $levelOneUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->distinct('user_id')
            ->count('user_id');

        // **Level 2 Calculations**
        $levelTwoUsersCount = $levelTwoUsers->count();
        $totalInvestedAmountLevel2 = Payment::whereIn('user_id', $levelTwoUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->sum('amount');

        $totalPlansLevel2 = Payment::whereIn('user_id', $levelTwoUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->count();

        $totalInvestedMembersLevel2 = Payment::whereIn('user_id', $levelTwoUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->distinct('user_id')
            ->count('user_id');

        // **Level 3 Calculations**
        $levelThreeUsersCount = $levelThreeUsers->count();
        $totalInvestedAmountLevel3 = Payment::whereIn('user_id', $levelThreeUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->sum('amount');

        $totalPlansLevel3 = Payment::whereIn('user_id', $levelThreeUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->count();

        $totalInvestedMembersLevel3 = Payment::whereIn('user_id', $levelThreeUsers)
            ->where('payment_status', 1)
            ->whereNotNull('next_payment_date') // ✅ Only running plans
            ->distinct('user_id')
            ->count('user_id');

        return view($this->template . 'user.team', compact(
            'levelOneUsersCount',
            'totalInvestedAmountLevel1',
            'totalPlansLevel1',
            'totalInvestedMembersLevel1',
            'levelTwoUsersCount',
            'totalInvestedAmountLevel2',
            'totalPlansLevel2',
            'totalInvestedMembersLevel2',
            'levelThreeUsersCount',
            'totalInvestedAmountLevel3',
            'totalPlansLevel3',
            'totalInvestedMembersLevel3'
        ));
    }

    public function returnInterest()
    {
        $general = GeneralSetting::first();
        $invests = Payment::with('plan', 'user')->where('payment_status', 1)->latest()->get();

        foreach ($invests as $invest) {

            //check_user
            $user = $invest->user;

            if ($invest->next_payment_date) {
                //check current time == paymentdate

                if ($user) {

                    if (now()->greaterThanOrEqualTo($invest->next_payment_date)) {
                        //find interest rate

                        $interestRate = $invest->plan->return_interest;
                        $returnAmount = 0;

                        if ($invest->plan->interest_status == 'percentage') {
                            $returnAmount = ($invest->amount * $interestRate) / 100;
                        }
                        if ($invest->plan->interest_status == 'fixed') {
                            $returnAmount = $invest->plan->return_interest;
                        }

                        $user->balance += $returnAmount;
                        $updatePaymentDate = $invest->next_payment_date->addHour($invest->plan->time->time);
                        $interestAmount = $returnAmount;

                        //paymentupdate on next date
                        $updatePayment = Payment::where('plan_id', $invest->plan_id)->where('next_payment_date', $invest->next_payment_date)->first();

                        $count = Payment::where('plan_id', $invest->plan_id)->where('next_payment_date', $invest->next_payment_date)->sum('pay_count');

                        if ($invest->plan->return_for == 1) {

                            if ($count < $invest->plan->how_many_time) {
                                $updatePayment->next_payment_date = $updatePaymentDate;
                                $updatePayment->interest_amount += $interestAmount;
                                $updatePayment->pay_count += 1;

                                UserInterest::create([
                                    'user_id' => $user->id,
                                    'payment_id' => $invest->id,
                                    'interest_amount' => $interestAmount,
                                    'purpouse' => 'Invest Return Commission'
                                ]);

                                sendMail('RETURN_INTEREST', [
                                    'plan' => $invest->plan->plan_name,
                                    'amount' => $returnAmount,
                                    'currency' => @$general->site_currency
                                ], $invest->user);

                                $updatePayment->save();
                                $user->save();

                                refferMoney($user->id, $user->refferedBy, 'interest', $returnAmount, $invest->plan->id);

                                if ($updatePayment->pay_count == $invest->plan->how_many_time) {
                                    $updatePayment->next_payment_date = null;
                                    $updatePayment->save();
                                }

                                if ($invest->plan->capital_back == 1) {
                                    if ($updatePayment->pay_count == $invest->plan->how_many_time) {
                                        $user->balance = $user->balance +  $invest->amount;
                                        $updatePayment->next_payment_date = null;
                                        $updatePayment->pay_count = null;
                                        $updatePayment->save();
                                        $user->save();

                                        Transaction::create([
                                            'trx' => strtoupper(Str::random(16)),
                                            'gateway_id' => 0,
                                            'amount' => $invest->amount,
                                            'currency' => @$general->site_currency,
                                            'details' => 'Capital Back For Plan ' . $invest->plan->plan_name,
                                            'charge' => 0,
                                            'type' => '+',
                                            'gateway_transaction' => '',
                                            'user_id' => $user->id,
                                            'payment_status' => 1
                                        ]);

                                        refferMoney($user->id, $user->refferedBy, 'interest', $returnAmount, $invest->plan->id);
                                    }
                                }
                            }
                        } else {

                            $updatePayment->next_payment_date = $updatePaymentDate;
                            $updatePayment->interest_amount += $interestAmount;
                            $updatePayment->pay_count += 1;

                            UserInterest::create([
                                'user_id' => $user->id,
                                'payment_id' => $invest->id,
                                'interest_amount' => $interestAmount,
                                'purpouse' => 'Invest Return Commission'
                            ]);

                            sendMail('RETURN_INTEREST', [
                                'plan' => $invest->plan->plan_name,
                                'amount' => $returnAmount,
                                'currency' => @$general->site_currency
                            ], $invest->user);

                            $updatePayment->save();
                            $user->save();
                            refferMoney($user->id, $user->refferedBy, 'interest', $returnAmount, $invest->plan->id);
                        }
                    }
                }
            }
        }
    }

    public function pendingInvest()
    {
        $data['payments'] = Payment::where('user_id', Auth::id())->where('payment_status', 2)->latest()->get();
        $data['pageTitle'] = 'Pending Invest';

        return view($this->template . 'user.pending_invest')->with($data);
    }

    public function allInvest()
    {
        $data['payments'] = Payment::where('user_id', Auth::id())->where('payment_status', '!=', 0)->latest()->get();
        $data['pageTitle'] = 'All Invest';

        return view($this->template . 'user.pending_invest')->with($data);
    }

    public function interestLog(Request $request)
    {

        $data['interestLogs'] = UserInterest::when($request->date, function ($item) use ($request) {
            $item->whereDate('created_at', $request->date);
        })->with('payment')->where('user_id', Auth::id())->latest()->get();


        $data['pageTitle'] = 'Return interest Log';

        return view($this->template . 'user.interest_log')->with($data);
    }


    public function transfer()
    {
        $pageTitle = 'Transfer Money';

        return view($this->template . 'user.transfer_money', compact('pageTitle'));
    }

    public function transferMoney(Request $request)
    {
        $general = GeneralSetting::first();

        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|gt:0'
        ]);


        $range = range($general->min_amount, $general->max_amount);

        if (!in_array($request->amount, $range)) {
            $notify[] = ['error', 'Please follow transfer Limit'];

            return back()->withNotify($notify);
        }



        $transferCount = Transaction::where('user_id', auth()->id())->where('type', 'send')->whereDate('created_at', now())->count();


        if ($transferCount >= $general->trans_limit) {
            $notify[] = ['error', 'Transfer Limit exceeded for today'];

            return back()->withNotify($notify);
        }





        $payment = Payment::where('user_id', auth()->id())->where('payment_status', 1)->count();

        if ($payment <= 0) {
            $notify[] = ['error', 'You have to invest on a plan to use Signup Balance'];

            return back()->withNotify($notify);
        }



        $receiver = User::where('email', $request->email)->first();

        if (auth()->user()->email == $request->email) {
            $notify[] = ['error', 'You can not send money to your account'];

            return back()->withNotify($notify);
        }

        if (!$receiver) {
            $notify[] = ['error', 'No User Found With this email'];

            return back()->withNotify($notify);
        }


        $commison = $general->trans_type === 'percent' ? ($request->amount * $general->trans_charge) / 100 :  $general->trans_charge;

        $totalSendAmount = $commison + $request->amount;


        if (auth()->user()->balance < $totalSendAmount) {

            $notify[] = ['error', 'Insufficient Balance'];

            return back()->withNotify($notify);
        }




        $user = auth()->user();

        $user->balance = $user->balance - $totalSendAmount;

        $user->save();

        $general = GeneralSetting::first();

        $trx = strtoupper(Str::random());


        MoneyTransfer::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'transaction_id' => $trx,
            'details' => 'Money Transfer',
            'amount' => $request->amount,
            'charge' => $commison
        ]);


        Transaction::create([
            'trx' => $trx,
            'gateway_id' => 0,
            'amount' => $request->amount,
            'currency' => @$general->site_currency,
            'details' => 'Send Money',
            'charge' => $commison,
            'type' => 'send',
            'gateway_transaction' => $trx,
            'user_id' => auth()->id(),
            'payment_status' => 1
        ]);




        $receiver->balance = $receiver->balance + $request->amount;

        $receiver->save();

        $trx = strtoupper(Str::random());

        Transaction::create([
            'trx' => $trx,
            'gateway_id' => 0,
            'amount' => $request->amount,
            'currency' => @$general->site_currency,
            'details' => 'Receive Money',
            'charge' => 0,
            'type' => 'receive',
            'gateway_transaction' => $trx,
            'user_id' => $receiver->id,
            'payment_status' => 1
        ]);



        $notify[] = ['success', 'Successfully Send Money'];

        return back()->withNotify($notify);
    }

    public function transactionLog(Request $request)
    {
        $pageTitle = 'Transaction Log';

        $transactions = Transaction::when($request->trx, function ($item) use ($request) {
            $item->where('trx', $request->trx);
        })->when($request->date, function ($item) use ($request) {
            $item->whereDate('created_at', $request->date);
        })->where('user_id', auth()->id())->where('payment_status', 1)->latest()->paginate();


        return view($this->template . 'user.transaction', compact('pageTitle', 'transactions'));
    }


    public function kyc()
    {
        if (auth()->user()->kyc == 1) {
            return redirect()->route('user.dashboard')->with('error', 'You already verify KYC');
        }
        $pageTitle = 'Kyc Verification';
        return view($this->template . 'user.kyc', compact('pageTitle'));
    }


    public function kycUpdate(Request $request)
    {
        $general = GeneralSetting::first();

        $user = auth()->user();

        if ($user->kyc == 2) {
            return redirect()->back()->with('error', 'You have already submitted KYC form');
        }


        $validation = [];
        if ($general->kyc != null) {
            foreach ($general->kyc as $params) {
                if ($params['type'] == 'text' || $params['type'] == 'textarea') {

                    $key = strtolower(str_replace(' ', '_', $params['field_name']));

                    $validationRules = $params['validation'] == 'required' ? 'required' : 'sometimes';

                    $validation[$key] = $validationRules;
                } else {

                    $key = strtolower(str_replace(' ', '_', $params['field_name']));

                    $validationRules = ($params['validation'] == 'required' ? 'required' : 'sometimes') . "|image|mimes:jpg,png,jpeg|max:2048";

                    $validation[$key] = $validationRules;
                }
            }
        }

        $data = $request->validate($validation);

        foreach ($data as $key => $upload) {

            if ($request->hasFile($key)) {

                $filename = uploadImage($upload, filePath('user'));

                $data[$key] = ['file' => $filename, 'type' => 'file'];
            }
        }




        $user->kyc_infos = $data;

        $user->kyc = 2;

        $user->save();

        return back()->with('success', 'Successfully send Kyc Information to Admin');
    }

    public function checkLevel()
    {
        $general = GeneralSetting::first();

        $payments = Payment::where('payment_status', 1)->groupBy('user_id')->selectRaw('SUM(amount) as amount, user_id')->get();


        foreach ($payments as $pay) {


            $ranking = Ranking::where('minimum_invest', '<=', $pay->amount)
                ->where('maximum_invest', '>=', $pay->amount)->where('status', 1)->first();




            if ($ranking) {


                $user = $pay->user;

                $hasRanking = $user->badges()->where('ranking_id', $ranking->id)->first();


                if (!$hasRanking) {

                    DB::table('user_rankings')->where('user_id', $user->id)->update(['is_current' => 0]);

                    UserRanking::create([
                        'user_id' => $pay->user_id,
                        'ranking_id' => $ranking->id
                    ]);

                    $user->balance = $user->balance + $ranking->bonus;

                    $user->save();

                    if ($ranking->bonus > 0) {
                        $trx = strtoupper(Str::random());
                        Transaction::create([
                            'trx' => $trx,
                            'gateway_id' => 0,
                            'amount' => $ranking->bonus,
                            'currency' => $general->site_currency,
                            'details' => 'Badge Unlock Bonus',
                            'charge' => 0,
                            'type' => '+',
                            'gateway_transaction' => '',
                            'user_id' => $pay->user_id,
                            'payment_status' => 1,
                        ]);
                    }
                }
            }
        }
    }


    public function planRemove(Request $request, $id)
    {
        $user = User::find($id);


        $payment = Payment::where('user_id', $user->id)->where('plan_id', $request->plan)->first();


        $user->balance = $user->balance + $payment->amount;

        $user->save();


        $payment->delete();

        return back()->with('success', 'Successfully Unsubscribe from plan');
    }
}
