<?php

namespace ecommpay\tests;

use ecommpay\Payment;
use ecommpay\PaymentPage;
use ecommpay\SignatureHandler;

class PaymentPageTest extends \PHPUnit\Framework\TestCase
{

    public function testGetUrl()
    {
        $handler = new SignatureHandler('secret');
        $paymentPage = new PaymentPage($handler);

        $payment = new Payment(100);
        $payment->setPaymentDescription('B&W purchase');
        $url = $paymentPage->getUrl($payment);

        $signature = urlencode($handler->sign($payment->getParams()));
        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment?project_id=100&payment_description=B%26W%20purchase' .
            '&signature=' . $signature,
            $url
        );
    }

    public function testSignSideEffects()
    {
        $handler = new SignatureHandler('secret');
        $paymentPage = new PaymentPage($handler);

        $payment = new Payment(1);

        $data1 = $this->arrayDeepCopy($payment->getParams());
        $url1 = $paymentPage->getUrl($payment);

        $data2 = $this->arrayDeepCopy($payment->getParams());
        $url2 = $paymentPage->getUrl($payment);

        self::assertEquals($data1, $data2);
        self::assertEquals($url1, $url2);
    }

    public function testOtherBaseUrl()
    {
        $handler = new SignatureHandler('secret');
        $paymentPage = new PaymentPage($handler, 'example.com');

        $payment = new Payment(1);
        $url = $paymentPage->getUrl($payment);

        self::assertEquals(
            'example.com?project_id=1&signature=' .
            'BsO4%2FAiBCOC65cx%2BlNImPe0achU8Peqn6bIHpSR6X%2BH2PKva5HuOttm1%2BlVrp5YGTUC3PS%2Bdh6YyokEyd%2FcOtw%3D%3D',
            $url
        );
    }

    public function testSetBaseUrl()
    {
        $testDomain = 'example.com';

        $payment = new Payment(1);
        $handler = new SignatureHandler('secret');
        $paymentPageValid = new PaymentPage($handler, $testDomain);

        $paymentPageTest = new PaymentPage($handler);
        $paymentPageTest->setBaseUrl($testDomain);

        self::assertEquals(
            $paymentPageValid->getUrl($payment),
            $paymentPageTest->getUrl($payment)
        );
    }

    /**
     * @param array $arr
     * @return array
     */
    private function arrayDeepCopy(array $arr): array
    {
        return unserialize(serialize($arr));
    }
}
