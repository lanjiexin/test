<?php

class AbstractValidator{
    private $validator_successor = null;

    public function __construct() {

    }

    public function set_validator_successor($validator_successor){
        $this->validator_successor = $validator_successor;
    }

    public function validate($some_behavior){
        $result = $this->validate_self_logic($some_behavior);
        if($result && $this->validator_successor !== null){
            return $this->validator_successor->validate($some_behavior);
        }
        return $result;
    }

    public function contain_all_element($full_list, $sub_list){
        $flag = true;
        foreach($full_list as $val){
            if(!in_array($val, $sub_list)){
                $flag = false;
                break;
            }
        }
        if(!$flag){
            return false;
        }
        return true;

    }

    public function validate_self_logic($reservation){

    }
}

class FromTypeValidator extends AbstractValidator {
    private $from_type_list = ['a', 'b', 'c', 'd'];

    public function validate_self_logic($some_behavior){
        echo 'go for from type validator';
        if($some_behavior == null){
            echo 'some behavior class error';
            return false;
        }

        $under_from_type_list = $some_behavior->under_from_type_list;
        $exclude_from_type_list = $some_behavior->exclude_from_type_list;
        if (empty($under_from_type_list) || empty($exclude_from_type_list)){
            echo 'some regarding belong type not set';
            return false;
        }

        $check = $this->contain_all_element($this->from_type_list, $under_from_type_list);
        if(!$check){
            echo 'I find some from under type, I don\'t know';
            return false;
        }
        $check = $this->contain_all_element($this->from_type_list, $exclude_from_type_list);
        if(!$check){
            echo 'I find some from exclude type, I don\'t know';
            return false;
        }

        # Check no overlap
        $intersection = array_intersect($under_from_type_list, $exclude_from_type_list);
        if($intersection){
            echo 'Oh no, overlap';
            return false;
        }

        # check union is full list
        $from_type_set = array_unique($this->from_type_list);
        $union_under_n_exclude = array_merge($under_from_type_list, $exclude_from_type_list);
        $union_under_n_exclude = array_unique($union_under_n_exclude);
        sort($from_type_set);
        sort($union_under_n_exclude);
        if($from_type_set != $union_under_n_exclude){
            echo 'h no, some type are not include';
            return false;
        }
        return true;
    }
}

class TOTypeValidator extends AbstractValidator {
    private $to_type_list = ['x', 'y', 'z'];

    public function validate_self_logic($some_behavior){
        echo 'go for to type validator';
        if($some_behavior == null){
            echo 'some behavior class error';
            return false;
        }

        $under_to_type_list = $some_behavior->under_to_type_list;
        $exclude_to_type_list = $some_behavior->exclude_to_type_list;
        if (empty($under_to_type_list) || empty($exclude_to_type_list)){
            echo 'some regarding belong type not set';
            return false;
        }

        $check = $this->contain_all_element($this->to_type_list, $under_to_type_list);
        if(!$check){
            echo 'I find some to under type, I don\'t know';
            return false;
        }
        $check = $this->contain_all_element($this->to_type_list, $exclude_to_type_list);
        if(!$check){
            echo 'I find some to exclude type, I don\'t know';
            return false;
        }

        # Check no overlap
        $intersection = array_intersect($under_to_type_list, $exclude_to_type_list);
        if($intersection){
            echo 'Oh no, overlap';
            return false;
        }

        # Chech union is full list
        $to_type_set = array_unique($this->to_type_list);
        $union_under_n_exclude = array_merge($under_to_type_list, $exclude_to_type_list);
        $union_under_n_exclude = array_unique($union_under_n_exclude);
        sort($to_type_set);
        sort($union_under_n_exclude);
        if($to_type_set != $union_under_n_exclude){
            echo 'h no, some type are not include';
            return false;
        }
        return true;
    }
}