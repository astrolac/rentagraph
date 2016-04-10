<?php
    $megatron = 'megatron';
    $testarr = array (
      'one' => 'vone',
      'two' => 'vtwo',
      'three' => array ('three1' => 'vthree1', array('1' => $megatron.'%$&^#%', '2' => '8($703)&$*80'), 'three2' => 'vthree2'),
      'four' => 'vfour'
    );

    function echoarr ($inarr, $arrlevel) {
        foreach ($inarr as $value) {
          if (is_array($value)) {
            echoarr($value, $arrlevel + 1);
          } else {
            for ($cntr=0; $cntr<$arrlevel; $cntr++) {
              echo "  +---";
            }
            echo $value."\n";
          }
        }
    }

    echo "\n";
    echoarr($testarr, 0);
    echo "\n";
