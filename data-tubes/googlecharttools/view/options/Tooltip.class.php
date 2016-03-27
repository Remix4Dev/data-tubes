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
 * @version $Id: Tooltip.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Tooltip defines what will be visbile when the cursor is over an element of the chart.
 *
 * @package view
 * @subpackage options
 */
class Tooltip extends OptionStorage {

    /**
     * Percentage and absolute values will be displayed.
     */
    const TEXT_BOTH = "both";

    /**
     * Only absolute values are displayed.
     */
    const TEXT_VALUE = "value";

    /**
     * Only percentage values are displayed.
     */
    const TEXT_PERCENTAGE = "percentage";

    /**
     * Tooltip will be displayed when the cursor is over a element.
     */
    const TRIGGER_HOVER = "hover";

    /**
     * No tooltip will be displayed.
     */
    const TRIGGER_NONE = "none";

    /**
     * Creates a new Tooltip option-set.
     *
     * @param boolean $showColorCode
     *              If set to to true, the chart element's color will be shown
     *              inside the tooltip.
     * @param string $text
     *              This is only recognized in {@link PieChart}s. Defines which information
     *              will be shown in the tooltip. Must be one of the <i>TEXT_...</i>
     *              constants or null.
     * @param TextStyle $textStyle
     *              The text style used for the tooltip.
     * @param string $trigger
     *              Defines which interaction causes the tooltip to be shown.
     *              Must be one of the <i>TRIGGER_...</i> constants or null.
     */
    public function __construct($showColorCode = null, $text = null, $textStyle = null, $trigger = null) {
        $this->setOptionBoolean("showColorCode", $showColorCode);
        $this->setText($text);
        $this->setOption("textStyle", $textStyle);
        $this->setTrigger($trigger);
    }

    /**
     * Sets the visibility of the element's color.
     *
     * @param boolean $color
     *              If set to to true, the chart element's color will be shown
     *              inside the tooltip.
     *              If set to false or null, they won't.
     */
    public function setShowColorCode($showColorCode) {
        $this->setOptionBoolean("showColorCode", $showColorCode);
    }

    /**
     * Sets which information will be shown in the tooltip of pie-charts.
     *
     * @param string $text
     *              This is only recognized in {@link PieChart}s. Defines which information
     *              will be shown in the tooltip. Must be one of the <i>TEXT_...</i>
     *              constants or null.
     */
    public function setText($text) {
        if ($text != self::TEXT_BOTH && $text != self::TEXT_PERCENTAGE &&
                $text != self::TEXT_VALUE && $text != null) {
            throw new \InvalidArgumentException("Argument \"text\" is invalid");
        }
        $this->setOption("text", $text);
    }

    /**
     * Sets the text style of the chart's tooltip.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default style will be used.
     */
    public function setTextStyle(TextStyle $textStyle) {
        $this->setOption("textStyle", $textStyle);
    }

    /**
     * Sets which interaction causes the tooltip to be shown.
     *
     * @param string $trigger
     *              Defines which interaction causes the tooltip to be shown.
     *              Must be one of the <i>TRIGGER_...</i> constants or null.
     */
    public function setTrigger($trigger) {
        if ($trigger != self::TRIGGER_HOVER && $trigger != self::TRIGGER_NONE &&
                $trigger != null) {
            throw new \InvalidArgumentException("Argument \"trigger\" is invalid");
        }
        $this->setOption("trigger", $trigger);
    }

}

?>
