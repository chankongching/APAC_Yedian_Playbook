<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->layout = '/layouts/mytest';
?>
      <!-- div class='input'><input placeholder="API_KEY" id="input_apiKey" name="apiKey" type="text"/></div -->
      <div class='input'><input placeholder="INPUT TOKEN HERE" id="input_token" name="token" type="text" value="<?php echo Yii::app()->user->getState('myTestToken'); ?>"/></div>
      <!-- div class='input'><input placeholder="ROOMKEY" id="input_roomKey" name="roomKey" type="text" readonly="readonly" value="<?php echo Yii::app()->user->getState('myTestRoom'); ?>"/></div -->
