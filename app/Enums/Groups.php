<?php

namespace App\Enums;

use App\Enums\MyEnum;

class Groups extends MyEnum 
{
  // If no value is given during object construction this value is used
  const __default 		= 4;
  // Our enum values, power of 2
  const root    		= 1;	// ye backend ka jay-kant shikrey :D
  const admin   		= 2;	// after root, corresponding to one service only
  const user   			= 4;	// all the lowest level users in groups available in backend 
  const membership   	= 8;	// all the backend users handling membership module
}
