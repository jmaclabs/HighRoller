<?php
/**
 * Author: jmac
 * Date: 9/24/11
 * Time: 3:28 PM
 * Desc: HighRoller Plot Options By Chart Type
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
 * See the NOTICE file distributed with this work for additional information
 * regarding copyright ownership.  Gravity.com licenses this file to you use
 * under the Apache License, Version 2.0 (the License); you may not this
 * file except in compliance with the License.  You may obtain a copy of the
 * License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an AS IS BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

class HighRollerPlotOptionsByChartType {

  public $allowPointSelect = false;
  public $showInLegend = true;
  public $shadow = true;
  public $formatter;

  function __construct($type){

    $this->borderColor = '#FFFFFF';           //#555
    $this->borderRadius = 0;

    if($type == 'pie' || $type == 'bar' || $type !== 'column'){
      $this->borderWidth = 1;
    } else {
      $this->borderWidth = 0;
    }

    $this->dataLabels = new HighRollerDataLabels();

    if($type == 'pie'){
      $this->size = '75%';                    // 100
      $this->center = array('50%', '50%');    // 25, 65
      $this->showInLegend = false;            // true
      $this->dataLabels->align = null;
      $this->dataLabels->enabled = true;
      $this->dataLabels->connectorWidth = 1;
      $this->dataLabels->connectorPadding = 5;
      $this->dataLabels->distance = 30;
      $this->dataLabels->softConnector = true;
    }

    $this->formatter = new HighRollerFormatter();
  }

}
?>