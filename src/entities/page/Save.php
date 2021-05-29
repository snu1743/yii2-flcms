<?php


namespace fl\cms\entities\page;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\entities\page\base\NavItemPage;
use fl\cms\entities\page\base\NavItemPageBlockItem;

class Save extends BaseFlRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules();
    }

    /**
     * @inheritdoc
     */
    public function initModel(): void
    {
        $params = json_decode($this->page_params);
        $navItemPage = NavItemPage::find()
            ->select(
        NavItemPage::tableName() . '.id as nav_item_page_id, ' .
                NavItemPageBlockItem::tableName() . '.id as nav_item_page_block_item_id'
            )
            ->leftJoin(NavItemPageBlockItem::tableName(), NavItemPageBlockItem::tableName() . '.nav_item_page_id = ' . NavItemPage::tableName() . '.id')
            ->where(['nav_item_id' => $params->nav_item_id])
            ->asArray()
            ->one();
        if(is_numeric($navItemPage['nav_item_page_block_item_id'])) {
            $navItemPageBlockItem = NavItemPageBlockItem::findOne(['nav_item_page_id' => $navItemPage['nav_item_page_id']]);
            $navItemPageBlockItem->json_config_values = json_encode(['html' => base64_decode($this->content)]);
            $navItemPageBlockItem->update();
        } else {
            $navItemPageBlockItem = new NavItemPageBlockItem();
            $navItemPageBlockItem->json_config_values = json_encode(['html' => base64_decode($this->content)]);
            $navItemPageBlockItem->nav_item_page_id =  $navItemPage['nav_item_page_id'];
            $navItemPageBlockItem->block_id =  1;
            $navItemPageBlockItem->placeholder_var =  'content';
            $navItemPageBlockItem->prev_id =  0;
            $navItemPageBlockItem->json_config_cfg_values =  '{"__e":"__o"}';
            $navItemPageBlockItem->is_dirty =  1;
            $navItemPageBlockItem->save();
        }
        $this->_properties[] = [$navItemPageBlockItem->getErrors()];
    }
}