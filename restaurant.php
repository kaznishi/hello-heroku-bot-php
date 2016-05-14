<?php

use Symfony\Component\HttpFoundation\Request;
/*****************************************************************************************
 　ぐるなびWebサービスのレストラン検索APIで緯度経度検索を実行しパースするプログラム
 　注意：緯度、経度、範囲の値は固定で入れています。
 　　　　アクセスキーはユーザ登録時に発行されたキーを指定してください。
*****************************************************************************************/
class SelectRestaurant
{
    public function findRestaurant()
    {
        return 'http://example.com';
    }

    public function gurunavi()
    {
        $restaurants = [];
        //エンドポイントのURIとフォーマットパラメータを変数に入れる
        $uri   = "http://api.gnavi.co.jp/RestSearchAPI/20150630/";
        //APIアクセスキーを変数に入れる
        $acckey= getenv('GNAVI_ACCKEY');
        //返却値のフォーマットを変数に入れる
        $format= "json";
        //緯度・経度、範囲を変数に入れる
        //緯度経度は日本測地系で五反田駅のもの。範囲はrange=2で500m以内を指定している。
        $lat   = 35.626178;
        $lon   = 139.723606;
        $range = 2;
        $lunch = 1; //ランチ営業あり
        $hitPerPage = 200;

        //URL組み立て
        $url  = sprintf("%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s", $uri, "?format=", $format, "&keyid=", $acckey, "&latitude=", $lat,"&longitude=",$lon,"&range=",$range,"&lunch=",$lunch,"&hit_per_page=",$hitPerPage);
        //API実行
        $json = file_get_contents($url);
        //取得した結果をオブジェクト化
        $obj  = json_decode($json);

        //結果をパース
        //トータルヒット件数、店舗番号、店舗名、最寄の路線、最寄の駅、最寄駅から店までの時間、店舗の小業態を出力
        foreach((array)$obj as $key => $val){
           if(strcmp($key, "rest") == 0){
               foreach((array)$val as $restArray){
                    $restaurants[] = new Restaurant($restArray->{'name'},$restArray->{'url_mobile'});
               }
           }
        }
        $count = count($restaurants);
        $selected = $restaurants[mt_rand(0,$count-1)];
        return $selected;
    }

}

class Restaurant
{
    private $name;
    private $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }
}

