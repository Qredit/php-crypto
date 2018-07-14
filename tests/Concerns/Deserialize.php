<?php

declare(strict_types=1);

/*
 * This file is part of Ark PHP Crypto.
 *
 * (c) Ark Ecosystem <info@ark.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArkEcosystem\Tests\Crypto\Concerns;

use ArkEcosystem\Crypto\Deserializer;
use ArkEcosystem\Crypto\Serializer;
use ArkEcosystem\Crypto\Transaction;

trait Deserialize
{
    protected function assertDeserialized(array $expected, array $keys, int $network = 30): object
    {
        $actual = Deserializer::new($expected['serialized'])->deserialize();

        $this->assertSame(1, $actual->version);
        $this->assertSame($network, $actual->network);
        $this->assertSame($expected['serialized'], Serializer::new($actual->toArray())->serialize()->getHex());
        $this->assertSameTransactions($expected, $actual, $keys);

        return $actual;
    }

    protected function object_to_array(object $value): array
    {
        return json_decode(json_encode($value), true);
    }

    private function assertSameTransactions(array $expected, Transaction $actual, array $keys): void
    {
        $expected = array_only($expected['data'], $keys);
        $actual   = array_only($this->object_to_array($actual), $keys);

        $this->assertSame($expected, $actual);
    }
}
