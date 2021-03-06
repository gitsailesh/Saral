<?php

/**
 * TestModel file
 *
 * This file is the test model
 *
 * @category Test
 * @package	TestModel
 * @version		0.1
 * @since		0.1
 */

/**
 * TestModel class
 *
 * Test Model
 *
 * @category Test
 * @package TestModel
 * @version Release: 0.1
 * @since 20.Dec.2017
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class TestModel extends SaralModel
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * sending users data to datatable
     *
     * @param array $post
     * @return array
     */
    function getUsers($post)
    {
        $dt = new DataTable();

        $columns = array(
            'UserID',
            'Username',
            'EmailID',
            'CreatedOn'
        );

        $cond = '';

        $clauses = $dt->getClauses($columns, $post);

        $qry = "SELECT SQL_CALC_FOUND_ROWS * FROM users";
        $qry .= $clauses['clauses'];
        $records = $this->getRecords($qry, $clauses['params'], true);

        $data = array();
        foreach ($records as $record) {
            $data[] = array(
                "<input type='checkbox' name='UserID[]' id='UserID{$record['UserID']}' class='UserID' value='$record[UserID]' />",
                $record['Username'],
                $record['EmailID'],
                date('d M Y h:i A', strtotime($record['CreatedOn']))
            );
        }
        $total_records = $this->getOne('SELECT FOUND_ROWS()');
        return array(
            "draw" => intval($post['draw']),
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        );
    }

    /**
     * check email for existenCe
     *
     * @param array $post
     * @return mixed
     */
    function checkEmail($post)
    {
        if ($post['UserID']) {
            return $this->getOne("SELECT COUNT(1) FROM users WHERE EmailID = ? AND UserID <> ?", array(
                $post['EmailID'],
                $post['UserID']
            ));
        } else {
            return $this->getOne("SELECT COUNT(1) FROM users WHERE EmailID = ?", array(
                $post['EmailID']
            ));
        }
    }

    /**
     * save/update user
     *
     * @param array $post
     * @throws Exception
     */
    function saveUser($post)
    {
        try {
            $now = date('Y-m-d H:i:s');
            $this->start();
            $params = array(
                'Username' => $post['Username'],
                'EmailID' => $post['EmailID']
            );

            if ($post['UserID']) {
                $this->updateRecord('users', $params, array(
                    'UserID' => $post['UserID']
                ));
            } else {
                $params['Password'] = $this->hashPassword($post['Password']);
                $params['CreatedOn'] = $now;
                $this->insertRecord('users', $params);
            }
            $this->save();
        } catch (Exception $e) {
            $this->undo();
            throw $e;
        }
    }

    /**
     * fetches user info for provided user_id
     *
     * @param array $post
     * @return array
     */
    public function getUser($post)
    {
        return $this->getRecord("SELECT * FROM users WHERE UserID = ?", array(
            $post['UserID']
        ), true);
    }

    /**
     * delete user
     *
     * @param array $post
     * @throws Exception
     */
    function deleteUser($post)
    {
        try {
            $this->start();
            foreach ($post['UserID'] as $user_id) {
                $this->deleteRecord("users", array(
                    'UserID' => $user_id
                ));
            }
            $this->save();
        } catch (Exception $e) {
            $this->undo();
            throw $e;
        }
    }
}