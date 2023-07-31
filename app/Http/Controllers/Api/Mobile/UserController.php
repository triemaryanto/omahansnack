<?php

namespace App\Http\Controllers\Api\Mobile;

use Exception;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction as Transactions;

class UserController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required',
        ]);

        $transaction = Transactions::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => ''
        ]);

        // Konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $transaction = Transactions::with(['product', 'user'])->find($transaction->id);

        $midtrans = array(
            'transaction_details' => array(
                'order_id' =>  $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ),
            'customer_details' => array(
                'first_name'    => $transaction->user->name,
                'email'         => $transaction->user->email
            ),
            'enabled_payments' => array('gopay', 'bank_transfer'),
            'vtweb' => array()
        );

        try {
            // Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Redirect ke halaman midtrans
            return ResponseFormatter::success($transaction, 'Transaksi berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }
    }
    public function transactions(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $food_id = $request->input('food_id');
        $status = $request->input('status');

        if ($id) {
            $transaction = Transactions::with(['product', 'user'])->find($id);

            if ($transaction)
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
        }

        $transaction = Transactions::with(['product', 'user'])->where('user_id', auth()->guard('api')->user()->id);

        if ($food_id)
            $transaction->where('food_id', $food_id);

        if ($status)
            $transaction->where('status', $status);

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }
    /**
     * getUser
     *
     * @return void
     */
    public function getUser()
    {
        //response data "user" yang sedang login
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'address'     => 'required',
            'houseNumber'     => 'required',
            'phoneNumber'     => 'required',
            'city'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name'       => $request->name,
            'roles' => 'USER',
            'address'     => $request->address,
            'houseNumber'     => $request->houseNumber,
            'phoneNumber'     => $request->phoneNumber,
            'city'     => $request->city,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Data Users berhasil di simpan');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data User Gagal Di Simpan', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->first();

        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Detail Data User');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Detail Data User Tidak DItemukan!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users,email,' . $user->id,
            'address'     => 'required',
            'houseNumber'     => 'required',
            'phoneNumber'     => 'required',
            'city'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/users/' . basename($user->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());

            $user->update([
                'image'       => $image->hashName(),
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        } else
            $user->update([
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        if ($request->password == "") {

            //update user without password
            $user->update([
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        } else
            $user->update([
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Data User Berhasil Diupdate');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data User Gagal Diupdate!', 500);
    }
}
