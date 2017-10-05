<?php

namespace Pingpong\Menus\Presenters;

use Pingpong\Menus\MenuItem;

abstract class Presenter implements PresenterInterface
{
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \Pingpong\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
    }

    /**
     * Get divider tag wrapper.
     *
     * @return string
     */
    public function getDividerWrapper()
    {
    }

    /**
     * Get header dropdown tag wrapper.
     *
     * @param \Pingpong\Menus\MenuItem $item
     *
     * @return string
     */
    public function getHeaderWrapper($item)
    {
    }

    /**
     * Get menu tag with dropdown wrapper.
     *
     * @param \Pingpong\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithDropDownWrapper($item)
    {
    }

    /**
     * Get multi level dropdown menu wrapper.
     *
     * @param \Pingpong\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMultiLevelDropdownWrapper($item)
    {
    }

    /**
     * Get child menu items.
     *
     * @param \Pingpong\Menus\MenuItem $item
     *
     * @return string
     */
    public function getChildMenuItems(MenuItem $item)
    {
        $results = '';
        foreach ($item->getChilds() as $child) {
            if ($child->hidden()) {
                continue;
            }
            
            if ($child->hasSubMenu()) {
                $results .= $this->getMultiLevelDropdownWrapper($child);
            } elseif ($child->isHeader()) {
                $results .= $this->getHeaderWrapper($child);
            } elseif ($child->isDivider()) {
                $results .= $this->getDividerWrapper();
            } else {
                $results .= $this->getMenuWithoutDropdownWrapper($child);
            }
        }

        return $results;
    }

    /**
     * 构建子级菜单
     * @method
     * @url /msc/
     * @access public
     * @param MenuItem $item
     * @return mixed
     * @author tangjun <tangjun@misrobot.com>
     * @date    2016年1月22日10:01:11
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     */
    public function getChildMenuItemsT(MenuItem $item)
    {
        $results = '';
        foreach ($item->getChilds() as $child) {
            $results .= '<li><a class="J_menuItem" href="'.url($child->url).'">'.$child->title.'</a></li>';
        }
        return $results;
    }

}
