<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Settings extends Controller_Admin_Base
{

    public function action_index()
    {
        $this->template->content = View::factory('admin/settings');
    }


}