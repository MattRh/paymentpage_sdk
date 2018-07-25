<?php

namespace ecommpay\tests\Request;

use ecommpay\Request\PaymentStatus;

class PaymentStatusTest extends \PHPUnit\Framework\TestCase
{

    public function testConstruct()
    {
        $params = [
            'project_id' => 999999999,
            'payment_id' => '666',
            'amount' => 15,
            'currency' => 'RUB',
            'ip_address' => '1.1.1.1',
            'pan' => '41111111111111',
            'year' => 2020,
            'month' => 12,
            'card_holder' => 'GENERAL AN',
            'cvv' => '777',
            'pares' => 'sada',
            'md' => '32423',
        ];

        $result = new \ArrayObject([
            'general' => [
                'project_id' => 999999999,
                'payment_id' => '666'
            ],
        ]);

        $ps = new PaymentStatus($params);
        $this->assertEquals($result->getArrayCopy(), $ps->getArrayCopy());
    }
}