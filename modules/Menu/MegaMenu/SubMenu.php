<?php

namespace Modules\Menu\MegaMenu;

use Modules\Category\Entities\Category;

class SubMenu
{
    private $subMenu;
    private $subMenuItems;


    public function __construct($subMenu)
    {
        $this->subMenu = $subMenu;
    }


    public function url()
    {
        return $this->subMenu->url();
    }


    public function target()
    {
        if ($this->subMenu instanceof Category) {
            return '_self';
        }

        return $this->subMenu->target;
    }


    public function name()
    {
        return $this->subMenu->name;
    }


    public function background_color()
    {
        if ($this->subMenu instanceof Category) {
            return $this->subMenu->background_color;
        }

        return $this->subMenu->background_color;
    }


    public function hover_background_color()
    {
        if ($this->subMenu instanceof Category) {
            return $this->subMenu->hover_background_color;
        }

        return $this->subMenu->hover_background_color;
    }


    public function text_color()
    {
        if ($this->subMenu instanceof Category) {
            return $this->subMenu->text_color;
        }

        return $this->subMenu->text_color;
    }


    public function hover_text_color()
    {
        if ($this->subMenu instanceof Category) {
            return $this->subMenu->hover_text_color;
        }

        return $this->subMenu->hover_text_color;
    }


    public function after_color()
    {
        return $this->subMenu->after_color;
    }


    public function hasBackgroundImage()
    {
        return !is_null($this->backgroundImageUrl());
    }


    public function backgroundImageUrl()
    {
        if ($this->subMenu instanceof Category) {
            return $this->subMenu->logo && $this->subMenu->logo->exists ? $this->subMenu->logo->path : null;
        }

        return $this->subMenu->background_image->path ?? null;
    }


    public function hasItems()
    {
        return $this->items()->isNotEmpty();
    }


    public function items()
    {
        if (!is_null($this->subMenuItems)) {
            return $this->subMenuItems;
        }

        return $this->subMenuItems = $this->subMenu->items->map(function ($item) {
            return new SubMenu($item);
        });
    }


    /**
     * Check if the submenu is a category
     */
    public function isCategory()
    {
        return $this->subMenu instanceof Category;
    }


    /**
     * Get the category if this submenu is a category
     */
    public function getCategory()
    {
        return $this->isCategory() ? $this->subMenu : null;
    }


    /**
     * Get the logo if this submenu is a category
     */
    public function getLogo()
    {
        if ($this->isCategory() && $this->subMenu->logo && $this->subMenu->logo->exists) {
            return $this->subMenu->logo;
        }

        return null;
    }


    /**
     * Check if the submenu has an icon
     */
    public function hasIcon()
    {
        if ($this->isCategory()) {
            return false; // Categories don't have icons, they have logos
        }

        return !is_null($this->icon());
    }


    /**
     * Get the icon for the submenu
     */
    public function icon()
    {
        if ($this->isCategory()) {
            return null; // Categories don't have icons
        }

        return $this->subMenu->icon ?? null;
    }
}
