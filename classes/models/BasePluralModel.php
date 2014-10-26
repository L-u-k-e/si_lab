<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

abstract class BasePluralModel {
    protected $pdo;
    protected $where, $where_variables, $order_by, $offset, $limit;

    protected function __construct($where = '', $where_variables = []) {
        $this->pdo = DB::instance();
        $this->where = $where;
        $this->where_variables = $where_variables;
        $this->order_by = '';
        $this->offset = '';
        $this->limit = '';
    }

    public function range($offset, $limit) {
        $this->offset = $offset;
        $this->limit = $limit;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        $direction = strtoupper($direction);
        if(in_array($direction, ['ASC', 'DESC'])) {
            $this->order_by = "$field $direction";
        } else {
            throw new Exception('Wrong order direction given.');
        }
        return $this;
    }

    public function getFullCount() {
        return $this->fetchAll("SELECT COUNT(*) AS c FROM $this->table_name", false)[0]['c'];
    }

    public function getAsArray() {
        return $this->fetchAll("SELECT * FROM $this->table_name", true);
    }

    private function composeFullQuery($query, $with_limit) {
        $full_query = $query;
        if($this->where) {
            $full_query .= " WHERE $this->where";
        }
        if($this->order_by) {
            $full_query .= " ORDER BY $this->order_by";
        }
        if($with_limit && $this->limit) {
            $full_query .= " LIMIT $this->limit";
            if($this->offset > 0) {
                $full_query .= " OFFSET $this->offset";
            }
        }
        return $full_query;
    }

    private function fetchAll($query, $with_limit) {
        $full_query = $this->composeFullQuery($query, $with_limit);
        $stmt = $this->pdo->prepare($full_query);
        if(count($this->where_variables) > 0) {
            foreach($this->where_variables as $name => $value) {
                $stmt->bindValue($name, $value);
            }
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $results;
    }

}