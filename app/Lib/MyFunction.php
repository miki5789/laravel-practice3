<?php

namespace App\Lib;

class MyFunction
{

  public static function yearSelect(){
      $n = date("Y"); //現在の年
      $y = $n - 125; //125年前からスタート
      $options = []; // オプションを格納するための変数を初期化
      for($y; $y<$n; $y++){
        $options[] = $y;  
        //$options .= '<option value="'.$y.'">'.$y.'</option>'; // 文字列を結合
      }
      return $options; // ループの外でreturn
  }

  public static function monthSelect(){
      $options = []; // オプションを格納するための変数を初期化
      for($m=1; $m<=12; $m++){
         $options[] = $m;
      }
      return $options; // ループの外でreturn
  }

  public static function daySelect(){
      $options = []; // オプションを格納するための変数を初期化
      for($d=1; $d<=31; $d++){
         $options[] = $d;
      }
      return $options; // ループの外でreturn
  }

}