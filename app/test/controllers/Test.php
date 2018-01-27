<?php

/**
 * Test file
 *
 * This file is used to demonstrate the usage of Saral Framework
 *
 * @category Test
 * @package	Test
 * @version		0.1
 * @since		0.1
 */

/**
 * Test class
 *
 * Test
 *
 * @category Test
 * @package Test
 * @version Release: 0.1
 * @since 19.Dec.2017
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class Test extends SaralController
{

    /**
     * holds test model object
     *
     * @var object
     */
    private $test_model;

    /**
     * construct to initiate/create model object
     */
    public function __construct()
    {
        parent::__construct();
        $this->test_model = $this->loadModel("test/TestModel");
    }

    /**
     * index page
     */
    public function doIndex()
    {
        $data['css'] = array(
            'datatables/jquery.dataTables.min.css',
            'datatables/dataTables.bootstrap.css',
            'jquery-confirm.min.css',
            'styles.css'
        );
        $data['js'] = array(
            'datatables/jquery.dataTables.min.js',
            'datatables/dataTables.bootstrap.min.js',
            'jquery.validate.min.js',
            'jquery.form.min.js',
            'jquery.toaster.js',
            'jquery-confirm.min.js',
            'user.js'
        );
        $data['view'] = 'test/index';
        $this->loadView("layout", $data);
    }

    /**
     * user data for datatable
     */
    function doUserData()
    {
        $post = $this->getPostData();
        $users = $this->test_model->getUsers($post);
        echo json_encode($users);
    }

    /**
     * form to add/edit user details
     */
    public function doUserForm()
    {
        $post = $this->getPostData();
        if ($post['UserID']) {
            $data['user'] = $this->test_model->getUser($post);
        }
        $data['user_id'] = $post['UserID'];
        $data['view'] = 'test/form';
        $this->loadView('blank', $data);
    }

    /**
     * check email existence
     */
    public function doCheckEmail()
    {
        $post = $this->getPostData();
        if ($this->test_model->checkEmail($post)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * save/update user
     */
    public function doUserSave()
    {
        try {
            $post = $this->getPostData();

            $title = $post['UserID'] ? 'Edit User' : 'Add User';
            $this->test_model->saveUser($post);
            echo json_encode(array(
                'status' => 'success',
                'message' => $post['UserID'] ? 'User updated successfully' : 'User added successfully',
                'title' => $title
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'failure',
                'message' => $e->getMessage(),
                'title' => $title
            ));
        }
    }

    /**
     * delete user
     */
    public function doUserDelete()
    {
        try {
            $post = $this->getPostData();
            $title = 'Delete User';
            $this->test_model->deleteUser($post);
            echo json_encode(array(
                'status' => 'success',
                'message' => 'User deleted successfully.',
                'title' => $title
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'failure',
                'message' => $e->getMessage(),
                'title' => $title
            ));
        }
    }
}