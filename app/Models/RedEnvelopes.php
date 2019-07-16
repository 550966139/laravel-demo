<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedEnvelopes extends Model
{
    public static function read($money, $num)
    {
        $readJson=[];
        $parmMoney=$money;
        for ($num;$num>1;$num-1) {
            $randMoney=self::randRead($parmMoney, $num);
            $parmMoney=$parmMoney-$randMoney;
            $readJson[]=$parmMoney;
        }
        $readJson[]=$parmMoney;

        app('log')->info(\json_encode($readJson));
    }

    //随机红包
    public static function randRead($money, $num)
    {
        $min = 0.01;
        $max = $money / ($num * 2);
        $rand = mt_rand(0, 100) / 100;
        $money = $max * $rand;
        $money = ($money < $min) ? $min : $money;
        $money = sprintf('%.2f', round($money, 2));
        return $money;
    }
}
