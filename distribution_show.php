<?php
namespace yuu_1st;

/**
 * ターミナル上で、一次元分布を可視化します。
 *
 * @param array $arr 分布配列。キーは全て整数値である必要があります。
 * @param integer $interval 出力間隔。値は切り捨てで圧縮されます。
 * @param int $maxwidth 出力時の横幅。指定文字数を超えると改行されます。
 * @return void
 */
function distribution_show(array $arr, int $interval = 1, int $maxwidth = PHP_INT_MAX) : void
{
    ksort($arr); 
    $min = array_key_first($arr);
    $max = array_key_last($arr);
    $value_max = 0;
    $array = [];
    for ($i = $min; $i <= $max; $i++) {
        $key = (int)floor(($i - $min) / $interval);
        $array[$key] ??= 0;
        $array[$key] += isset($arr[$i]) ? $arr[$i] : 0;
        $value_max = $array[$key] > $value_max ? $array[$key] : $value_max;
    }
    $console = ""; 
    $co = 0;
    $coplus = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] > 0) {
            $color = (int)floor((255 - 232) * ($array[$i] * 1.0 / $value_max) + 232);
            printf("\e[38;5;%dm%s\e[m", $color, "|");
        } else {
            printf(" ");
        }
        if ($coplus % 10 == 0) {
            $key = $min + $i * $interval;
            $console .= "↑" . $key;
            $coplus += mb_strlen($key) + 1;
        } elseif ($co == $coplus) {
            $console .= " ";
            $coplus++;
        }
        $co++;
        if ($co % $maxwidth === 0) {
            printf("\n%s\n", $console);
            $console = "";
        }
    }
    printf("\n%s\n", $console);
}
