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
 * @version $Id: HAxis.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The HAxis defines the appearance of the chart's horizontal axis.
 *
 * The options set in this option-set are only valid in discrete charts.
 *
 * @package view
 * @subpackage options
 */
class HAxis extends Axis {

    /**
     * Sets if the horizontal axis' label will be displayed in an angle.
     *
     * @param boolean $slanted
     *              If set to true, the text will be displayed in an angle.
     *              If set to false or null, the text will be upright.
     */
    public function setSlantedText($slanted) {
        $this->setOptionBoolean("slantedText", $slanted);
    }

    /**
     * Sets the horizontal axis' label angel.
     *
     * Is only valid, if {@link setSlantedText()} is set to true.
     *
     * @param int $angle
     *              The angle used to display the label in deg. If set to null,
     *              the default angle will be used (30 deg).
     */
    public function setSlantedTextAngle($angle) {
        $this->setOptionNumeric("slantedTextAngle", $angle);
    }

    /**
     * Sets the number of alternation levels.
     *
     * @param int $alternation
     *              Number of alternation levels. If set to null, two levels
     *              will be used. Must be greater or equal than "1".
     */
    public function setMaxAlternation($alternation) {
        if ($alternation >= 1) {
            $this->setOptionNumeric("maxAlternation", $alternation);
        }
    }

    /**
     * Sets which label's will be displayed (Every label, every second, ...).
     *
     * @param int $every
     *              Which label's will be displayed. If set to null, every
     *              label will be displayed. Must be greater or equal than "1".
     */
    public function setShowTextEvery($every) {
        if ($every >= 1 || $every == null) {
            $this->setOptionNumeric("showTextEvery", $every);
        }
    }

}

?>
