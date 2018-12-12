<?php
require_once('validator.php');
class Reservation{
    public $source_type = null;
    public $reservation_type = null;
    public function __construct($source_type,  $reservation_type) {
        $this->source_type=$source_type;
        $this->reservation_type=$reservation_type;
    }
}

class AbstractTypeBehavior{

    private $under_from_type_list = null;
    private $exclude_from_type_list = null;

    private $under_to_type_list = null;
    private $exclude_to_type_list = null;

    protected $my_action = 'behavior action';

    public function __construct($under_from_type_list, $exclude_from_type_list, $under_to_type_list, $exclude_to_type_list) {
        $this->under_from_type_list=$under_from_type_list;
        $this->exclude_from_type_list=$exclude_from_type_list;
        $this->under_to_type_list=$under_to_type_list;
        $this->exclude_to_type_list=$exclude_to_type_list;
    }

    public function check_create_successful(){
        $my_behavior_from_type_validator = new FromTypeValidator();
        $my_behavior_to_type_validator = new ToTypeValidator();
        $my_behavior_from_type_validator->set_validator_successor($my_behavior_to_type_validator);
        $my_behavior_from_type_validator->validate($this);
    }

    public function is_in_list($reservation){
        if(!in_array($reservation->source_type, $this->under_from_type_list)){
            echo 'from type in list, so no further action';
            return false;
        }
        return true;
    }
}

class SomeTypeBehavior extends AbstractTypeBehavior{

    public function action($reservation){
        $is_in_list = $this->is_in_list($reservation);
        if(!$is_in_list){
            return false;
        }
        return $this->my_action;
    }
}


function setup_new_behavior(){
    # Engineer setup the new behavior and check
    $new_type_behavior = new SomeTypeBehavior(["a", "b", "d"], ["c"], ["x"], ["y", "z"]);
    $new_type_behavior->check_create_successful();
    return $new_type_behavior;
}

$my_new_type_behavior = setup_new_behavior();
# API code
$my_new_type_behavior->action(new Reservation('a', 'x'));