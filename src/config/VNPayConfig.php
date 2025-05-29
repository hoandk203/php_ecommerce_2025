<?php
class VNPayConfig {
    // VNPAY Configuration
    public static $vnp_TmnCode = "VAAEFGYL"; // Mã website tại VNPAY
    public static $vnp_HashSecret = "NPQ3W1BYSMJWE2TQMQTWZCQPO9P4GXG3"; // Chuỗi bí mật
    public static $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    public static $vnp_ReturnUrl = "/vnpay-return"; // URL trả về sau khi thanh toán
    public static $vnp_ApiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
    public static $vnp_Version = "2.1.0";
    public static $vnp_Command = "pay";
    public static $vnp_CurrCode = "VND";
    public static $vnp_Locale = "vn";
    public static $vnp_OrderType = "billpayment";
}
?>
