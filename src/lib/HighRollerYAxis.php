<?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 8:44 PM
 * Desc: HighRoller yAxis
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
 
class HighRollerYAxis {

  public $title;
  public $labels;
  public $min;
  public $max;
  public $plotLines;
  public $formatter;

  function __construct(){
    $this->title = new HighRollerTitle();
    $this->labels = new HighRollerYAxisLabels();
    $this->plotLines = array();
    $this->formatter = new HighRollerFormatter();
  }
  
}
?>