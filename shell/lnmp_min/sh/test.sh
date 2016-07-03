#!/bin/bash
test(){ 
  if [[ `getconf WORD_BIT` = '32' && `getconf LONG_BIT` = '64' ]] ; then
     echo true
  else
    echo false
  fi
}
if  `test`;then
    echo '64'
else
   echo '32'
fi


