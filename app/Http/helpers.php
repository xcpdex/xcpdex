<?php

function fromSatoshi($satoshi)
{
    return bcdiv((int)(string)$satoshi, 100000000, 8);
}

function fromCents($cents)
{
    return bcdiv((int)(string)$cents, 100, 2);
}

function toSatoshi($decimal)
{
    return bcmul(sprintf("%.8f", (float)$decimal), 100000000, 0);
}