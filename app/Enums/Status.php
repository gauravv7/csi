<?php

namespace App\Enums;

class Status extends SplEnum
{
  // If no value is given during object construction this value is used
  const __default = 1;
  // Our enum values, power of 2
  const withdraw     = 64;
  const join         = 32;
  const pending      = 16;
  const cancelled    = 8;
  const active       = 4;
  const inactive     = 2;
  const deleted      = 1;

}
