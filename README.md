# Jeeb.io Laravel Gateway
<div dir="rtl" align="right">
پکیجی جهت استفاده از درگاه پرداخت رمزارزها توسط وبسایت [jeeb.io](https://jeeb.io)  
</div>

## ملزومات
- laravel 7+
- curl

## نصب
<p dir="rtl">با دستور زیر آخرین نسخه‌ی پکیج رو نصب کنید</p> 

```composer require aries\jeeb.io```  

<p dir="rtl">حالا لازمه که با دستور زیر مایگریشن‌های مورد نیاز پکیج رو منتشر کنید</p>

```php artisan vendor:publish --provider='Aries\Jeeb\JeebServiceProvider'```  

<p dir="rtl">و حالا لازمه که مایگریشن‌ها رو اجرا کنید  </p>

```php artisan migrate```

## استفاده
<div dir="rtl">
برای کار کردن درست پکیج به سه روت نیاز داریم
- یک روت که در اون تراکنش رو ایجاد کنیم
- یک روت برای زمانی که از درگاه برمیگردیم به سایت خودمون
- و یک روت برای پردازش وبهوک ارسالی از طرف سرور جیب  
> روت دوم اختیاریه، در صورتی که نخواید کاربر رو برای پرداخت به درگاه جیب بفرستید می‌تونید کیف پول ایجاد شده توسط جیب رو توی سایت خودتون نشون بدین و کاربر رو به درگاه هدایت نکنید.
</div>

### ایجاد تراکنش
<div dir="rtl">
شما می‌تونید با استفاده از فساد Aries\Jeeb\Facades\Jeeb و متود pay یک ترانش جدید ایجاد کنید:
</div>

```php
<?php
use Aries\Jeeb\Facades\Jeeb;
use Illuminate\Support\Facades\Route;

Route::get('/jeeb/pay', function() {
    return Jeeb::pay()
        ->order(1234) # شماره سفارش
        ->from('irr') # ارز مبدا که در اینجا ریال ایران هستش
        ->with('btc/ltc/doge') # ارزهای قابل پذیرش که با / از هم تفکیک شده
        ->amount(1000000) # مبلغ تراکنش بر پایه‌ی ارز مبدا که اینجا یک میلیون ریال هست
        ->callback('YOUR/CALLBACK/URL') # آدرس روتی که پس از پرداخت در درگاه جیب بهش برمی‌گردیم
        ->webhook('YOUR/WEBHOOK/URL') # آدرس روتی که قراره وب‌هوک رو پردازش کنه،
        ->language('fa') # زبان رابط کاربری درگاه جیب
        ->process() # ارسال درخواست به سرور جیب
        ->redirect(); # انتقال کاربر به درگاه جیب
});
```
