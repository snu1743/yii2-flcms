<?php


namespace fl\cms\entities\pages;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use \fl\cms\helpers\page\Create as PageCreate;
use yii\base\Exception;


class Create extends BaseFlRecord
{
    /**
     * @return array|mixed
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
        $this->setParentCmsPage();
        $this->setPath();
        $this->setTitle();

        $session = yii::$app->session;
        $params = [
            'path' => $this->path,
            'name' => $this->name,
            'title' => $this->title,
            'parent_id' => $this->parent_cms_page['id'],
            'user_id' => $session['fl_cms']['user_id'],
            'group_ids' => [],
            'tree_id' => $this->parent_cms_page['tree_id'],
            'body' => "<h1><i>Новая страница</i> $this->path</h1>"
        ];
        $this->_properties[] = PageCreate::exec($params);
    }

    private function setParentCmsPage()
    {
        $this->parent_cms_page = json_decode($this->parent_cms_page, true);
        if (!is_array($this->parent_cms_page)) {
            throw new Exception('The parent_cms_page property must be an array');
        }
    }

    private function setPath()
    {
        if (!$this->alias) {
            $this->alias = $this->name;
        }
        $this->alias = str_replace(['/','\\', '?', '&', ':'], '', $this->alias);
        $this->alias = preg_replace('/[\s]{2,}/', ' ', $this->alias);
        $this->alias = trim($this->alias, ' ');
        $this->alias = str_replace(' ', '_', $this->alias);
        $this->alias = strtolower($this->alias);
        if ($this->parent_cms_page['path']) {
            $this->path = "{$this->parent_cms_page['path']}/{$this->alias}";
        } else {
            $this->path = $this->alias;
        }
    }

    private function setTitle()
    {
        $this->name = str_replace(['/','\\'], '', $this->name);
        $this->name = preg_replace('/[\s]{2,}/', ' ', $this->name);
        $this->name = trim($this->name, ' ');
        if ($this->parent_cms_page['name']) {
            $this->name = "{$this->parent_cms_page['name']}/{$this->name}";
        } else {
            $this->name = $this->name;
        }
    }
}