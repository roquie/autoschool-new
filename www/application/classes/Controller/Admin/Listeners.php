<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Listeners extends Controller_Admin_Base
{

    public function action_distrib()
    {
        $u = new Model_User();
        $list_users = $u->get_user_list(true);

        $edu = ORM::factory('Education')->find_all();
        $national = ORM::factory('Nationality')->find_all();
        $type_doc = ORM::factory('Documents')->find_all();

        $this->template->content =
            View::factory('admin/listeners/distrib', compact('errors', 'success', 'edu', 'national', 'type_doc', 'list_users'))
                ->render();
    }

    public function action_contract_check()
    {
        $post = $this->request->post();

        if (Security::is_token($post['csrf']) && $this->request->method() === Request::POST)
        {
            $id = $post['user_id'];
            if (isset($post['customer']))
            {
                try
                {
                    DB::update('listeners')
                        ->set(array('is_individual' => 0))
                        ->where('user_id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }
                $this->ajax_msg('Вы являетесь заказчиком');
            }
            else
            {
                try
                {
                    DB::update('listeners')
                        ->set(array('is_individual' => 1))
                        ->where('user_id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }
                $this->ajax_msg('Заказчик изменен');

            }
        }
    }

    public function action_getUser()
    {
        $this->auto_render = false;
        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $id = $this->request->post('user_id');

            $result = ORM::factory('Listeners', $id);

            /*if ($this->request->post('distrib') == '0')
            {
            }*/
            Session::instance()->set('checked_user', $result->user_id);

            $data['contract'] = array();

            $data['listener'] = $result->as_array();
            if ((int)$data['listener']['is_individual'] == 1) {
                $data['contract'] = $result->indy->as_array();
                $data['contract']['document_data_vydachi'] = Text::check_date($data['contract']['document_data_vydachi']);
            }

            $data['listener']['data_rojdeniya'] = Text::check_date($data['listener']['data_rojdeniya']);
            $data['listener']['document_data_vydachi'] = Text::check_date($data['listener']['document_data_vydachi']);
            $data['listener']['date_contract'] = Text::check_date($data['listener']['date_contract']);
            $data['listener']['data_med'] = Text::check_date($data['listener']['data_med']);

            $result = ORM::factory('Group', $data['listener']['group_id']);

            $staffs = $result->staff->find_all();

            foreach ($staffs as $key => $value) {
                $data['instructors'][$value->id] = $value->famil . ' '. UTF8::substr($value->imya,0, 1).'. ' . UTF8::substr($value->otch,0, 1).'.';;
            }

            $this->ajax_data($data);
        }
    }

    public function action_users_by_group()
    {
        $this->auto_render = false;
        $csrf = $this->request->post('csrf');
        if ($this->request->method() === Request::POST && Security::is_token($csrf))
        {
            $post = $this->request->post();
            $list_users = ((int)$post['group_id'] === 0) ? Model::factory('User')->get_user_list(false) : Model::factory('User')->by_group_id($post['group_id']);
            $this->ajax_data(
                View::factory('admin/html/listeners', compact('list_users'))->render()
            );
        }
    }

    public function action_update_user()
    {
        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $post = $this->request->post();
            $id = $post['user_id'];

            unset($post['csrf'], $post['user_id']);

            $valid = new Validation(
                Arr::map(
                    'Security::xss_clean',
                    Arr::map('trim', $post)
                )
            );
            $valid->rule('famil', 'not_empty');
            $valid->rule('famil', 'alpha', array(':value', true));
            $valid->rule('famil', 'min_length', array(':value', 2));
            $valid->rule('famil', 'max_length', array(':value', 50));
            $valid->rule('imya', 'not_empty');
            $valid->rule('imya', 'alpha', array(':value', true));
            $valid->rule('imya', 'min_length', array(':value', 2));
            $valid->rule('imya', 'max_length', array(':value', 50));
            //$valid->rule('otch', 'not_empty');
            $valid->rule('otch', 'alpha', array(':value', true));
            $valid->rule('otch', 'min_length', array(':value', 2));
            $valid->rule('otch', 'max_length', array(':value', 50));
            $valid->rule('tel', 'not_empty');
            $valid->rule('tel', 'phone', array(':value', 11));

            $post['data_rojdeniya'] =  Text::getDateUpdate($post['data_rojdeniya']);
            $post['document_data_vydachi'] = Text::getDateUpdate($post['document_data_vydachi']);
            if (isset($post['date_contract']))
                $post['date_contract'] = Text::getDateUpdate($post['date_contract']);
            if (isset($post['data_med']))
                $post['data_med'] = Text::getDateUpdate($post['data_med']);

            foreach ($post as $key => $value) {
                if ($value == '')
                    $post[$key] = NULL;
            }

            if ($valid->check())
            {
                try
                {
                    DB::update('listeners')
                        ->set($post)
                        ->where('user_id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }

                $this->ajax_msg('Данные успешно сохранены');
            }
            else
            {
                $errors = $valid->errors('register');
                $this->ajax_msg(array_shift($errors), 'error');
            }

        }
    }

    public function action_update_ind()
    {
        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $post = $this->request->post();

            $id = $post['listener_id'];
            $is_ind = $post['is_individual'];

            unset($post['csrf'], $post['is_individual']);

            $valid = new Validation(
                Arr::map(
                    'Security::xss_clean',
                    Arr::map('trim', $post)
                )
            );
            $valid->rule('famil', 'not_empty');
            $valid->rule('famil', 'alpha', array(':value', true));
            $valid->rule('famil', 'min_length', array(':value', 2));
            $valid->rule('famil', 'max_length', array(':value', 50));
            $valid->rule('imya', 'not_empty');
            $valid->rule('imya', 'alpha', array(':value', true));
            $valid->rule('imya', 'min_length', array(':value', 2));
            $valid->rule('imya', 'max_length', array(':value', 50));
            $valid->rule('otch', 'not_empty');
            $valid->rule('otch', 'alpha', array(':value', true));
            $valid->rule('otch', 'min_length', array(':value', 2));
            $valid->rule('otch', 'max_length', array(':value', 50));
            $valid->rule('tel', 'not_empty');
            $valid->rule('tel', 'phone', array(':value', 11));

            $post['document_data_vydachi'] = Text::getDateUpdate($post['document_data_vydachi']);

            if ($valid->check())
            {
                try
                {
                    $res = DB::select()
                        ->from('individual')
                        ->where('listener_id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }

                if (count($res) > 0)
                {
                    try
                    {
                        DB::update('individual')
                            ->set($post)
                            ->where('listener_id', '=', $id)
                            ->execute();
                    }
                    catch(Database_Exception $e)
                    {
                        $this->ajax_msg($e->getMessage(), 'error');
                    }
                 }
                else
                {
                    try
                    {
                        DB::insert('individual')
                            ->columns(array_keys($post))
                            ->values($post)
                            ->execute();
                    }
                    catch(Database_Exception $e)
                    {
                        $this->ajax_msg($e->getMessage(), 'error');
                    }
                }

                $this->ajax_msg('Данные успешно сохранены');
            }
            else
            {
                $errors = $valid->errors('register');
                $this->ajax_msg(array_shift($errors), 'error');
            }

        }
    }

    public function action_change_status()
    {
        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $post = $this->request->post();
            $id = $post['user_id'];

            unset($post['csrf'], $post['user_id']);

            $valid = new Validation(
                Arr::map(
                    'Security::xss_clean',
                    Arr::map('trim', $post)
                )
            );

            $valid->label('status', 'Поле статус');
            $valid->rule('status', 'not_empty');
            $valid->rule('status', 'digit');

            if ($valid->check())
            {
                try
                {
                    DB::update('listeners')
                        ->set($post)
                        ->where('id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }

                $this->ajax_msg('Статус изменен');
            }
            else
            {
                $errors = $valid->errors('register');
                $this->ajax_msg(array_shift($errors), 'error');
            }
        }
    }

    public function action_del_listener()
    {
        $csrf = pack('H*', $this->request->query('csrf'));

        if (Security::is_token($csrf) && $this->request->method() === Request::GET)
        {
            $id = $this->request->query('id');

            ORM::factory('Listeners', $id)->delete();

            $this->msg('Слушатель удален', 'success', 'admin');
        }
        else
            throw new HTTP_Exception_403('access denied');

    }

    public function action_add_desc_status()
    {
        $this->auto_render = false;

        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $post = $this->request->post();
            $id = $post['user_id'];

            unset($post['csrf'], $post['user_id']);

            $post = Arr::map(
                'Security::xss_clean',
                Arr::map('trim', $post)
            );

            try
            {
                DB::update('listeners')
                    ->set($post)
                    ->where('id', '=', $id)
                    ->execute();
            }
            catch(Database_Exception $e)
            {
                $this->ajax_msg($e->getMessage(), 'error');
            }
            $this->ajax_msg('Описание добавлено');
        }
    }

    public function action_delete()
    {
        $this->auto_render = false;
        $csrf = $this->request->post('csrf');

        if (Security::is_token($csrf) && $this->request->method() === Request::POST)
        {
            $post = $this->request->post();
            $id = $post['user_id'];

            $valid = new Validation(
                Arr::map(
                    'Security::xss_clean',
                    Arr::map('trim', $post)
                )
            );

            $valid->label('user_id', 'Поле слушатель');
            $valid->rule('user_id', 'not_empty');
            $valid->rule('user_id', 'digit');

            if ($valid->check())
            {
                try
                {
                    DB::delete('users')
                        ->where('id', '=', $id)
                        ->execute();
                }
                catch(Database_Exception $e)
                {
                    $this->ajax_msg($e->getMessage(), 'error');
                }

                $this->ajax_msg('Слушатель удален');
            }
            else
            {
                $errors = $valid->errors('register');
                $this->ajax_msg(array_shift($errors), 'error');
            }
        }
    }

}