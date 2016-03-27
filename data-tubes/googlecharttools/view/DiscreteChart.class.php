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
 * @version $Revision$
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\Animation;

/**
 * A DiscreteChart is used as abstract base class for all charts whose x-axis
 * represents discrete values.
 *
 * @package view
 */
abstract class DiscreteChart extends CartesianChart {

    /**
     * Focus is given to a single data point (On cell in the {@link DataTable}).
     */
    const FOCUS_DATUM = "datum";

    /**
     * Focus is given to all data point that belong to the same x-value
     * (On row in the {@link DataTable}).
     */
    const FOCUS_CATEGORY = "category";

    /**
     * Sets which elements will recevie the focus on mouse hover and on mouse click.
     *
     * @param string $target
     *              The element that will receive the focus. Must be on of the
     *              <i>FOCUS_...</i> constants or null. If set null, the
     *              focus will be on one single data point.
     * @throws \InvalidArgumentException
     *              Thrown, if the given focus is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setFocusTarget($target) {
        if ($target != self::FOCUS_CATEGORY && $target != self::FOCUS_DATUM &&
                $target != null) {
            throw new \InvalidArgumentException("Argument \"target\" is invalid");
        }
        $this->setOption("focusTarget", $target);
    }
}
?>
