<?php

namespace Modules\Menu\MegaMenu;

use Modules\Category\Entities\Category;

class Menu
{
    private $menu;
    private $subMenus;


    public function __construct($menu)
    {
        $this->menu = $menu;
    }


    public function isFluid()
    {
        return $this->menu->is_fluid;
    }


    public function url()
    {
        return $this->menu->url();
    }


    public function hasIcon()
    {
        return !is_null($this->icon());
    }


    public function icon()
    {
        return $this->menu->icon;
    }


    public function hasBackgroundImage()
    {
        return !is_null($this->backgroundImage());
    }


    public function backgroundImage()
    {
        return $this->menu->background_image->path;
    }


    public function backgroundImageUrl()
    {
        return $this->menu->background_image->path ?? null;
    }


    public function target()
    {
        return $this->menu->target;
    }


    public function name()
    {
        return $this->menu->name;
    }


    public function background_color()
    {
        return $this->menu->background_color;
    }


    public function hover_background_color()
    {
        return $this->menu->hover_background_color;
    }


    public function text_color()
    {
        return $this->menu->text_color;
    }


    public function hover_text_color()
    {
        return $this->menu->hover_text_color;
    }


    public function after_color()
    {
        return $this->menu->after_color;
    }


    public function hasSubMenus()
    {
        return $this->subMenus()->isNotEmpty();
    }


    public function isAllCategoriesType()
    {
        return $this->menu->isAllCategoriesType();
    }

    public function isAllCategoriesMenu()
{
    return $this->menu->isAllCategoriesType();
}


    public function getAllCategories()
    {
        return $this->menu->getAllCategories();
    }


    public function subMenus()
    {
        if (!is_null($this->subMenus)) {
            return $this->subMenus;
        }

        return $this->subMenus = $this->getSubMenus()->map(function ($subMenu) {
            return new SubMenu($subMenu);
        });
    }


    private function getSubMenus()
    {
        if ($this->menu->isCategoryType()) {
            return $this->getCategorySubMenus();
        }

        if ($this->menu->isAllCategoriesType()) {
            return $this->getAllCategoriesSubMenus();
        }

        return $this->menu->items ?? collect();
    }


    private function getCategorySubMenus()
    {
        return Category::tree()
            ->where('id', $this->menu->category_id)
            ->first()
            ->items ?? collect();
    }


    private function getAllCategoriesSubMenus()
    {
        return Category::tree();
    }
}