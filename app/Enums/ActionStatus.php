<?php

namespace App\Enums;

use App\Enums\MyEnum;

class ActionStatus extends MyEnum
{
  // If no value is given during object construction this value is used
  const __default = -1;
  // Our enum values, power of 2
  const pending         = -1;
  const cancelled       = 0;
  const approved        = 1;
  const nothing         = -2;

}
