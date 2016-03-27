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
 * @version $Id: Animation.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Animation class specifies the animation that will be shown when the chart is
 * re-drawn.
 *
 * @package view
 * @subpackage options
 */
class Animation extends OptionStorage {

    /**
     * Constant animation speed.
     */
    const EASING_LINEAR = "linear";

    /**
     * Ease in. From slow to fast.
     */
    const EASING_IN = "in";

    /**
     * Ease out. From fast to slow.
     */
    const EASING_OUT = "out";

    /**
     * Ease in and out. From slow to fast and then to slow again.
     */
    const EASING_IN_AND_OUT = "inAndOut";

    /**
     * Creates a new Animation option-set
     *
     * @param int $duration
     *              The animation duration in milliseconds.
     * @param string $easing
     *              The easing function used for the animation. Must be on of the
     *              <i>EASING_...</i> constants or null.
     * @throws \InvalidArgumentException
     *              Thrown, if the given position is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function __construct($duration = null, $easing = null) {
        $this->setOptionNumeric("duration", $duration);
        $this->setEasing($easing);
    }

    /**
     * Sets the animation duration.
     *
     * @param int $duration
     *              The animation duration in milliseconds. Must be greater or
     *              equal than "0". If set to null, no animation will be used.
     */
    public function setDuration($duration) {
        if ($duration >= 0 || $duration == null)
            $this->setOptionNumeric("duration", $duration);
    }

    /**
     * Sets the easing function.
     *
     * @param string $easing
     *              The easing function used for the animation. Must be on of the
     *              <i>EASING_...</i> constants or null. If set to null, the default
     *              function will be used.
     * @throws \InvalidArgumentException
     *              Thrown, if the given easing function is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setEasing($easing) {
        if ($easing != self::EASING_IN && $easing != self::EASING_IN_AND_OUT &&
                $easing != self::EASING_LINEAR && $easing != self::EASING_OUT &&
                $easing != null) {
            throw new \InvalidArgumentException("Argument \"easing\" is invalid");
        }
        $this->setOption("easing", $easing);
    }

}

?>
