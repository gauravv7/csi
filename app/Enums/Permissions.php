<?php

namespace App\Enums;

class Permissions extends MyEnum
{
  // If no value is given during object construction this value is used
  const __default = 1;
  // Our enum values, power of 2
  const owner_read   = 256;
  const owner_write  = 128;
  const owner_delete = 64;
  const group_read   = 32;
  const group_write  = 16;
  const group_delete = 8;
  const other_read   = 4;
  const other_write  = 2;
  const other_delete = 1;
}
