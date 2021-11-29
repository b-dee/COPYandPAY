<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\PaymentController;

class PaymentControllerTest extends TestCase
{
    /* 
        I usually aim for as close to 100% code coverage as possible for unit tests,
        so that I can tell immediately if regressions creep in even before checking in.

        Here I'll just write one test for demonstration, rather than fully covering the entire test app.
    */
    public function testCacheKey()
    {
        $prefix = 'prefix_';
        $params = [3, '2', true]; // 3_2_1_ before md5
        $key = PaymentController::cacheKey($params, $prefix);
        $this->assertSame('prefix_432d4d8e6c3df2a8bd94668f5537587a', $key);
    }
}