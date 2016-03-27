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
 * @version $Id: TreeMap.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

/**
 * Creates a tree map.
 *
 * <b>Data format:</b><br />
 * The tree map requires a {@link DataTable} with at least three columns.
 * The first column is used as the nodes ID and name. The second column sets the nodes
 * parent ID. The nodes' size is defined through the third column.
 * The fourth column is optional and is used to calculate the nodes' color.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/treemap}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class TreeMap extends Chart {

    public function getPackage() {
        return "treemap";
    }

    /**
     * Sets the color that will be used for the map's header.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setHeaderColor($color) {
        $this->setOption("headerColor", $color);
    }

    /**
     * Set the height of every node's header.
     *
     * @param int $height
     *              A non-negativ value that sets the height in pixels. If set
     *              to null, "0" will be used.
     */
    public function setHeaderHeight($height) {
        if ($height >= 0 || $height == null) {
            $this->setOptionNumeric("headerHeight", $height);
        }
    }

    /**
     * Sets the color that will be used for the node while mouse hover.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the nodes color will be
     *              lightened by 35 %.
     */
    public function setHeaderHighlightColor($color) {
        $this->setOption("headerHighlightColor", $color);
    }

    /**
     * Sets the maxiumum color that will be used when the node's color is calculated
     * by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setMaxColor($color) {
        $this->setOption("maxColor", $color);
    }

    /**
     * Sets the maximum number of node levels that will be displayed.
     *
     * @param int $color
     *              Maximum number of levels. If set to null, "1" will be used.
     */
    public function setMaxDepth($color) {
        $this->setOption("maxDepth", $color);
    }

    /**
     * Sets the maxiumum highlight color that will be used when the node's color
     * is calculated by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the nodes color will be
     *              lightened by 35 %.
     */
    public function setMaxHighlightColor($color) {
        $this->setOption("maxHighlightColor", $color);
    }

    /**
     * Sets the maxiumum number of node levels that will be displayed beyond
     * {@link setMaxDepth()}.
     *
     * @param int $color
     *              Maximum number of levels. If set to null, "1" will be used.
     */
    public function setPostMaxDepth($color) {
        $this->setOption("maxPostDepth", $color);
    }

    /**
     * Sets the maxiumum color that is accepted in the optional fourth column.
     *
     * If a color is greater than this value, than it will be set to this color.
     *
     * @param int $number
     *              The maxiumum accepted color number.
     */
    public function setMaxColorValue($number) {
        $this->setOption("maxColorValue", $number);
    }

    /**
     * Sets the "middle" color that will be used when the node's color is calculated
     * by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setMidColor($color) {
        $this->setOption("midColor", $color);
    }

    /**
     * Sets the "middle" highlight color that will be used when the node's color
     * is calculated by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the nodes color will be
     *              lightened by 35 %.
     */
    public function setMidHighlightColor($color) {
        $this->setOption("midHighlightColor", $color);
    }

    /**
     * Sets the minimum color that will be used when the node's color is calculated
     * by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setMinColor($color) {
        $this->setOption("minColor", $color);
    }

    /**
     * Sets the miniumum highlight color that will be used when the node's color
     * is calculated by using the optional fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the nodes color will be
     *              lightened by 35 %.
     */
    public function setMinHighlightColor($color) {
        $this->setOption("minHighlightColor", $color);
    }

    /**
     * Sets the minimum color that is accepted in the optional fourth column.
     *
     * If a color is smaller than this value, than it will be set to this color.
     *
     * @param int $number
     *              The maxiumum accepted color number.
     */
    public function setMinColorValue($number) {
        $this->setOption("minColorValue", $number);
    }

    /**
     * Sets the color that will be used when no value has been set in the optional
     * fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setNoColor($color) {
        $this->setOption("noColor", $color);
    }

    /**
     * Sets the color that will be used when no value has been set in the optional
     * fourth column.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the nodes color will be
     *              lightened by 35 %.
     */
    public function setNoHighlightColor($color) {
        $this->setOption("noHighlightColor", $color);
    }

    /**
     * Sets if the color gradient scale will be displayed.
     *
     * @param boolean $scale
     *              If set to true, the scale will be displayed.
     *              If set to false or null, it won't.
     */
    public function setShowScale($scale) {
        $this->setOptionBoolean("showScale", $scale);
    }

    /**
     * Sets if tooltips will be shown while mouse hover.
     *
     * @param boolean $tooltips
     *              If set to true or null, tooltips will be shown.
     */
    public function setShowTooltips($tooltips) {
        $this->setOptionBoolean("showTooltips", $tooltips);
    }

    /**
     * Sets the text color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setFontColor($color) {
        $this->setOption("fontColor", $color);
    }

    /**
     * Sets the font.
     *
     * @param string $size
     *              Font familiy's name. If set to null, the default font familiy will be used.
     */
    public function setFontFamily($family) {
        $this->setOption("fontFamily", $family);
    }

    /**
     * Sets the font size.
     *
     * @param int $size
     *              Font size in pixels. If set to null, the default size will be used
     */
    public function setFontSize($size) {
        $this->setOptionNumeric("fontSize", $size);
    }

}

?>
