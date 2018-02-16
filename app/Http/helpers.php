<?php

function fromSatoshi($satoshi)
{
    return bcdiv((int)(string)$satoshi, 100000000, 8);
}

function toSatoshi($decimal)
{
    return bcmul(sprintf("%.8f", (float)$decimal), 100000000, 0);
}