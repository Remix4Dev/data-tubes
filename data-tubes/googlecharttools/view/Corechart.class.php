<?php

/*
 * Copyright 2012 Patrick Strobel.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Id: Corechart.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\BackgroundColor;
use googlecharttools\view\options\ChartArea;
use googlecharttools\view\options\Legend;
use googlecharttools\view\options\OptionStorage;
use googlecharttools\view\options\TextStyle;
use googlecharttools\view\options\Tooltip;

/**
 * Abstract base class for all charts located in the "corechart" package.
 *
 * @package view
 */
abstract class Corechart extends Chart {

    public function getPackage() {
        return "corechart";
    }

    /**
     * Sets the chart's background color and border.
     *
     * @param BackgroundColor $background
     *              Background color and border. If set to null, the default
     *              background color and border will be used.
     */
    public function setBackgroundColor(BackgroundColor $background) {
        $this->setOption("backgroundColor", $background);
    }

    /**
     * Sets the chart's position and size relative to it's border.
     *
     * @param ChartArea $area
     *              Position and size. If set to null, the default
     *              position and size will be used.
     */
    public function setChartArea(ChartArea $area) {
        $this->setOption("chartArea", $area);
    }

    /**
     * Sets the chart element's color.
     *
     * These colors will be assigned to the chart's elements.
     *
     * @param string[] $colors
     *              An array where each value represents a color for a
     *              specific element of the chart. Any valid HTML/CSS color
     *              definition can be used (e. g. "white", #FFF, #123456).
     *              If set to null, the default colors will be used.
     */
    public function setColors($colors) {
        $this->setOptionArray("colors", $colors);
    }

    /**
     * Sets the font size.
     *
     * This font size will be used for all text-elements in the chart, as long
     * as no other size has been set for the sub text-element.
     *
     * @param int $size
     *              Font size in pixels. If set to null, the default size will be used.
     */
    public function setFontSize($size) {
        $this->setOptionNumeric("fontSize", $size);
    }

    /**
     * Sets the font name.
     *
     * This font will be used as for all text-elements in the chart, as long
     * as no other font name has been set for the sub text-element.
     *
     * @param string $name
     *              Font name. If set to null, the default font will be used.
     */
    public function setFontName($name) {
        $this->setOption("fontName", $name);
    }

    /**
     * Sets the legends position and text style.
     *
     * @param Legend $legend
     *              Position and text style. If set to null, the default position
     *              and text style will be used.
     */
    public function setLegend(Legend $legend) {
        $this->setOption("legend", $legend);
    }

    /**
     * Sets the direction, in which the data will be read.
     *
     * @param boolean $reverse
     *              If set to true, the data will be read in reverse.
     *              If set to false or null, it will be read in the order it's
     *              added to the {@link DataTable}.
     */
    public function setReverseCategories($reverse) {
        $this->setOptionBoolean("reverseCategories", $reverse);
    }

    /**
     * Sets the chart's title.
     *
     * The title will be displayed above the graph.
     *
     * @param string $title
     *              The chart's title. If set to null, no title will
     *              be displayed.
     */
    public function setTitle($title) {
        $this->setOption("title", $title);
    }

    /**
     * Sets the chart title's text style.
     *
     * @param TextStyle $textStyle
     *              The text style. If set to null, the default text style will
     *              be used.
     */
    public function setTitleTextStyle(TextStyle $textStyle) {
        $this->setOption("titleTextStyle", $textStyle);
    }

    /**
     * Sets the behaviour and style of the chart's tooltip.
     *
     * @param Tooltip $tooltip
     *              The tooltip behaviour and style. If set to null, the default
     *              behaviour and style will be used.
     */
    public function setTooltip(Tooltip $tooltip) {
        $this->setOption("tooltip", $tooltip);
    }

}

?>
