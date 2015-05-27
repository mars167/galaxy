<?php

namespace home\modules\member\controllers;

use home\modules\member\models\Wishlist;
use star\catalog\models\Item;
use yii\web\Controller;
use yii\data\Pagination;
use yii;

class WishlistController extends Controller
{
    public function actionAddWishlist()
    {
        $user_id = Yii::$app->user->id;
        $item_id = Yii::$app->request->post('item_id');
        if($item_id && $user_id) {
            $wishlist = new Wishlist();
            if(Wishlist::findOne(['item_id' => $item_id, 'user_id' => $user_id])) {
                return json_encode('You have already add the item to favorite list!');
            } else {
                $result = json_encode('Success');
            }
            $wishlist->user_id = $user_id;
            $wishlist->item_id = $item_id;
            $wishlist->created_at = time();
            if($wishlist->save()) {
                return $result;
            }
        }
    }

    public function actionGetWishlist()
    {
        $user_id = Yii::$app->user->id;
        if($user_id) {
            $items= Item::find()->innerJoin('wishlist','item.item_id = wishlist.item_id',['wishlist.user_id' => $user_id]);
            $pages = new Pagination(['totalCount' =>$items->count(), 'pageSize' => '1']);
            $items = $items->offset($pages->offset)->limit($pages->limit)->all();
            return $this->render('favorite',[
                'items' => $items,
                'pages' => $pages
            ]);
        }
    }
}