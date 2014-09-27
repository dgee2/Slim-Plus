<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DGee2\SlimPlus\Model;

/**
 * Description of ViewBase
 *
 * @author Dan
 */
abstract class ViewBase extends TableBase {

	public function save() {
		return $this->store();
	}

}
