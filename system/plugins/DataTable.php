<?php

/**
 * Datatable plugin file
 *
 * This file is to generate datatable out of data provided including pagination, filters, sorter
 *
 * @category Saral
 * @package	DataTable
 * @version		0.4
 * @since		0.1
 */

/**
 * DataTable class
 *
 * Class is used generate datatable
 *
 * @category Saral
 * @package DataTable
 * @version Release: 0.4
 * @since 29.oct.2013
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class DataTable
{

    /**
     * gives clauses (conditions) part of query with params
     *
     * @param array $columns
     *            -- those appear in datatable and can be sorted
     * @param array $post
     *            -- post data sent
     * @param boolean $need_where
     *            -- tells whether you want WHERE keyword in return
     * @return array
     */
    public function getClauses($columns, $post, $need_where = true)
    {
        $params = array();
        
        /*
         * Paging
         */
        $limit = '';
        if ($post['length'] > 0)
            $limit = $post['start'] . ', ' . $post['length'];
        
        /*
         * Ordering
         */
        $order = (isset($post['order']) && isset($columns[$post['order'][0]['column']])) ? $columns[$post['order'][0]['column']] . " " . $post['order'][0]['dir'] : '';
        
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $where = "";
        if (! empty($post['search']['value'])) {
            $where = "(";
            for ($i = 0; $i < count($columns); $i ++) {
                $where .= $columns[$i] . " LIKE ? OR ";
                $params[] = "%{$post['search']['value']}%";
            }
            $where = substr_replace($where, "", - 3);
            $where .= ')';
        }
        
        $where2 = '';
        /* Individual column filtering */
        for ($i = 0; $i < count($columns); $i ++) {
            if (! empty($post['columns'][$i]['search']['value'])) {
                $where2 .= $columns[$i] . " LIKE ? OR ";
                $params[] = "%{$post['columns'][$i]['search']['value']}%";
            }
        }
        $where2 = substr_replace($where2, "", - 3);
        $where2 = (! empty($where2)) ? "(" . $where2 . ")" : '';
        
        $clause = ($where != '') ? $where : '';
        
        if ($where2 != '')
            if ($where != '')
                $clause .= ' OR ' . $where2;
            else
                $clause .= ' ' . $where2;
        
        if ($need_where && $clause != '') {
            $clause = ' WHERE ' . $clause;
        } else if (! $need_where && $clause != '') {
            $clause = ' AND ' . $clause;
        }
        
        if ($order != '')
            $clause .= ' ORDER BY ' . $order;
        if ($limit != '')
            $clause .= ' LIMIT ' . $limit;
        
        return array(
            'clauses' => $clause,
            'params' => $params
        );
    }
}