# Jeeb.io Laravel Gateway
<div dir="rtl" align="right">
پکیجی جهت استفاده از درگاه پرداخت رمزارزها توسط وبسایت <a href="https://jeeb.io">jeeb.io</a>
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

<ol>
<li>یک روت که در اون تراکنش رو ایجاد کنیم</li>
<li>یک روت برای زمانی که از درگاه برمیگردیم به سایت خودمون</li>
<li>و یک روت برای پردازش وبهوک ارسالی از طرف سرور جیب </li>
</ol>
<blockquote>روت دوم اختیاریه، در صورتی که نخواید کاربر رو برای پرداخت به درگاه جیب بفرستید می‌تونید کیف پول ایجاد شده توسط جیب رو توی سایت خودتون نشون بدین و کاربر رو به درگاه هدایت نکنید.</blockquote>
</div>

### رفع مشکل توکن روت‌ها
<div dir="rtl">
پاسخ‌های ارسالی از طرف سرور جیب به صورت post هستن و این با csrf_token لاراول به مشکل می‌خوره.<br />
برای حل این مشکل باید روت‌های <code>callback</code> و <code>webhook</code> رو در مسیر <code>app\http\Middleware\VerifyCrsfToken.php</code> استثنا کرد:
</div>

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/jeeb/callback',
        '/jeeb/webhook'
    ];
}
```

### ایجاد تراکنش
<div dir="rtl">
شما می‌تونید با استفاده از فساد <code>Aries\Jeeb\Facades\Jeeb</code> و متود <code>pay</code> یک ترانش جدید ایجاد کنید:
</div>

```php
<?php
use Aries\Jeeb\Facades\Jeeb;
use Illuminate\Support\Facades\Route;

Route::get('/jeeb/pay', function() {
    return Jeeb::pay()
        ->order(1234)                   # شماره سفارش
        ->from('irr')                   # ارز مبدا که در اینجا ریال ایران هستش
        ->with('btc/ltc/doge')          # ارزهای قابل پذیرش که با / از هم تفکیک شده
        ->amount(1000000)               # مبلغ تراکنش بر پایه‌ی ارز مبدا که اینجا یک میلیون ریال هست
        ->callback('YOUR/CALLBACK/URL') # آدرس روتی که پس از پرداخت در درگاه جیب بهش برمی‌گردیم
        ->webhook('YOUR/WEBHOOK/URL')   # آدرس روتی که قراره وب‌هوک رو پردازش کنه
        ->language('fa')                # زبان رابط کاربری درگاه جیب
        ->process()                     # ارسال درخواست به سرور جیب
        ->redirect();                   # انتقال کاربر به درگاه جیب
});
```

### بازگشت از درگاه (callback)
<div dir="rtl">
 با استفاده از فساد <code>Aries\Jeeb\Facades\Jeeb</code> و متود <code>callback</code> پاسخ سرور جیب رو هندل کنید.<br />
 همچنین با استفاده از فساد <code>Aries\Jeeb\Facades\State</code> و متود <code>message</code> مقدار stateId برگشت داده شده رو برای کاربر ترجمه کنید.
</div>

```php
<?php
use Aries\Jeeb\Facades\Jeeb;
use Aries\Jeeb\Facades\State;
use Illuminate\Support\Facades\Route;

Route::get('/jeeb/callback', function() {
    $response = Jeeb::callback()->process();
    $message = State::message($response->stateId);

    return view('path.to.your.callback.view', compact('response', 'message'));
});
```

### وب‌هوک
<div dir="rtl">
شما می‌تونید با استفاده از فساد <code>Aries\Jeeb\Facades\Jeeb</code> و متود <code>webhook</code> پاسخ‌های ارسال شده از طرف سرور جیب رو هندل کنید.<br />
لازم به ذکره که به دلیل زمانبر بودن تایید تراکنش در بلاکچین باید منتظر ارسال پاسخ تایید از طرف سرور جیب به صورت وب‌هوک باشید و در صورت تایید شدن تراکنش با ststeId برابر ۴ می‌تونید محصول رو به کاربر پرداخت کنید.<br />
مبالغ پرداختی بیشتر یا کمتر از مقدار مشخص شده توسط شما بصورت خودکار به کاربر برگشت داده می‌شه.
</div>

```php
<?php
use Aries\Jeeb\Facades\Jeeb;
use Illuminate\Support\Facades\Route;

Route::get('/jeeb/webhook', function() {
    return Jeeb::webhook();
});
```

## مشاهده‌ی لیست تراکنش‌ها
<div dir="rtl">
برای مشاهده‌ی لیست تراکنش‌ها به تفکیک وضعیت پرداخت می‌تونید از فساد <code>Aries\Jeeb\Facades\Jeeb</code> و متود <code>transaction</code> استفاده کنید:
</div>

```php
use Aries\Jeeb\Facades\Jeeb;

public function transactions(Request $request) {
        switch($request->input('type')) {
            case 'confirmed':
                # تراکنش‌هایی که در بلاکچین تایید شدند.
                return Jeeb::transaction()->confirmed()->get();
            case 'unConfirmed':
                # تراکنش‌هایی که در بلاکچین تایید شدند ولی شما هنوز آن‌ها را تایید نکرده اید.
                return Jeeb::transaction()->unConfirmed()->get();
            case 'pending':
                # تراکنش‌هایی که هنوز در بلاکچین تایید نشده اند.
                return Jeeb::transaction()->pending()->get();
            case 'rejected': 
                # تراکنش‌هایی که توسط کاربر لغو شده یا در زمان مقرر پرداخت نشده‌اند
                return Jeeb::transaction()->rejected()->get();
            case 'less': 
                # تراکنش‌هایی که مقدار پرداخت شده آن توسط کاربر کمتر از مقدار مشخص شده توسط شماست
                return Jeeb::transaction()->lessPaid()->get();
            case 'over': 
                # تراکنش‌هایی که مقدار پرداخت شده آن توسط کاربر بیشتر از مقدار مشخص شده توسط شماست
                return Jeeb::transaction()->overPaid()->get();
            case 'all': 
                # لیست تمام تراکنش‌ها
                return Jeeb::transaction()->get()->load('wallets');
        }
    }
```

<div dir="rtl">
مقدار بازگشتی توسط این متود یک مدل استاندارد لاراولی بوده و می‌تونید مثل یک مدل استاندارد باهاش برخورد کنید، مثلا می‌تونید با متود <code>paginate</code> اون رو صفحه بندی کنید.
</div>