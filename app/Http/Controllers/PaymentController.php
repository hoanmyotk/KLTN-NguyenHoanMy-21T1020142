<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/vnpay_return";
        $vnp_TmnCode = "9WK0CDH1";
        $vnp_HashSecret = "LI2LO5XBMEIYPNZPH4VPPSJ5OHEY4UXZ";
        
        $vnp_TxnRef = time(); 
        $vnp_OrderInfo = "Thanh toán thử nghiệm";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = 100000 * 100; // Đúng với giá trị bạn đang sử dụng
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        
        if ($vnp_BankCode) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
    }
    
    public function vnpay_return(Request $request)
    {
        $vnp_HashSecret = "LI2LO5XBMEIYPNZPH4VPPSJ5OHEY4UXZ"; // Secret Key của bạn
        $vnpData = $request->all();
        $vnpSecureHash = $vnpData['vnp_SecureHash']; // Chữ ký từ VNPay
        unset($vnpData['vnp_SecureHash']); // Xóa để tránh ảnh hưởng khi tạo hash mới

        // Sắp xếp dữ liệu theo thứ tự key tăng dần
        ksort($vnpData);
        
        // Tạo chuỗi `hashdata` sử dụng urlencode giống `vnpay_payment()`
        $hashdata = "";
        $i = 0;
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Tạo chữ ký để kiểm tra
        $generatedHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Ghi log để debug nếu có lỗi
        Log::info("VNPay Data: ", $vnpData);
        Log::info("Generated Hash: " . $generatedHash);
        Log::info("Expected Hash from VNPay: " . $vnpSecureHash);

        // So sánh chữ ký
        if ($generatedHash === $vnpSecureHash) {
            if ($vnpData['vnp_ResponseCode'] == '00' && $vnpData['vnp_TransactionStatus'] == '00') {
                return response()->json([
                    'message' => 'Thanh toán thành công!',
                    'data' => $vnpData
                ]);
            } else {
                return response()->json([
                    'message' => 'Thanh toán thất bại!',
                    'data' => $vnpData
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Chữ ký không hợp lệ!',
                'data' => $vnpData
            ], 400);
        }
    }
}