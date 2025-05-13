<?php

use Sqids\Sqids;

function encodeId($id): string
{
    $sqids = new Sqids(alphabet: 'FxnXM1kBN6cuhsAvjW3Co7l2RePyY8DwaU04Tzt9fHQrqSVKdpimLGIJOgb5ZE', minLength: 10);
    return $sqids->encode([$id]);
}

function decodeId($id): int
{
    $str = 'FxnXM1kBN6cuhsAvjW3Co7l2RePyY8DwaU04Tzt9fHQrqSVKdpimLGIJOgb5ZE';
    $sqids = new Sqids(alphabet: $str, minLength: 10);
    return $sqids->decode($id)[0];
}
